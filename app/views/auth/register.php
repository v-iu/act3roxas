<?php require_once '../app/views/layouts/header.php'; ?>
<h2>Register</h2>
<?php if (!empty($data['error'])): ?>
    <div class="alert alert-danger"><?php echo $data['error']; ?></div>
<?php endif; ?>
<form method="post" action="/register">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" id="username" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>
<?php require_once '../app/views/layouts/footer.php'; ?>