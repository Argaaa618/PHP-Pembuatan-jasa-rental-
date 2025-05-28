<?php
// galeri.php - Gallery page to display videos and photos from "galeri/drone galeri"

$mediaDir = 'galeri/drone galeri';

// Get all media files (videos and images) from the directory
$mediaFiles = array_filter(scandir($mediaDir), function($file) use ($mediaDir) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['mp4', 'jpg', 'jpeg', 'png', 'gif']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gallery - ARGA AERIAL</title>
<link rel="stylesheet" href="styles.css" />
<style>
.gallery-container {
    max-width: 1000px;
    margin: 20px auto;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}
.media-item {
    flex: 1 1 300px;
    max-width: 300px;
    box-shadow: 0 0 8px rgba(0,0,0,0.2);
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.media-item video,
.media-item img {
    width: 100%;
    height: auto;
    display: block;
}
.media-item video {
    max-height: 200px;
}
</style>
</head>
<body>
<?php include 'header.php'; ?>

<h1 style="text-align:center; margin-top: 20px;">Gallery</h1>
<div class="gallery-container">
    <?php foreach ($mediaFiles as $file): 
        $filePath = $mediaDir . '/' . $file;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['mp4'])): ?>
            <div class="media-item">
                <video controls preload="metadata" muted>
                    <source src="<?php echo htmlspecialchars($filePath); ?>" type="video/mp4" />
                    Your browser does not support the video tag.
                </video>
            </div>
        <?php elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
            <div class="media-item">
                <img src="<?php echo htmlspecialchars($filePath); ?>" alt="Gallery Image" />
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

</body>
</html>
