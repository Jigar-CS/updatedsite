<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/ImageRepository.php';
require_once '../templates/header.php';
require_once '../templates/menu.php';
?>

<div data-barba="container" data-barba-namespace="contact">
    <main class="main">
        <div class="p-contact">
            <div class="p-contact-content">
                <h1 class="p-contact-title">Contact</h1>
                <div class="p-contact-form">
                    <form action="mailto:jigarsakhia2020@gmail.com" method="post" enctype="text/plain">
                        <div class="p-contact-formItem">
                            <label for="name" class="p-contact-label">Name</label>
                            <input type="text" id="name" name="name" class="p-contact-input" required>
                        </div>
                        <div class="p-contact-formItem">
                            <label for="email" class="p-contact-label">Email</label>
                            <input type="email" id="email" name="email" class="p-contact-input" required>
                        </div>
                        <div class="p-contact-formItem">
                            <label for="message" class="p-contact-label">Message</label>
                            <textarea id="message" name="message" class="p-contact-textarea" required></textarea>
                        </div>
                        <button type="submit" class="p-contact-submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../templates/footer.php'; ?>