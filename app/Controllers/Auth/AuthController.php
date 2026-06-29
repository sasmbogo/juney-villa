<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Models\User;
use App\Core\Database;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm(): void
    {
        if (isLoggedIn()) {
            $this->redirect(isAdmin() ? '/admin/dashboard' : '/guest/dashboard');
            return;
        }
        $this->renderWithLayout('auth.login', ['pageTitle' => 'Login - Juney Villa'], 'layouts.auth');
    }

    public function login(): void
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request. Please try again.');
            $this->redirect('/login');
            return;
        }

        $email = $this->getInput('email', '');
        $password = $this->getInput('password', '');
        $remember = $this->getInput('remember');

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Please enter your email and password.');
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            // Log failed attempt
            $this->logLoginAttempt($email, 'failed');
            $this->setFlash('error', 'Invalid email or password.');
            $_SESSION['old_input'] = ['email' => $email];
            $this->redirect('/login');
            return;
        }

        if (!$user['email_verified_at']) {
            $this->setFlash('error', 'Please verify your email address first. Check your inbox for the verification link.');
            $this->redirect('/login');
            return;
        }

        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $this->setFlash('error', 'Account is temporarily locked. Please try again later.');
            $this->redirect('/login');
            return;
        }

        // Set session
        $this->setUserSession($user);
        $this->logLoginAttempt($email, 'success', (int)$user['id']);

        // Remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->update((int)$user['id'], ['remember_token' => $token]);
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
        }

        // Check if password change required
        if ($user['must_change_password']) {
            $this->redirect('/guest/profile?change_password=1');
            return;
        }

        // Redirect
        $intended = $_SESSION['intended_url'] ?? null;
        unset($_SESSION['intended_url']);

        if ($user['role_id'] <= 8) {
            $this->redirect($intended ?? '/admin/dashboard');
        } else {
            $this->redirect($intended ?? '/guest/dashboard');
        }
    }

    public function registerForm(): void
    {
        if (isLoggedIn()) {
            $this->redirect('/guest/dashboard');
            return;
        }
        $this->renderWithLayout('auth.register', ['pageTitle' => 'Register - Juney Villa'], 'layouts.auth');
    }

    public function register(): void
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request.');
            $this->redirect('/register');
            return;
        }

        $data = $this->getAllInput();
        $errors = $this->validate($data, [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required',
        ]);

        if ($data['password'] !== ($data['password_confirmation'] ?? '')) {
            $errors['password_confirmation'][] = 'Passwords do not match.';
        }

        // Check if email exists
        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) {
            $errors['email'][] = 'This email is already registered.';
        }

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fix the errors below.');
            $_SESSION['old_input'] = $data;
            $_SESSION['validation_errors'] = $errors;
            $this->redirect('/register');
            return;
        }

        try {
            $userId = $this->userModel->createUser([
                'role_id' => 9,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'password' => $data['password'],
                'is_active' => 1,
            ]);

            // Get verification token
            $user = $this->userModel->find($userId);

            // TODO: Send verification email
            $this->setFlash('success', 'Registration successful! Please check your email to verify your account.');
            $this->redirect('/login');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Registration failed. Please try again.');
            $this->redirect('/register');
        }
    }

    public function logout(): void
    {
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        $this->redirect('/login');
    }

    public function forgotPasswordForm(): void
    {
        $this->renderWithLayout('auth.forgot-password', ['pageTitle' => 'Forgot Password - Juney Villa'], 'layouts.auth');
    }

    public function forgotPassword(): void
    {
        if (!$this->verifyCsrf()) {
            $this->redirect('/forgot-password');
            return;
        }

        $email = $this->getInput('email', '');
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->update((int)$user['id'], [
                'password_reset_token' => $token,
                'password_reset_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ]);
            // TODO: Send reset email
        }

        $this->setFlash('success', 'If an account exists with that email, a password reset link has been sent.');
        $this->redirect('/forgot-password');
    }

    public function resetPasswordForm(string $token): void
    {
        $data = ['pageTitle' => 'Reset Password - Juney Villa', 'token' => $token];
        $this->renderWithLayout('auth.reset-password', $data, 'layouts.auth');
    }

    public function resetPassword(): void
    {
        if (!$this->verifyCsrf()) {
            $this->redirect('/login');
            return;
        }

        $token = $this->getInput('token', '');
        $password = $this->getInput('password', '');

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->setFlash('error', 'Invalid or expired reset link.');
            $this->redirect('/forgot-password');
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->userModel->update((int)$user['id'], [
            'password' => $hashedPassword,
            'password_reset_token' => '',
            'password_reset_expires' => null,
            'must_change_password' => 0,
        ]);

        $this->setFlash('success', 'Password reset successfully. Please login with your new password.');
        $this->redirect('/login');
    }

    public function verifyEmail(string $token): void
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email_verification_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->setFlash('error', 'Invalid verification link.');
            $this->redirect('/login');
            return;
        }

        $this->userModel->update((int)$user['id'], [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'email_verification_token' => '',
        ]);

        $this->setFlash('success', 'Email verified successfully! You can now login.');
        $this->redirect('/login');
    }

    private function setUserSession(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role_id'] = $user['role_id'];
        $_SESSION['user_avatar'] = $user['avatar'];

        // Load role name
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT name FROM roles WHERE id = ?");
        $stmt->execute([$user['role_id']]);
        $role = $stmt->fetch();
        $_SESSION['user_role'] = $role['name'] ?? 'Guest';

        // Load permissions
        $stmt = $db->prepare("SELECT p.slug FROM permissions p JOIN role_permissions rp ON p.id = rp.permission_id WHERE rp.role_id = ?");
        $stmt->execute([$user['role_id']]);
        $_SESSION['user_permissions'] = $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function logLoginAttempt(string $email, string $status, ?int $userId = null): void
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO login_logs (user_id, email, status, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$userId, $email, $status, $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '']);
        } catch (\Exception $e) {
            // Silently fail
        }
    }
}
