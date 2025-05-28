<?php
// Script to create an admin user with hashed password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        require_once 'db.php';

        // Check if username already exists
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Username already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
            $stmt_insert->bind_param("ss", $username, $hashed_password);
            if ($stmt_insert->execute()) {
                $success = 'Admin user created successfully.';
            } else {
                $error = 'Failed to create admin user.';
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
} else {
    // Auto create default admin user with username 'admin' and password 'admin' if not exists
    require_once 'db.php';
    $default_username = 'admin';
    $default_password = 'admin';

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $default_username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
        $stmt_insert = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
        $stmt_insert->bind_param("ss", $default_username, $hashed_password);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create Admin User</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="login-container">
    <h2>Create Admin User</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="POST" action="create_admin_user.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="admin" required />
        <label for="password">Password</label>
        <input type="password" id="password" name="password" value="admin" required />
        <button type="submit">Create Admin</button>
    </form>
    <p><a href="index.php">Back to Login</a></p>
</div>
</body>
</html>
