<div class="text-center mb-4">
    <h3 class="fw-bold font-playfair">Welcome Back</h3>
    <p class="text-muted">Sign in to your account</p>
</div>

<form action="/login" method="POST">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="your@email.com" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            <button type="button" class="input-group-text toggle-password" onclick="togglePassword(this)">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Remember me</label>
        </div>
        <a href="/forgot-password" class="small text-gold">Forgot password?</a>
    </div>

    <button type="submit" class="btn btn-gold w-100 py-2 mb-3">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>

    <div class="text-center mb-3">
        <span class="text-muted small">Or continue with</span>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="/auth/google" class="btn btn-outline-secondary w-50">
            <i class="bi bi-google me-1"></i> Google
        </a>
        <a href="/auth/facebook" class="btn btn-outline-secondary w-50">
            <i class="bi bi-facebook me-1"></i> Facebook
        </a>
    </div>

    <p class="text-center text-muted small mb-0">
        Don't have an account? <a href="/register" class="text-gold fw-semibold">Register here</a>
    </p>
</form>

<script>
function togglePassword(btn) {
    const input = btn.previousElementSibling;
    const icon = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { input.type = 'password'; icon.className = 'bi bi-eye'; }
}
</script>
