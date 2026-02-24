<?php require_once '../app/views/layouts/header.php'; ?>
<h2>User List</h2>
<table class="table table-striped">
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th></tr></thead>
    <tbody>
    <?php foreach ($data['users'] as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo $user['created_at']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../app/views/layouts/footer.php'; ?>