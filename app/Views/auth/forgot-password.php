<div class="text-center mb-4">
    <h3 class="fw-bold font-playfair">Forgot Password</h3>
    <p class="text-muted">Enter your email and we'll send you a reset link</p>
</div>

<form action="<?= url('/forgot-password') ?>" method="POST">
    <?= csrf_field() ?>
    <div class="mb-4">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" required autofocus>
    </div>
    <button type="submit" class="btn btn-gold w-100 py-2 mb-3">Send Reset Link</button>
    <p class="text-center"><a href="<?= url('/login') ?>" class="text-gold small">Back to Login</a></p>
</form>
