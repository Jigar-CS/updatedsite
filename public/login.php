<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate user credentials
    if (authenticateUser($username, $password)) {
        $_SESSION['user'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}

require_once '../templates/header.php';
require_once '../templates/menu.php';
?>

<div data-barba="container" data-barba-namespace="login">
    <main class="main">
        <div class="p-login">
            <div class="p-login-content">
                <h1 class="p-login-title">Login</h1>
                <?php if (isset($error)): ?>
                    <div class="p-login-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form action="login.php" method="POST" class="p-login-form">
                    <div class="p-login-formItem">
                        <label for="username" class="p-login-label">Username</label>
                        <input type="text" name="username" id="username" class="p-login-input" required>
                    </div>
                    <div class="p-login-formItem">
                        <label for="password" class="p-login-label">Password</label>
                        <input type="password" name="password" id="password" class="p-login-input" required>
                    </div>
                    <button type="submit" class="p-login-submit">Login</button>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once '../templates/footer.php'; ?>