<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['rental'])) {
    header('Location: drones.php');
    exit();
}
$rental = $_SESSION['rental'];
// Clear rental data after displaying receipt
unset($_SESSION['rental']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Payment Receipt - ARGA FLY SKY</title>
<link rel="stylesheet" href="styles.css" />
<style>
.receipt-container {
    max-width: 600px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.receipt-container h1 {
    color: #0077cc;
    margin-bottom: 20px;
}
.receipt-container table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.receipt-container table th,
.receipt-container table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}
.receipt-container table th {
    background-color: #f0f4f8;
    color: #0077cc;
}
.back-link {
    display: inline-block;
    padding: 12px 20px;
    background-color: #0077cc;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 700;
    transition: background-color 0.3s ease;
}
.back-link:hover {
    background-color: #005fa3;
}
</style>
</head>
<body>
<div class="receipt-container">
    <h1>BUKTI TRANSAKSI</h1>
    <table>
        <tr>
            <th>NAMA DRONE </th>
            <td><?= htmlspecialchars($rental['drone_name']) ?></td>
        </tr>
        <tr>
            <th>NAMA PILOT</th>
            <td><?= htmlspecialchars($rental['renter_name']) ?></td>
        </tr>
        <tr>
            <th>EMAIL PILOT</th>
            <td><?= htmlspecialchars($rental['renter_email']) ?></td>
        </tr>
        <tr>
            <th>NOMOR TELP YANG DAPAT DIHUBUNGI</th>
            <td><?= htmlspecialchars($rental['renter_phone']) ?></td>
        </tr>
        <tr>
            <th>LAMA DRONE DISEWA</th>
            <td><?= htmlspecialchars($rental['rental_days']) ?></td>
        </tr>
        <tr>
            <th>HARGA/hari</th>
            <td>Rp <?= number_format($rental['price_per_day'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th>TOTAL HARGA</th>
            <td>Rp <?= number_format($rental['total_price'], 0, ',', '.') ?></td>
        </tr>
    </table>
    <a href="drones.php" class="back-link">Kembali</a>
</div>
</body>
</html>
