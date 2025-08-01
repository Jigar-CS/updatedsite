<button class="l-menu-button"> 
    <span class="l-menu-button__line"></span> 
    <span class="l-menu-button__line"></span> 
</button>
<div class="l-menu">
    <div class="l-menu__inner">
        <div class="l-menu__box">
            <div class="l-menu__item">
                <p class="title">Navigation</p>
                <div class="links"> 
                    <a href="index.php" data-current="home">Home,</a> 
                    <a href="bio.php" data-current="bio">Bio,</a> 
                    <a href="contact.php">Contact</a> 
                </div>
            </div>
            <?php if (isset($_SESSION['user'])): ?>
            <div class="l-menu__item">
                <p class="title">Admin</p>
                <div class="links">
                    <a href="admin.php">Admin Panel,</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <div class="l-menu__item">
                <p class="title">Account</p>
                <div class="links">
                    <a href="login.php">Login</a>
                </div>
            </div>
            <?php endif; ?>
            <div class="l-menu__item">
                <p class="title">SNS</p>
                <div class="links"> 
                    <a href="https://www.instagram.com/sakhiajigar" target="_blank">Instagram</a>
                </div>
            </div>
            <div class="l-menu__item">
                <p class="title">Thought</p>
                <p class="text">Even after time passes, a single photo can faintly bring back the moment. Photography is wonderful.</p>
            </div>
        </div>
    </div>
    <div class="l-menu__image"> <img src="img3.webp" alt=""> </div>
</div>
