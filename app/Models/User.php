<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'role_id', 'first_name', 'last_name', 'email', 'phone', 'password',
        'avatar', 'date_of_birth', 'gender', 'nationality', 'address', 'city',
        'country', 'id_type', 'id_number', 'email_verified_at', 'email_verification_token',
        'two_factor_enabled', 'two_factor_secret', 'google_id', 'facebook_id',
        'remember_token', 'password_reset_token', 'password_reset_expires',
        'must_change_password', 'last_login_at', 'last_login_ip', 'login_attempts',
        'locked_until', 'preferred_language', 'preferred_currency', 'is_active'
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            return null;
        }
        if (!password_verify($password, $user['password'])) {
            $this->incrementLoginAttempts($user['id']);
            return null;
        }
        if (!$user['is_active']) {
            return null;
        }
        $this->resetLoginAttempts($user['id']);
        $this->updateLastLogin($user['id']);
        return $user;
    }

    public function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $data['email_verification_token'] = bin2hex(random_bytes(32));
        return $this->create($data);
    }

    private function incrementLoginAttempts(int $userId): void
    {
        $this->rawExecute(
            "UPDATE users SET login_attempts = login_attempts + 1, locked_until = IF(login_attempts >= 4, DATE_ADD(NOW(), INTERVAL 30 MINUTE), locked_until) WHERE id = :id",
            ['id' => $userId]
        );
    }

    private function resetLoginAttempts(int $userId): void
    {
        $this->rawExecute(
            "UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = :id",
            ['id' => $userId]
        );
    }

    private function updateLastLogin(int $userId): void
    {
        $this->rawExecute(
            "UPDATE users SET last_login_at = NOW(), last_login_ip = :ip WHERE id = :id",
            ['id' => $userId, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '']
        );
    }

    public function getByRole(int $roleId): array
    {
        return $this->where(['role_id' => $roleId]);
    }

    public function getStaff(): array
    {
        return $this->rawQuery("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.role_id <= 8 ORDER BY u.role_id, u.first_name");
    }

    public function getGuests(): array
    {
        return $this->where(['role_id' => 9]);
    }
}
