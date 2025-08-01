<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/ImageRepository.php';

// Initialize the database connection
$db = new Database();
$imageRepo = new ImageRepository($db->getPdo());

// Bio content
$bioTitle = "Biography";
$bioContent = "This is the biography of Jigar, the photographer behind FramedSoul. Here, you can learn about his journey, inspirations, and the stories behind his photographs.";

// Include header and menu
require_once '../templates/header.php';
require_once '../templates/menu.php';
?>

<div data-barba="container" data-barba-namespace="bio">
    <main class="main">
        <div class="p-bio">
            <div class="p-bio-content">
                <h1 class="p-bio-title"><?= htmlspecialchars($bioTitle) ?></h1>
                <div class="p-bio-text">
                    <p><?= htmlspecialchars($bioContent) ?></p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../templates/footer.php'; ?>