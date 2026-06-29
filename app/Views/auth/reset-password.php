<div class="text-center mb-4">
    <h3 class="fw-bold font-playfair">Reset Password</h3>
    <p class="text-muted">Enter your new password</p>
</div>
<form action="/reset-password" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" required minlength="8">
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-gold w-100 py-2">Reset Password</button>
</form>
