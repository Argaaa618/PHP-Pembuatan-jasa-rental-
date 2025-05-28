<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($username === '') {
        $error = 'Username is required.';
    } elseif ($password === '') {
        $error = 'Password is required.';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match.';
    } else {
        require_once 'db.php';

        // Check if username exists
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Username already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt_insert->bind_param("ss", $username, $hashed_password);
            if ($stmt_insert->execute()) {
                $success = true;
            } else {
                $error = 'Failed to register user.';
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>REGISTER</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<video autoplay muted loop id="background-video" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1;">
    <source src="assets/background.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<div class="login-container">
<div class="logo-center" style="text-align: center; margin-bottom: 20px;">
<img src="./assets/logo1.png" alt="ARGA AERIAL Logo" style="width: 300px; height: auto; background: transparent; box-shadow: none;" />
</div>
<h2>REGISTER</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="success">Registration successful! <a href="index.php">Login here</a>.</div>
    <?php endif; ?>
    <?php if (!$success): ?>
    <form method="POST" action="register.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
        <label for="password_confirm">Confirm Password</label>
        <input type="password" id="password_confirm" name="password_confirm" required />
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login here</a></p>
    <?php endif; ?>
</div>
</body>
</html>
