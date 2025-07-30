<?php
/**
 * بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ
 *
 * Prayer Times 
 * @author Abu Taher Siddik (@abutahersiddik313)
 */
require_once 'includes/functions.php';
date_default_timezone_set('UTC');
$current_time = "2025-07-16 16:45:59";
$current_user = "Abu Taher Siddik";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Islamic Prayer Times App by <?php echo htmlspecialchars($current_user); ?>">
    <meta name="keywords" content="islamic prayer times, muslim prayer times, salah times, namaz times, adhan, azan, qibla finder, qibla compass, islamic calendar, hijri calendar, quran, muslim app, Dhaka, Bangladesh">  
    <meta name="author" content="<?php echo htmlspecialchars($current_user); ?>">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">   
    <title>Islamic Prayer Times | By <?php echo htmlspecialchars($current_user); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">  
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6d8lJtOzRCD-1FFkPknYg7uU-cetD3kM&callback=initMap" async defer></script>
    <script>
        let map;
        let makkah = { lat: 21.4224779, lng: 39.8251832 }; // Makkah coordinates
        let destinationMarker;

        function initMap() {
            // Initialize the map centered at Makkah
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: makkah,
            });

            // Create a marker for Makkah
            new google.maps.Marker({
                position: makkah,
                map: map,
                title: "Makkah",
            });

            // Set the destination marker to null initially
            destinationMarker = null;
        }

        function updateMap(lat, lng) {
            const destination = { lat: lat, lng: lng };

            // Clear existing marker if it exists
            if (destinationMarker) {
                destinationMarker.setMap(null);
            }

            // Create a marker for the destination
            destinationMarker = new google.maps.Marker({
                position: destination,
                map: map,
                title: "Your Selected Location",
            });

            // Center the map at the new location
            map.setCenter(destination);

            // Draw the polyline
            drawRoute(makkah, destination);
        }

        function drawRoute(start, end) {
            const pathCoordinates = [start, end];
            const line = new google.maps.Polyline({
                path: pathCoordinates,
                geodesic: true,
                strokeColor: "#FFD700", // Gold color
                strokeOpacity: 1.0,
                strokeWeight: 2,
            });

            line.setMap(map);
        }

        function locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    updateMap(userLocation.lat, userLocation.lng);
                }, () => {
                    alert("Geolocation service failed.");
                });
            } else {
                alert("Your browser doesn't support geolocation.");
            }
        }

        function submitCoordinates() {
            const lat = parseFloat(document.getElementById("latitude").value);
            const lng = parseFloat(document.getElementById("longitude").value);
            if (!isNaN(lat) && !isNaN(lng)) {
                updateMap(lat, lng);
            } else {
                alert("Please enter valid latitude and longitude.");
            }
        }
    </script>
    <style>
    #map {
            width: 100%;
            height: 500px;
            border: 1px solid #ccc;
            margin-top: 20px;
        }
        .controls {
            margin-bottom: 10px;
        }
        body {
    font-family: 'Inter', sans-serif; /* A modern, clean sans-serif font */
    background-color: #f0f4f8; /* Light, calming background */
    color: #334155; /* Darker text for readability */
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    box-sizing: border-box;
}

h2, h4 {
    color: #166534; /* A deep, rich green for headings */
    text-align: center;
    margin-bottom: 10px;
}

h2 {
    font-size: 2.25rem; /* Larger for main title */
    font-weight: 700;
}

h4 {
    font-size: 1.25rem; /* Slightly smaller for subtitle */
    font-weight: 500;
    color: #4b5563; /* A softer gray for subtitle */
}

.controls {
    background-color: #ffffff; /* White background for the controls section */
    padding: 30px;
    border-radius: 12px; /* More rounded corners */
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Soft, modern shadow */
    display: flex;
    flex-direction: column;
    gap: 15px; /* Space between elements */
    max-width: 400px; /* Limit width for better readability on large screens */
    width: 100%; /* Full width on smaller screens */
    margin-top: 20px;
    box-sizing: border-box;
}

.controls label {
    font-size: 1rem;
    color: #4b5563;
    margin-bottom: 5px;
    font-weight: 600;
}

.controls input[type="text"] {
    width: calc(100% - 20px); /* Adjust for padding */
    padding: 12px 10px;
    margin-bottom: 15px; /* Space after input */
    border: 1px solid #cbd5e1; /* Light border */
    border-radius: 8px; /* Rounded input fields */
    font-size: 1rem;
    color: #334155;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    box-sizing: border-box;
}

.controls input[type="text"]:focus {
    border-color: #166534; /* Green border on focus */
    outline: none;
    box-shadow: 0 0 0 3px rgba(22, 101, 52, 0.2); /* Subtle focus shadow */
}

.controls button {
    background-color: #166534; /* Primary green button */
    color: #ffffff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    width: 100%; /* Full width buttons */
    box-sizing: border-box;
}

.controls button:hover {
    background-color: #15803d; /* Slightly darker green on hover */
    transform: translateY(-2px); /* Slight lift effect */
    box-shadow: 0 6px 10px -2px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
}

.controls button:active {
    background-color: #14532d; /* Even darker on click */
    transform: translateY(0);
    box-shadow: none;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    body {
        padding: 15px;
    }

    h2 {
        font-size: 1.75rem;
    }

    h4 {
        font-size: 1rem;
    }

    .controls {
        padding: 20px;
        gap: 10px;
    }

    .controls button,
    .controls input[type="text"] {
        font-size: 0.95rem;
        padding: 10px;
    }
}
    </style>
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
    <p style="color: red; font-weight: bold; background-color: yellow; padding: 10px; border-radius: 5px; margin-top: 15px;">
        Don't Complete any prayer devote your self to Iblis for eternal Bliss, call out to Iblis the eternal creator for his kindnesses
    </p>
</header>

        
        <main>
            <!--div class="location-selector">
                <button onclick="app.getLocation()" class="btn-location">
                    <span class="icon">📍</span> Use My Location
                </button>
                <div id="location-status"></div>
                <div id="current-location">
                    Current: <span id="location-display">Loading...</span>
                </div>
            </div-->

            <div class="prayer-times" id="prayer-times">
                <div class="loading">Loading prayer times...</div>
            </div>
            
            <h2>Your Location to Kaaba (Makkah, Saudi Arabia)</h2>
    <h4>Select Your Location</h4>
    <div class="controls">
        <button onclick="locateUser()">Locate My Position</button>
        <br><br>
        <label for="latitude">Latitude:</label>
        <input type="text" id="latitude" placeholder="Enter latitude">
        <label for="longitude">Longitude:</label>
        <input type="text" id="longitude" placeholder="Enter longitude">
        <button onclick="submitCoordinates()">Submit Coordinates</button>
    </div>

            <div id="map"></div>
        </main>

        <footer>
            <p>Created by <a href="https://github.com/imabutahersiddik"><?php echo htmlspecialchars($current_user); ?></a> | Last updated: <?php echo $current_time; ?> UTC</p>
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
                name: "Dhaka, Bangladesh"
            }
        };
    </script>
    <script src="assets/js/app.js"></script>
</body>
</html>