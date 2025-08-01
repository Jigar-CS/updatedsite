<?php
session_start();

// Simple authentication without database
$admin_username = 'admin';
$admin_password = 'admin123';

$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Handle login
if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = 'Invalid credentials';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle file upload
if (isset($_POST['upload']) && isset($_SESSION['admin_logged_in'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $newFilename = 'img' . (time()) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadsDir . '/' . $newFilename)) {
                $upload_success = 'Image uploaded successfully!';
            } else {
                $upload_error = 'Failed to upload image.';
            }
        } else {
            $upload_error = 'Invalid file type. Please upload JPG, PNG, GIF, or WebP files.';
        }
    }
}

// Handle file deletion
if (isset($_POST['delete']) && isset($_SESSION['admin_logged_in'])) {
    $fileToDelete = $_POST['delete_file'];
    if ($fileToDelete && file_exists($uploadsDir . '/' . $fileToDelete)) {
        if (unlink($uploadsDir . '/' . $fileToDelete)) {
            $delete_success = 'Image deleted successfully!';
        } else {
            $delete_error = 'Failed to delete image.';
        }
    }
}

// Get all images
$images = [];
if (is_dir($uploadsDir)) {
    $files = array_diff(scandir($uploadsDir), array('.', '..'));
    foreach ($files as $file) {
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
            $images[] = $file;
        }
    }
}

require_once '../templates/header.php';
?>

<style>
/* Creative Black & White Admin Panel Design */
* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    overflow: visible !important;
    height: auto !important;
}

body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 25%, #000000 50%, #0d0d0d 75%, #000000 100%);
    min-height: 100vh;
    height: auto;
    color: #ffffff;
    position: relative;
    overflow: visible !important;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.03) 0%, transparent 40%);
    pointer-events: none;
    z-index: 1;
}

.admin-container { 
    max-width: 1100px; 
    margin: 0 auto; 
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px; 
    padding: 35px; 
    box-shadow: 0 20px 60px rgba(0,0,0,0.8), 
                0 0 40px rgba(255,255,255,0.05);
    position: relative;
    z-index: 2;
    margin-top: 20px;
    margin-bottom: 20px;
}

.admin-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #ffffff, transparent);
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

.admin-header { 
    text-align: center; 
    margin-bottom: 40px; 
    padding-bottom: 30px; 
    border-bottom: 2px solid rgba(255,255,255,0.1);
    position: relative;
}

.admin-header::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    width: 100px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #ffffff, transparent);
    transform: translateX(-50%);
}

.admin-header h1 { 
    color: #ffffff; 
    margin: 0; 
    font-size: 2.5rem;
    font-weight: 300;
    letter-spacing: 2px;
    text-shadow: 0 0 20px rgba(255,255,255,0.3);
    margin-bottom: 10px;
}

.admin-header p {
    color: rgba(255,255,255,0.7);
    font-size: 1.1rem;
    letter-spacing: 1px;
}

.admin-nav { 
    text-align: center; 
    margin-bottom: 40px; 
}

.admin-nav a { 
    background: rgba(255,255,255,0.1);
    color: #ffffff; 
    padding: 15px 30px; 
    text-decoration: none; 
    border-radius: 50px; 
    margin: 0 15px; 
    border: 2px solid rgba(255,255,255,0.2);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    display: inline-block;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    font-weight: 500;
    letter-spacing: 1px;
}

.admin-nav a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s;
}

.admin-nav a:hover::before {
    left: 100%;
}

.admin-nav a:hover { 
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.4);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.login-form { 
    max-width: 400px; 
    margin: 50px auto; 
    padding: 40px; 
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(20px);
    border: 2px solid rgba(255,255,255,0.1);
    border-radius: 20px; 
    box-shadow: 0 20px 60px rgba(0,0,0,0.8);
    position: relative;
    overflow: hidden;
}

.login-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #ffffff, transparent);
    opacity: 0.5;
}

.form-group { 
    margin-bottom: 25px; 
    position: relative;
}

.form-group label { 
    display: block; 
    margin-bottom: 8px; 
    font-weight: 500;
    color: #ffffff;
    letter-spacing: 1px;
}

.form-group input { 
    width: 100%; 
    padding: 15px 20px; 
    border: 2px solid rgba(255,255,255,0.2); 
    border-radius: 10px; 
    font-size: 16px;
    background: rgba(255,255,255,0.05);
    color: #ffffff;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.form-group input:focus {
    outline: none;
    border-color: rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.1);
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
}

.form-group input::placeholder {
    color: rgba(255,255,255,0.5);
}

.btn { 
    background: linear-gradient(45deg, #ffffff, #f0f0f0);
    color: #000000; 
    padding: 15px 30px; 
    border: none; 
    border-radius: 50px; 
    cursor: pointer; 
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 1px;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0,0,0,0.1), transparent);
    transition: left 0.6s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover { 
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(255,255,255,0.3);
}

.btn-danger { 
    background: linear-gradient(45deg, #ff4757, #ff3742);
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(255,71,87,0.3);
}

.btn-danger:hover { 
    box-shadow: 0 15px 40px rgba(255,71,87,0.5);
}

.alert { 
    padding: 20px; 
    margin: 25px 0; 
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    font-weight: 500;
    letter-spacing: 0.5px;
}

.alert-success { 
    background: rgba(46,204,113,0.2); 
    color: #ffffff; 
    border-color: rgba(46,204,113,0.5);
    box-shadow: 0 10px 30px rgba(46,204,113,0.1);
}

.alert-error { 
    background: rgba(231,76,60,0.2); 
    color: #ffffff; 
    border-color: rgba(231,76,60,0.5);
    box-shadow: 0 10px 30px rgba(231,76,60,0.1);
}

.upload-section { 
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    padding: 40px; 
    border-radius: 20px; 
    margin-bottom: 40px;
    border: 1px solid rgba(255,255,255,0.1);
    position: relative;
}

.upload-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
}

.images-grid { 
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
    gap: 25px; 
    margin-top: 40px; 
}

.image-item { 
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 15px; 
    padding: 20px; 
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    overflow: hidden;
}

.image-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.8s;
}

.image-item:hover::before {
    left: 100%;
}

.image-item:hover {
    transform: translateY(-10px);
    border-color: rgba(255,255,255,0.3);
    box-shadow: 0 25px 60px rgba(0,0,0,0.8);
}

.image-item img { 
    width: 100%; 
    height: 180px; 
    object-fit: cover; 
    border-radius: 10px;
    filter: grayscale(100%) contrast(1.2);
    transition: all 0.4s ease;
}

.image-item:hover img {
    filter: grayscale(80%) contrast(1.4) brightness(1.1);
}

.image-item h4 { 
    margin: 15px 0 10px 0;
    color: #ffffff;
    font-weight: 500;
    letter-spacing: 1px;
}

.image-item .btn { 
    width: 100%; 
    margin-top: 15px; 
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.3);
}

::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.5);
}

/* Responsive design */
@media (max-width: 768px) {
    .admin-container {
        margin: 10px;
        padding: 20px;
    }
    
    .admin-header h1 {
        font-size: 2rem;
    }
    
    .admin-nav a {
        display: block;
        margin: 10px 0;
    }
    
    .login-form {
        margin: 20px auto;
        padding: 25px;
    }
    
    .images-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
}
</style>

<div class="admin-container">
    <div class="admin-header">
        <h1>🎨 FramedSoul Admin Panel</h1>
        <p>Photography Portfolio Management</p>
    </div>

    <div class="admin-nav">
        <a href="index.php">🏠 View Gallery</a>
        <?php if (isset($_SESSION['admin_logged_in'])): ?>
            <a href="?logout=1">🚪 Logout</a>
        <?php endif; ?>
    </div>

    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
        <!-- Login Form -->
        <div class="login-form">
            <h2 style="text-align: center; margin-bottom: 30px;">🔐 Admin Login</h2>
            
            <?php if (isset($login_error)): ?>
                <div class="alert alert-error"><?= $login_error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn" style="width: 100%;">Login</button>
            </form>
        </div>
    <?php else: ?>
        <!-- Admin Dashboard -->
        
        <?php if (isset($upload_success)): ?>
            <div class="alert alert-success"><?= $upload_success ?></div>
        <?php endif; ?>
        
        <?php if (isset($upload_error)): ?>
            <div class="alert alert-error"><?= $upload_error ?></div>
        <?php endif; ?>
        
        <?php if (isset($delete_success)): ?>
            <div class="alert alert-success"><?= $delete_success ?></div>
        <?php endif; ?>
        
        <?php if (isset($delete_error)): ?>
            <div class="alert alert-error"><?= $delete_error ?></div>
        <?php endif; ?>

        <!-- Upload Section -->
        <div class="upload-section">
            <h2>📤 Upload New Image</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Select Image (JPG, PNG, GIF, WebP):</label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" required>
                </div>
                <button type="submit" name="upload" class="btn">Upload Image</button>
            </form>
        </div>

        <!-- Images Management -->
        <h2>🖼️ Manage Images (<?= count($images) ?> total)</h2>
        
        <?php if (empty($images)): ?>
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px;">
                <h3>📸 No Images Found</h3>
                <p>Upload some images to get started with your gallery!</p>
            </div>
        <?php else: ?>
            <div class="images-grid">
                <?php foreach ($images as $image): ?>
                    <div class="image-item">
                        <img src="uploads/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($image) ?>">
                        <h4><?= htmlspecialchars($image) ?></h4>
                        <p><small>Size: <?= number_format(filesize($uploadsDir . '/' . $image) / 1024, 1) ?> KB</small></p>
                        <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this image?')">
                            <input type="hidden" name="delete_file" value="<?= htmlspecialchars($image) ?>">
                            <button type="submit" name="delete" class="btn btn-danger">🗑️ Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div style="background: #e9ecef; padding: 20px; border-radius: 10px; margin-top: 30px; text-align: center;">
            <h3>📊 Gallery Statistics</h3>
            <p><strong><?= count($images) ?></strong> images in gallery</p>
            <p><strong><?= number_format(array_sum(array_map(function($img) use ($uploadsDir) { return filesize($uploadsDir . '/' . $img); }, $images)) / 1024 / 1024, 2) ?> MB</strong> total storage</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../templates/footer.php'; ?>