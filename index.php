<?php
require_once 'includes/functions.php';
date_default_timezone_set('UTC');
$current_time = "2025-07-03 17:03:38";
$current_user = "imabutahersiddik";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Islamic Prayer Times App by <?php echo htmlspecialchars($current_user); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($current_user); ?>">
    <title>Islamic Prayer Times | By <?php echo htmlspecialchars($current_user); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Prayer Times</h1>
            <div class="current-time">
                <div id="hijri-date"></div>
                <div id="gregorian-date"></div>
                <div id="current-time"></div>
            </div>
        </header>
        
        <main>
            <div class="location-selector">
                <button onclick="app.getLocation()" class="btn-location">
                    <span class="icon">📍</span> Use My Location
                </button>
                <div id="location-status"></div>
                <div id="current-location">
                    Current: <span id="location-display">Loading...</span>
                </div>
            </div>

            <div class="prayer-times" id="prayer-times">
                <div class="loading">Loading prayer times...</div>
            </div>

            <div class="qibla-direction">
                <h3>Qibla Direction</h3>
                <canvas id="qibla-compass" width="200" height="200"></canvas>
                <div id="qibla-angle"></div>
            </div>
        </main>

        <footer>
            <p>Created by <?php echo htmlspecialchars($current_user); ?> | Last updated: <?php echo $current_time; ?> UTC</p>
        </footer>
    </div>

    <!-- Prayer Information Modal -->
    <div id="prayerModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-prayer-name" data-last-updated="<?php echo $current_time; ?>">
        <div class="modal-content" data-user="<?php echo htmlspecialchars($current_user); ?>">
            <span class="close" aria-label="Close" title="Close">&times;</span>
            <h2 id="modal-prayer-name"></h2>
            <div class="prayer-info">
                <div id="modal-prayer-time"></div>
                <div id="modal-prayer-description"></div>
                <div class="prayer-requirements">
                    <h3>Required Rakats</h3>
                    <div id="modal-prayer-rakats"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug information -->
    <script>
        window.APP_CONFIG = {
            currentTime: "<?php echo $current_time; ?>",
            currentUser: "<?php echo htmlspecialchars($current_user); ?>",
            defaultLocation: {
                latitude: 24.8949,
                longitude: 91.3674,
                name: "Chhatak, Bangladesh"
            }
        };
    </script>
    <script src="assets/js/app.js"></script>
</body>
</html>