<?php
// FramedSoul Gallery - File-based system (no database required)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Simple file-based image system
$images = [];
$uploadsDir = __DIR__ . '/uploads';

// Create uploads directory if it doesn't exist
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Scan for actual images in uploads folder
if (is_dir($uploadsDir)) {
    $files = array_diff(scandir($uploadsDir), array('.', '..'));
    $imageCount = 0;
    foreach ($files as $file) {
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
            $imageCount++;
            $images[] = [
                'id' => $imageCount,
                'path' => $file,
                'filename' => $file,
                'title' => 'Photo ' . $imageCount,
                'description' => 'Captured through my lens'
            ];
        }
    }
}

// If no images found, create demo dataset for 36 images
if (empty($images)) {
    for ($i = 1; $i <= 36; $i++) {
        $filename = ($i <= 33) ? "img{$i}.webp" : "img{$i}.jpg";
        $images[] = [
            'id' => $i,
            'path' => $filename,
            'filename' => $filename,
            'title' => "Photo {$i}",
            'description' => 'Seeing world through my Lens'
        ];
    }
}

require_once '../templates/header.php';
require_once '../templates/menu.php';
?>

<style>
/* Creative Black & White Gallery Design */
.p-home {
    background: linear-gradient(45deg, #000000 0%, #1a1a1a 25%, #000000 50%, #0d0d0d 75%, #000000 100%);
    position: relative;
    overflow: hidden;
}

.p-home::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(255,255,255,0.03) 0%, transparent 40%);
    pointer-events: none;
    z-index: 1;
}

.p-home-grid-mode__contents {
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(10px);
    border-right: 2px solid rgba(255,255,255,0.1);
    position: relative;
    z-index: 2;
}

.p-home-grid-mode__item {
    position: relative;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0.3) 100%);
}

.p-home-grid-mode__item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s;
    z-index: 2;
}

.p-home-grid-mode__item:hover::before {
    left: 100%;
}

.p-home-grid-mode__item:hover {
    transform: translateY(-10px) scale(1.05);
    border-color: rgba(255,255,255,0.3);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6), 
                0 0 20px rgba(255,255,255,0.1);
}

.p-home-grid-mode__item-num {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0,0,0,0.8);
    color: #ffffff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    z-index: 3;
    border: 1px solid rgba(255,255,255,0.2);
}

.p-home-grid-mode__item-image img {
    filter: grayscale(100%) contrast(1.2) brightness(0.9);
    transition: all 0.4s ease;
}

.p-home-grid-mode__item:hover .p-home-grid-mode__item-image img {
    filter: grayscale(80%) contrast(1.4) brightness(1.1);
    transform: scale(1.1);
}

.p-home-grid-mode__area {
    background: radial-gradient(ellipse at center, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0.9) 70%);
    border-left: 2px solid rgba(255,255,255,0.1);
    position: relative;
}

.p-home-grid-mode__area::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 200px;
    height: 200px;
    border: 2px solid rgba(255,255,255,0.1);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: pulse 3s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 0.7; transform: translate(-50%, -50%) scale(1.1); }
}

.p-home-grid-mode__area-text {
    background: rgba(0,0,0,0.8);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

.p-home-grid-mode__area-text span {
    color: #ffffff;
    text-shadow: 0 0 10px rgba(255,255,255,0.3);
}

.p-home-grid-mode__area-text .line {
    background: linear-gradient(90deg, transparent, #ffffff, transparent);
    box-shadow: 0 0 10px rgba(255,255,255,0.5);
}

.p-home-view {
    background: rgba(0,0,0,0.9);
    border: 2px solid rgba(255,255,255,0.2);
    box-shadow: 0 10px 30px rgba(0,0,0,0.8);
}

.p-home-view__button {
    position: relative;
    overflow: hidden;
}

.p-home-view__button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.p-home-view__button:hover::before {
    left: 100%;
}

.p-home-view__background {
    background: linear-gradient(45deg, #ffffff, #f0f0f0);
    box-shadow: 0 0 15px rgba(255,255,255,0.3);
}

/* Creative geometric patterns */
.p-home-grid-mode__contents::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 2px;
    height: 100%;
    background: linear-gradient(to bottom, 
        transparent 0%, 
        rgba(255,255,255,0.3) 20%, 
        rgba(255,255,255,0.1) 50%, 
        rgba(255,255,255,0.3) 80%, 
        transparent 100%);
    animation: shimmer 4s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

/* No images styling */
.no-images {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: rgba(0,0,0,0.8);
    border: 2px dashed rgba(255,255,255,0.3);
    border-radius: 15px;
    color: #ffffff;
}

.no-images h3 {
    font-size: 2rem;
    margin-bottom: 20px;
    text-shadow: 0 0 20px rgba(255,255,255,0.3);
}

.no-images a {
    color: #ffffff !important;
    text-decoration: underline;
    transition: all 0.3s ease;
}

.no-images a:hover {
    text-shadow: 0 0 10px rgba(255,255,255,0.7);
}
</style>

<!-- Main Home Container -->
<div class="p-home" data-barba="container" data-barba-namespace="home">
    <!-- Grid Mode Layout -->
    <div class="p-home-grid-mode">
        <!-- Left Side - Image Grid -->
        <div class="p-home-grid-mode__contents">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                    <a href="record/record<?= str_pad($image['id'], 2, '0', STR_PAD_LEFT) ?>.php" class="p-home-grid-mode__item">
                        <div class="p-home-grid-mode__item-num"><?= $image['id'] ?></div>
                        <div class="p-home-grid-mode__item-image">
                            <?php 
                            // Use the correct path - if it starts with http, use as-is, otherwise prepend uploads/
                            $imageSrc = (strpos($image['path'], 'http') === 0) ? $image['path'] : 'uploads/' . $image['path'];
                            ?>
                            <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                 alt="<?= htmlspecialchars($image['title']) ?>" 
                                 loading="lazy" 
                                 onerror="this.src='https://picsum.photos/400/300?random=<?= $image['id'] ?>'">
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-images">
                    <h3>📸 Gallery Ready</h3>
                    <p>FramedSoul Photography Portfolio is live!<br>Upload your images through the <a href="admin.php" style="color: #007bff;">admin panel</a> to get started.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Side - Preview Area -->
        <div class="p-home-grid-mode__area">
            <div class="p-home-grid-mode__area-image">
                <img src="" alt="">
            </div>
            <div class="p-home-grid-mode__area-text">
                <span>2024</span>
                <div class="line"></div>
                <span>2025</span>
            </div>
        </div>

        <!-- Fullscreen Background Image -->
        <div class="p-home-grid-mode__full">
            <img src="" alt="">
        </div>
    </div>

    <!-- View Toggle Controls -->
    <div class="p-home-view">
        <div class="p-home-view__background"></div>
        <button class="p-home-view__button is-current" data-grid-view>
            <span>Grid</span>
        </button>
        <button class="p-home-view__button" data-list-view>
            <span>Slider</span>
        </button>
    </div>

    <!-- Scroll Indicator -->
    <div class="p-home-scroll">
        <span>Scroll</span>
    </div>
</div>
            
            <p class="p-home-scroll">↓　ScrollDown</p>
        </div>
    </main>
</div>

<script>
// FramedSoul Gallery - Original Site Animations & Interactions
document.addEventListener('DOMContentLoaded', function() {
    let isGridMode = true;
    
    // Initialize GSAP-like animations for grid items
    function animateGridItems() {
        const items = document.querySelectorAll('.p-home-grid-mode__item');
        items.forEach((item, index) => {
            // Set initial state
            item.style.opacity = '0';
            item.style.transform = 'translateY(50px)';
            
            // Animate in with stagger
            setTimeout(() => {
                item.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    // Handle image hover for preview area
    function handleImageHover() {
        const items = document.querySelectorAll('.p-home-grid-mode__item');
        const previewArea = document.querySelector('.p-home-grid-mode__area-image img');
        const fullBackground = document.querySelector('.p-home-grid-mode__full img');
        
        items.forEach(item => {
            const img = item.querySelector('img');
            
            item.addEventListener('mouseenter', function() {
                if (previewArea && img.src) {
                    previewArea.src = img.src;
                    previewArea.style.opacity = '1';
                    previewArea.style.transition = 'opacity 0.6s ease';
                }
                
                if (fullBackground && img.src) {
                    fullBackground.src = img.src;
                    fullBackground.style.opacity = '0.1';
                    fullBackground.style.transition = 'opacity 0.8s ease';
                }
                
                // Add hover overlay effect
                const imgElement = this.querySelector('.p-home-grid-mode__item-image');
                if (imgElement) {
                    imgElement.style.setProperty('--overlay-opacity', '0.7');
                    imgElement.style.setProperty('--plus-opacity', '1');
                }
            });
            
            item.addEventListener('mouseleave', function() {
                if (previewArea) {
                    previewArea.style.opacity = '0';
                }
                
                if (fullBackground) {
                    fullBackground.style.opacity = '0';
                }
                
                // Remove hover overlay effect
                const imgElement = this.querySelector('.p-home-grid-mode__item-image');
                if (imgElement) {
                    imgElement.style.setProperty('--overlay-opacity', '0');
                    imgElement.style.setProperty('--plus-opacity', '0');
                }
            });
        });
    }
    
    // Grid/Slider mode switching
    function setupViewToggle() {
        const gridBtn = document.querySelector('[data-grid-view]');
        const sliderBtn = document.querySelector('[data-list-view]');
        const gridContents = document.querySelector('.p-home-grid-mode__contents');
        const background = document.querySelector('.p-home-view__background');
        
        if (!gridBtn || !sliderBtn || !gridContents || !background) return;
        
        // Set initial background position
        background.style.left = '3px';
        background.style.transition = 'left 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        gridBtn.addEventListener('click', function() {
            if (isGridMode) return;
            
            isGridMode = true;
            gridBtn.classList.add('is-current');
            sliderBtn.classList.remove('is-current');
            
            // Move background
            background.style.left = '3px';
            
            // Reset to grid layout
            gridContents.style.display = 'grid';
            gridContents.style.gridTemplateColumns = 'repeat(4, 1fr)';
            gridContents.style.columnGap = '70px';
            gridContents.style.rowGap = '70px';
            gridContents.style.flexWrap = 'wrap';
            gridContents.style.overflowX = 'visible';
            
            // Reset item styles
            const items = gridContents.querySelectorAll('.p-home-grid-mode__item');
            items.forEach(item => {
                item.style.width = 'auto';
                item.style.minWidth = 'auto';
                item.style.flexShrink = '1';
            });
            
            animateGridItems();
        });
        
        sliderBtn.addEventListener('click', function() {
            if (!isGridMode) return;
            
            isGridMode = false;
            sliderBtn.classList.add('is-current');
            gridBtn.classList.remove('is-current');
            
            // Move background
            background.style.left = '73px';
            
            // Switch to row layout
            gridContents.style.display = 'flex';
            gridContents.style.flexWrap = 'nowrap';
            gridContents.style.overflowX = 'auto';
            gridContents.style.gap = '70px';
            
            // Set item styles for row
            const items = gridContents.querySelectorAll('.p-home-grid-mode__item');
            items.forEach(item => {
                item.style.width = '60vh';
                item.style.minWidth = '60vh';
                item.style.flexShrink = '0';
            });
        });
    }
    
    // Smooth scroll reveal
    function setupScrollAnimations() {
        const scrollText = document.querySelector('.p-home-scroll');
        
        // Show scroll indicator after items load
        setTimeout(() => {
            if (scrollText) {
                scrollText.style.opacity = '1';
                scrollText.style.transition = 'opacity 1s ease';
            }
        }, 2000);
        
        // Hide scroll on scroll
        let scrollTimer;
        window.addEventListener('scroll', function() {
            if (scrollText) {
                scrollText.style.opacity = '0';
            }
            
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                if (scrollText) {
                    scrollText.style.opacity = '1';
                }
            }, 1000);
        });
    }
    
    // Initialize all animations
    function init() {
        // Remove loading screen
        const loadingScreen = document.querySelector('.c-loading');
        if (loadingScreen) {
            setTimeout(() => {
                loadingScreen.style.opacity = '0';
                loadingScreen.style.transition = 'opacity 1s ease';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 1000);
            }, 1500);
        }
        
        // Wait for fonts and setup
        setTimeout(() => {
            animateGridItems();
            handleImageHover();
            setupViewToggle();
            setupScrollAnimations();
        }, 100);
    }
    
    // Start initialization
    init();
});

// Add CSS custom properties for hover effects
const style = document.createElement('style');
style.textContent = `
.p-home-grid-mode__item-image {
    --overlay-opacity: 0;
    --plus-opacity: 0;
}

.p-home-grid-mode__item-image::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #000;
    opacity: var(--overlay-opacity);
    transition: opacity 0.4s ease;
    pointer-events: none;
}

.p-home-grid-mode__item-image::before {
    content: "+";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 22px;
    color: white;
    opacity: var(--plus-opacity);
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: 1;
}
`;
document.head.appendChild(style);
</script>

<?php require_once '../templates/footer.php'; ?>
