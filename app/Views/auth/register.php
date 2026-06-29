<div class="text-center mb-4">
    <h3 class="fw-bold font-playfair">Create Account</h3>
    <p class="text-muted">Join us for exclusive villa experiences</p>
</div>

<form action="/register" method="POST">
    <?= csrf_field() ?>
    
    <div class="row g-3">
        <div class="col-6">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= old('first_name') ?>" required>
        </div>
        <div class="col-6">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= old('last_name') ?>" required>
        </div>
    </div>

    <div class="mb-3 mt-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>" placeholder="+255...">
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required minlength="8">
        <small class="text-muted">Minimum 8 characters</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label small" for="terms">
            I agree to the <a href="#" class="text-gold">Terms of Service</a> and <a href="#" class="text-gold">Privacy Policy</a>
        </label>
    </div>

    <button type="submit" class="btn btn-gold w-100 py-2 mb-3">
        <i class="bi bi-person-plus me-2"></i>Create Account
    </button>

    <p class="text-center text-muted small mb-0">
        Already have an account? <a href="/login" class="text-gold fw-semibold">Sign in</a>
    </p>
</form>
