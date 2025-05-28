<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$drone_id = $_POST['drone_id'] ?? null;
if (!$drone_id) {
    header('Location: drones.php');
    exit();
}

// Sample drone data (same as drones.php)
$drones = [
    1 => ['name' => 'DJI Phantom 4', 'price' => 800000],
    2 => ['name' => 'DJI MINI 2', 'price' => 350000],
    3 => ['name' => 'MAVIC PRO', 'price' => 600000],
    4 => ['name' => 'FPV DRONE', 'price' => 500000],
];

$drone = $drones[$drone_id] ?? null;
if (!$drone) {
    header('Location: drones.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rent'])) {
    $renter_name = trim($_POST['renter_name'] ?? '');
    $renter_email = trim($_POST['renter_email'] ?? '');
    $renter_phone = trim($_POST['renter_phone'] ?? '');
    $rental_days = intval($_POST['rental_days'] ?? 0);

    if ($renter_name === '') {
        $errors[] = 'Renter name is required.';
    }
    if ($renter_email === '') {
        $errors[] = 'Renter email is required.';
    } elseif (!filter_var($renter_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Renter email is invalid.';
    }
    if ($renter_phone === '') {
        $errors[] = 'Renter phone is required.';
    }
    if ($rental_days <= 0) {
        $errors[] = 'Rental days must be greater than zero.';
    }

if (empty($errors)) {
        require_once 'db.php';

        $user_id = $_SESSION['user_id'];
        $total_price = $drone['price'] * $rental_days;

        $stmt = $mysqli->prepare("INSERT INTO rentals (user_id, drone_id, renter_name, renter_email, renter_phone, rental_days, price_per_day, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssiii", $user_id, $drone_id, $renter_name, $renter_email, $renter_phone, $rental_days, $drone['price'], $total_price);

        if ($stmt->execute()) {
            $success = true;
            $_SESSION['rental'] = [
                'drone_id' => $drone_id,
                'drone_name' => $drone['name'],
                'renter_name' => $renter_name,
                'renter_email' => $renter_email,
                'renter_phone' => $renter_phone,
                'rental_days' => $rental_days,
                'price_per_day' => $drone['price'],
                'total_price' => $total_price,
            ];
            header('Location: receipt.php');
            exit();
        } else {
            $errors[] = 'Failed to save rental data. Please try again.';
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
<title>Rent Drone - ARGA AERIAL</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="dashboard-container">
    <h1>Rent <?= htmlspecialchars($drone['name']) ?></h1>
    <?php if ($success): ?>
        <p>Thank you, <?= htmlspecialchars($renter_name) ?>! Your rental for <?= htmlspecialchars($drone['name']) ?> for <?= $rental_days ?> day(s) has been received.</p>
        <a href="dashboard.php">Back to Dashboard</a>
    <?php else: ?>
        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="rent.php">
            <input type="hidden" name="drone_id" value="<?= $drone_id ?>" />
            <label for="renter_name">NAMA PILOT</label>
            <input type="text" id="renter_name" name="renter_name" required />
            <label for="renter_email">EMAIL PILOT </label>
            <input type="email" id="renter_email" name="renter_email" required />
            <label for="renter_phone">NO TELP PILOT </label>
            <input type="text" id="renter_phone" name="renter_phone" required />
            <label for="rental_days">DURASI SEWA (hari)</label>
            <input type="number" id="rental_days" name="rental_days" min="1" required />
            <button type="submit" name="submit_rent">PESAN DRONE</button>
        </form>
        <a href="drones.php">Back to Drones</a>
    <?php endif; ?>
    <br />
    <a href="logout.php" class="logout-link">Logout</a>
</div>
</body>
</html>
