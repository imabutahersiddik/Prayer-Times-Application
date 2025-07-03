class PrayerTimesApp {
    constructor() {
        this.KAABA_COORDINATES = {
            latitude: 21.4225,
            longitude: 39.8262
        };

        this.DEFAULT_COORDINATES = window.APP_CONFIG.defaultLocation;
        this.currentUser = window.APP_CONFIG.currentUser;
        this.lastUpdate = window.APP_CONFIG.currentTime;

        this.prayerInfo = {
            Fajr: {
                description: "The dawn prayer performed before sunrise. It marks the beginning of the day for Muslims.",
                rakats: "2 rakats fard",
                time_description: "Starts at dawn, ends just before sunrise"
            },
            Dhuhr: {
                description: "The noon prayer performed after the sun passes its zenith (highest point).",
                rakats: "4 rakats fard",
                time_description: "Starts after sun's zenith, ends when Asr time begins"
            },
            Asr: {
                description: "The afternoon prayer performed when the sun is about midway between noon and sunset.",
                rakats: "4 rakats fard",
                time_description: "Starts when shadow equals object's height, ends before sunset"
            },
            Maghrib: {
                description: "The sunset prayer performed immediately after sunset.",
                rakats: "3 rakats fard",
                time_description: "Starts immediately after sunset, ends when twilight disappears"
            },
            Isha: {
                description: "The night prayer performed after the darkness of night falls.",
                rakats: "4 rakats fard",
                time_description: "Starts after twilight disappears, ends before dawn"
            }
        };

        this.initializeApp();
    }

    async initializeApp() {
        try {
            console.log(`[${this.lastUpdate}] Initializing app for ${this.currentUser}...`);
            await this.loadSavedLocation();
            this.setupEventListeners();
            this.startTimeUpdates();
            this.initializeModal();
        } catch (error) {
            console.error('Error initializing app:', error);
            this.handleError(error);
        }
    }

    setupEventListeners() {
        window.addEventListener('load', () => {
            this.updateDisplays();
        });
    }

    startTimeUpdates() {
        this.updateTime();
        setInterval(() => this.updateTime(), 1000);
    }

    updateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = 
            now.toLocaleTimeString('en-US', { 
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        
        document.getElementById('gregorian-date').textContent = 
            now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
    }

    async loadSavedLocation() {
        console.log('Loading saved location...');
        const savedLat = localStorage.getItem('userLat');
        const savedLng = localStorage.getItem('userLng');
        
        if (savedLat && savedLng) {
            console.log('Found saved coordinates:', savedLat, savedLng);
            await this.updatePrayerTimes({
                latitude: parseFloat(savedLat),
                longitude: parseFloat(savedLng)
            });
        } else {
            console.log('No saved location, using default coordinates');
            await this.updatePrayerTimes(this.DEFAULT_COORDINATES);
            this.getLocation();
        }
    }

    async updatePrayerTimes(coordinates) {
        try {
            const times = await this.fetchPrayerTimes(coordinates);
            
            if (!times || !times.data || !times.data.timings) {
                throw new Error('Invalid prayer times data received');
            }

            this.renderPrayerTimes(times.data);
            this.updateQiblaDirection(coordinates);
            this.updateLocationDisplay(coordinates);
        } catch (error) {
            console.error('Error updating prayer times:', error);
            this.handleError(error);
        }
    }

    async fetchPrayerTimes(coordinates) {
        const response = await fetch(`api/prayer-times.php?latitude=${coordinates.latitude}&longitude=${coordinates.longitude}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.status === 'error') {
            throw new Error(data.message || 'Failed to fetch prayer times');
        }
        
        return data;
    }

    renderPrayerTimes(data) {
        const container = document.getElementById('prayer-times');
        container.innerHTML = '';

        const prayers = {
            Fajr: '🌅',
            Sunrise: '☀️',
            Dhuhr: '🌞',
            Asr: '🌇',
            Maghrib: '🌆',
            Isha: '🌙'
        };

        Object.entries(prayers).forEach(([prayer, icon]) => {
            if (data.timings && data.timings[prayer]) {
                container.appendChild(
                    this.createPrayerCard(prayer, data.timings[prayer], icon)
                );
            }
        });
    }

    createPrayerCard(prayer, time, icon) {
        const card = document.createElement('div');
        card.className = 'prayer-card';
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', `${prayer} prayer at ${time}`);
        
        // Add click handler with propagation stopped
        card.addEventListener('click', (e) => {
            e.stopPropagation();
            this.showPrayerInfo(prayer, time);
        });
        
        // Add keyboard handler
        card.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                e.stopPropagation();
                this.showPrayerInfo(prayer, time);
            }
        });
        
        card.innerHTML = `
            <div class="prayer-icon">${icon}</div>
            <h3>${prayer}</h3>
            <div class="time">${time}</div>
        `;
        
        return card;
    }

    initializeModal() {
        const modal = document.getElementById('prayerModal');
        const closeBtn = document.getElementsByClassName('close')[0];
        const modalContent = modal.querySelector('.modal-content');

        // Prevent clicks inside modal from closing it
        modalContent.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Close button handler
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            modal.style.display = "none";
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display === "block") {
                modal.style.display = "none";
            }
        });
    }

    showPrayerInfo(prayer, time) {
        const info = this.prayerInfo[prayer];
        if (!info) return;

        const modal = document.getElementById('prayerModal');
        const prayerName = document.getElementById('modal-prayer-name');
        const prayerTime = document.getElementById('modal-prayer-time');
        const prayerDescription = document.getElementById('modal-prayer-description');
        const prayerRakats = document.getElementById('modal-prayer-rakats');

        // Update modal content
        prayerName.textContent = prayer;
        prayerTime.textContent = `Time: ${time} (${info.time_description})`;
        prayerDescription.textContent = info.description;
        prayerRakats.textContent = info.rakats;

        // Show modal with animation
        modal.style.display = "block";
        
        // Force a reflow to ensure animation works
        const modalContent = modal.querySelector('.modal-content');
        modalContent.offsetHeight; // Force reflow
        modalContent.classList.add('modal-animate');
        
        // Remove animation class after transition
        setTimeout(() => {
            modalContent.classList.remove('modal-animate');
        }, 30000);
    }

    getLocation() {
        const status = document.getElementById('location-status');
        
        if (!navigator.geolocation) {
            status.textContent = 'Geolocation is not supported by your browser';
            return;
        }

        status.textContent = 'Locating...';

        navigator.geolocation.getCurrentPosition(
            async position => {
                const coordinates = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };

                localStorage.setItem('userLat', coordinates.latitude);
                localStorage.setItem('userLng', coordinates.longitude);

                await this.updatePrayerTimes(coordinates);
                
                status.textContent = 'Location updated successfully!';
                setTimeout(() => status.textContent = '', 3000);
            },
            error => {
                console.error('Geolocation error:', error);
                status.textContent = 'Unable to retrieve your location';
                this.handleLocationError(error);
            }
        );
    }

    async updateLocationDisplay(coordinates) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${coordinates.latitude}&lon=${coordinates.longitude}&format=json`
            );
            const data = await response.json();
            const locationDisplay = document.getElementById('location-display');
            locationDisplay.textContent = `${data.address.city || data.address.town || data.address.village}, ${data.address.country}`;
        } catch (error) {
            console.error('Error getting location name:', error);
            const locationDisplay = document.getElementById('location-display');
            locationDisplay.textContent = `${coordinates.latitude.toFixed(4)}, ${coordinates.longitude.toFixed(4)}`;
        }
    }

    updateQiblaDirection(coordinates) {
        const angle = this.calculateQiblaDirection(coordinates);
        this.drawQiblaCompass(angle);
        
        const qiblaAngle = document.getElementById('qibla-angle');
        qiblaAngle.textContent = `Qibla is ${Math.round(angle)}° from North`;
    }

    calculateQiblaDirection(coordinates) {
        const φ1 = this.toRadians(coordinates.latitude);
        const φ2 = this.toRadians(this.KAABA_COORDINATES.latitude);
        const Δλ = this.toRadians(this.KAABA_COORDINATES.longitude - coordinates.longitude);

        const y = Math.sin(Δλ);
        const x = Math.cos(φ1) * Math.tan(φ2) - Math.sin(φ1) * Math.cos(Δλ);
        
        return (this.toDegrees(Math.atan2(y, x)) + 360) % 360;
    }

    drawQiblaCompass(angle) {
        const canvas = document.getElementById('qibla-compass');
        const ctx = canvas.getContext('2d');
        
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 10;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Draw compass circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#2c3e50';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Draw Qibla direction
        ctx.save();
        ctx.translate(centerX, centerY);
        ctx.rotate(this.toRadians(angle));
        
        ctx.beginPath();
        ctx.moveTo(0, -radius + 5);
        ctx.lineTo(10, 0);
        ctx.lineTo(-10, 0);
        ctx.closePath();
        ctx.fillStyle = '#27ae60';
        ctx.fill();
        
        ctx.restore();
    }

    toRadians(degrees) {
        return degrees * (Math.PI / 180);
    }

    toDegrees(radians) {
        return radians * (180 / Math.PI);
    }

    handleError(error) {
        console.error('Application error:', error);
        const status = document.getElementById('location-status');
        if (status) {
            status.textContent = 'Error: ' + error.message;
            status.style.color = 'var(--error-color)';
        }
    }

    handleLocationError(error) {
        console.log('Using default location due to error:', error);
        this.updatePrayerTimes(this.DEFAULT_COORDINATES);
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new PrayerTimesApp();
});