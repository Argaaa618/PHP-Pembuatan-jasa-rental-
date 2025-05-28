<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    require_once 'db.php';

    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $user_name, $hashed_password, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $user_name;
            $_SESSION['role'] = $role;
            header('Location: drones.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - ARGA AERIAL</title>
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
<h2>LOGIN ARGA AERIAL</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
        <button type="submit">Login</button>
    </form>
    <p class="black-indent">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
