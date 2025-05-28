<?php
session_start();

// Simple admin check - assuming user role stored in session
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit();
}

// Path to drone data JSON file
$dataFile = 'drones_data.json';

// Load existing drone data or initialize default
if (file_exists($dataFile)) {
    $drones = json_decode(file_get_contents($dataFile), true);
} else {
    $drones = [
        [
            'id' => 1,
            'name' => 'DJI Phantom 4',
            'price' => 800000,
            'specifications' => [
                'Weight' => '1380 g',
                'Flight Time' => '30 minutes',
                'Max Speed' => '72 km/h',
                'Camera' => '12 MP, 4K video',
            ],
            'pickup_location' => 'Jakarta Store',
        ],
        [
            'id' => 2,
            'name' => 'DJI MINI 2',
            'price' => 350000,
            'specifications' => [
                'Weight' => '249 g',
                'Flight Time' => '31 minutes',
                'Max Speed' => '57.6 km/h',
                'Camera' => '12 MP, 4K video',
            ],
            'pickup_location' => 'Bandung Store',
        ],
        [
            'id' => 3,
            'name' => 'MAVIC PRO',
            'price' => 600000,
            'specifications' => [
                'Weight' => '743 g',
                'Flight Time' => '27 minutes',
                'Max Speed' => '65 km/h',
                'Camera' => '12 MP, 4K video',
            ],
            'pickup_location' => 'Surabaya Store',
        ],
        [
            'id' => 4,
            'name' => 'FPV DRONE',
            'price' => 500000,
            'specifications' => [
                'Weight' => '795 g',
                'Flight Time' => '10 minutes',
                'Max Speed' => '140 km/h',
                'Camera' => '4K video',
            ],
            'pickup_location' => 'Yogyakarta Store',
        ],
    ];
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $updatedDrones = [];
    foreach ($_POST['drones'] as $index => $drone) {
        $id = intval($drone['id']);
        $name = trim($drone['name']);
        $price = floatval($drone['price']);
        $pickup_location = trim($drone['pickup_location']);
        $specifications = [];
        if (isset($drone['spec_names']) && isset($drone['spec_values'])) {
            for ($i = 0; $i < count($drone['spec_names']); $i++) {
                $specName = trim($drone['spec_names'][$i]);
                $specValue = trim($drone['spec_values'][$i]);
                if ($specName !== '') {
                    $specifications[$specName] = $specValue;
                }
            }
        }
        // Handle photo upload
        $photoFilename = $drones[$index]['photo'] ?? '';
        if (isset($_FILES['drones']['name'][$index]['photo']) && $_FILES['drones']['error'][$index]['photo'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $tmpName = $_FILES['drones']['tmp_name'][$index]['photo'];
            $originalName = basename($_FILES['drones']['name'][$index]['photo']);
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            $newFilename = 'drone_' . $id . '_' . time() . '.' . $ext;
            $destination = $uploadDir . $newFilename;
            if (move_uploaded_file($tmpName, $destination)) {
                $photoFilename = $newFilename;
            }
        }
        if ($name === '' || $price <= 0) {
            $errors[] = "Drone ID $id: Name and price must be valid.";
            continue;
        }
        $updatedDrones[] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'specifications' => $specifications,
            'pickup_location' => $pickup_location,
            'photo' => $photoFilename,
        ];
    }
    if (empty($errors)) {
        file_put_contents($dataFile, json_encode($updatedDrones, JSON_PRETTY_PRINT));
        $drones = $updatedDrones;
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin - Manage Drones</title>
<link rel="stylesheet" href="styles.css" />
<style>
    .spec-row {
        display: flex;
        gap: 10px;
        margin-bottom: 6px;
    }
    .spec-row input {
        flex: 1;
        padding: 6px;
        font-size: 14px;
    }
    .add-spec-btn {
        margin-top: 10px;
        padding: 6px 12px;
        font-size: 14px;
        cursor: pointer;
    }
    .drone-form {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        background: #f9f9f9;
    }
</style>
<script>
function addSpecRow(droneIndex) {
    const container = document.getElementById('specs-container-' + droneIndex);
    const div = document.createElement('div');
    div.className = 'spec-row';
    div.innerHTML = '<input type="text" name="drones[' + droneIndex + '][spec_names][]" placeholder="Specification Name" />' +
                    '<input type="text" name="drones[' + droneIndex + '][spec_values][]" placeholder="Specification Value" />' +
                    '<button type="button" onclick="this.parentNode.remove()">Remove</button>';
    container.appendChild(div);
}
</script>
</head>
<body>
<video autoplay muted loop id="background-video" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1;">
    <source src="assets/background.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<div class="dashboard-container">
<div class="logo-center" style="text-align: center; margin-bottom: 20px;">
<img src="./assets/logo.png" alt="ARGA AERIAL Logo" style="width: 200px; height: auto; background: transparent; box-shadow: none;" />
</div>
<h1>Admin - Kelola Data Drone ARGA AERIAL</h1>
    <?php if ($success): ?>
        <div class="success">Data drone berhasil disimpan.</div>
    <?php endif; ?>
    <?php if ($errors): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="POST" action="admin.php" enctype="multipart/form-data">
        <?php foreach ($drones as $index => $drone): ?>
            <div class="drone-form">
                <input type="hidden" name="drones[<?= $index ?>][id]" value="<?= $drone['id'] ?>" />
                <label>Nama Drone:</label>
                <input type="text" name="drones[<?= $index ?>][name]" value="<?= htmlspecialchars($drone['name']) ?>" required />
                <label>Harga (per hari):</label>
                <input type="number" name="drones[<?= $index ?>][price]" value="<?= htmlspecialchars($drone['price']) ?>" min="0" required />
                <label>Lokasi Pengambilan:</label>
                <input type="text" name="drones[<?= $index ?>][pickup_location]" value="<?= htmlspecialchars($drone['pickup_location']) ?>" />
                <label>Foto Drone:</label>
                <?php if (!empty($drone['photo'])): ?>
                    <div>
                        <img src="uploads/<?= htmlspecialchars($drone['photo']) ?>" alt="Foto Drone" style="max-width: 200px; max-height: 150px; display: block; margin-bottom: 10px;" />
                    </div>
                <?php endif; ?>
                <input type="file" name="drones[<?= $index ?>][photo]" accept="image/*" />
                <label>Spesifikasi:</label>
                <div id="specs-container-<?= $index ?>">
                    <?php foreach ($drone['specifications'] as $specName => $specValue): ?>
                        <div class="spec-row">
                            <input type="text" name="drones[<?= $index ?>][spec_names][]" value="<?= htmlspecialchars($specName) ?>" placeholder="Specification Name" />
                            <input type="text" name="drones[<?= $index ?>][spec_values][]" value="<?= htmlspecialchars($specValue) ?>" placeholder="Specification Value" />
                            <button type="button" onclick="this.parentNode.remove()">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-spec-btn" onclick="addSpecRow(<?= $index ?>)">Tambah Spesifikasi</button>
            </div>
        <?php endforeach; ?>
        <button type="submit" name="save">Simpan Perubahan</button>
    </form>
    <a href="drones.php">Kembali ke Daftar Drone</a>
</div>
</body>
</html>
