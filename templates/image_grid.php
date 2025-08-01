<!-- Image Grid Template - Matching Demo Design -->
<div data-barba="container" data-barba-namespace="home">
    <main class="main">
        <div class="p-home">
            <section class="p-home-grid-mode">
                <div class="p-home-grid-mode__contents">
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $index => $image): ?>
                            <a href="record/record<?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>.php" class="p-home-grid-mode__item">
                                <p class="p-home-grid-mode__item-num"><?= $index + 1 ?></p>
                                <div class="p-home-grid-mode__item-image">
                                    <img src="uploads/<?= htmlspecialchars($image['path']) ?>" alt="photo thumbnail" width="200" height="300" loading="lazy" decoding="async">
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                            <p style="font-size: 18px; color: #696969;">No images found. Please add some images through the admin panel.</p>
                            <?php if (isset($_SESSION['user'])): ?>
                                <a href="index.php?page=admin" style="display: inline-block; margin-top: 20px; padding: 12px 24px; background: #1d1d1d; color: #fff; text-decoration: none; border-radius: 5px;">Go to Admin Panel</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-home-grid-mode__area">
                    <div class="p-home-grid-mode__area-image">
                        <?php if (!empty($images)): ?>
                            <img src="uploads/<?= htmlspecialchars($images[0]['path']) ?>" alt="featured image">
                        <?php endif; ?>
                    </div>
                    <div class="p-home-grid-mode__area-text">
                        <span class="line"></span>
                        <span>FramedSoul</span>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
</div>