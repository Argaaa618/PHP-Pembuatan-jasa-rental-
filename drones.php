t<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$dataFile = 'drones_data.json';
if (file_exists($dataFile)) {
    $drones = json_decode(file_get_contents($dataFile), true);
} else {
    $drones = [
        [
            'id' => 1,
            'name' => 'DJI Phantom 4',
            'price' => 800000,
            'image' => ' https://www.blibli.com/friends-backend/wp-content/uploads/2024/05/B140249-Cover-spesifikasi-dji-phantom-4-pro-scaled.jpg',
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
            'image' => 'https://cdn.mos.cms.futurecdn.net/v2/t:0,l:936,cw:2497,ch:1873,q:80,w:2497/qPDUPGiA6qqdzFEMWryRUb.jpg',
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
            'image' => 'https://www.newsshooter.com/wp-content/uploads/2016/10/DSC05740-1-1.jpg',
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
            'name' => 'FPV DRONE ',
            'price' => 500000,
            'image' => 'https://i.ytimg.com/vi/2Uvg6VWTuxY/maxresdefault.jpg',
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Drones - ARGA AERIAL</title>
<link rel="stylesheet" href="styles.css" />
<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-papb+X6X+6Q6XQ6X6XQ6X6XQ6X6XQ6X6XQ6X6XQ6X6XQ6X6XQ6X6XQ6X6XQ6X6XQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="drones-page">
<?php include 'header.php'; ?>
<main class="dashboard-container">
    <h1>Drone Tersedia untuk Disewa</h1>
    <section class="rental-terms">
        <h2>Peringatan dan Syarat & Ketentuan</h2>
        <p>Harap baca syarat berikut dengan seksama sebelum menyewa drone:</p>
        <ul>
            <li>Penyewa harus berusia minimal 18 tahun dan menyediakan identifikasi yang valid.</li>
            <li>Drone harus digunakan dengan bertanggung jawab dan sesuai dengan hukum serta peraturan setempat.</li>
            <li>Segala kerusakan pada drone selama masa sewa menjadi tanggung jawab penyewa.</li>
            <li>Penyewa harus mengembalikan drone tepat waktu dan dalam kondisi baik.</li>
            <li>Perusahaan penyewaan tidak bertanggung jawab atas kecelakaan atau cedera yang disebabkan oleh penggunaan drone.</li>
        </ul>
    </section>
    <div class="drone-list">
        <?php foreach ($drones as $drone): ?>
            <div id="drone-card-<?= $drone['id'] ?>" class="drone-card">
                <img src="<?= htmlspecialchars($drone['image']) ?>" alt="<?= htmlspecialchars($drone['name']) ?>" />
                <div class="drone-info">
                    <h3><?= htmlspecialchars($drone['name']) ?></h3>
                    <p class="price">Rp <?= number_format($drone['price'], 0, ',', '.') ?></p>
                    <button type="button" class="toggle-specs-button" onclick="toggleSpecs(<?= $drone['id'] ?>)" id="toggle-btn-<?= $drone['id'] ?>">Show Specs</button>
                    <div id="specs-<?= $drone['id'] ?>" class="drone-specs" style="display:none; transition: max-height 0.3s ease; overflow: hidden;">
                        <h4>Specifications:</h4>
                        <ul>
                            <?php foreach ($drone['specifications'] as $specName => $specValue): ?>
                                <li><strong><?= htmlspecialchars($specName) ?>:</strong> <?= htmlspecialchars($specValue) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <form method="POST" action="rent.php">
                        <input type="hidden" name="drone_id" value="<?= $drone['id'] ?>" />
                        <button type="submit" class="continue-button">Lanjutkan</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<footer class="site-footer">
    <div class="container">
        <p>&copy; 2024 ARGA FLY SKY. All rights reserved.</p>
        <p>Contact Admin: <a href="mailto:admin@argaflysky.com">admin@argaflysky.com</a> | Phone: +62 812 3456 7890</p>
    </div>
</footer>
<script>
</script>
<script>
function toggleSpecs(droneId) {
    var specsDiv = document.getElementById('specs-' + droneId);
    if (specsDiv.style.display === 'none') {
        specsDiv.style.display = 'block';
    } else {
        specsDiv.style.display = 'none';
    }
}
function toggleSpecs(droneId) {
    var specsDiv = document.getElementById('specs-' + droneId);
    var toggleBtn = document.getElementById('toggle-btn-' + droneId);
    if (specsDiv.style.display === 'none' || specsDiv.style.display === '') {
        specsDiv.style.display = 'block';
        toggleBtn.textContent = 'Hide Specs';
    } else {
        specsDiv.style.display = 'none';
        toggleBtn.textContent = 'Show Specs';
    }
}
</script>
</body>
</html>

