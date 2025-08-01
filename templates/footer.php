    <footer class="l-footer"> 
        <small class="l-footer__copy">&copy; 2025 FramedSoul</small> 
    </footer>
    <div id="webgl">
        <div class="transition-text-cover" aria-hidden="true">
            <p class="transition-text">Loading</p> 
            <img src="mainlogo.jpg" alt="">
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Menu toggle functionality
            var menuButton = document.querySelector('.l-menu-button');
            var menu = document.querySelector('.l-menu');
            if (menuButton && menu) {
                menuButton.addEventListener('click', function () {
                    menu.classList.toggle('is-open');
                    menuButton.classList.toggle('is-open');
                });
            }
            
            // Hide loading screen with animation
            setTimeout(function() {
                const loadingScreen = document.querySelector('.c-loading');
                if (loadingScreen) {
                    loadingScreen.style.opacity = '0';
                    loadingScreen.style.transition = 'opacity 0.5s ease';
                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                        document.body.style.overflow = '';
                    }, 500);
                }
            }, 1500);
            
            // View toggle functionality (Grid/Slider)
            var gridButton = document.querySelector('[data-grid-view]');
            var listButton = document.querySelector('[data-list-view]');
            
            if (gridButton) {
                gridButton.addEventListener('click', function() {
                    gridButton.classList.add('is-current');
                    if (listButton) listButton.classList.remove('is-current');
                });
            }
            
            if (listButton) {
                listButton.addEventListener('click', function() {
                    listButton.classList.add('is-current');
                    if (gridButton) gridButton.classList.remove('is-current');
                });
            }
        });
    </script>
</body>
</html>
