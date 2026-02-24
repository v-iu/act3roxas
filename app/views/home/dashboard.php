<?php require_once '../app/views/layouts/header.php'; ?>
<h2>Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($data['user']['username']); ?>!</p>
<p>Your email: <?php echo htmlspecialchars($data['user']['email']); ?></p>
<?php require_once '../app/views/layouts/footer.php'; ?>