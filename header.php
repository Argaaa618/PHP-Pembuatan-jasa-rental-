<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<header class="site-header">
    <div class="container header-container">
        <div class="logo-nav-wrapper">
<img src="assets/logo1.png" alt="ARGA AERIAL Logo" class="site-logo smaller-logo" />
        </div>
        <nav class="site-nav">
            <ul class="nav-list">
<li><a href="galeri.php" class="nav-link <?php echo ($currentPage == 'galeri.php') ? 'active' : ''; ?>">Gallery</a></li>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?php echo ($currentPage == 'services.php') ? 'active' : ''; ?>">Our Services <i class="fa fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="service1.php" class="dropdown-item">Service 1</a></li>
                        <li><a href="service2.php" class="dropdown-item">Service 2</a></li>
                        <li><a href="service3.php" class="dropdown-item">Service 3</a></li>
                    </ul>
                </li>
                <!-- <li><a href="#" class="nav-link">Blog</a></li> -->
                <!-- <li><a href="#" class="nav-link">Contact us</a></li> -->
                <li><a href="drones.php" class="nav-link <?php echo ($currentPage == 'drones.php') ? 'active' : ''; ?>">Sewa Drone </a></li>
            </ul>
        </nav>
    </div>
</header>
