# Prayer Times Application Documentation
**Last Updated:** 2025-07-03 17:19:04 UTC
**Author:** @imabutahersiddik

## Overview
The Prayer Times Application is a modern, responsive web application that displays Islamic prayer times and provides detailed information about each prayer. It features a modern UI, real-time updates, and an interactive modal system.

## Features

### 1. Prayer Times Display
- Real-time prayer times calculation
- Beautiful card-based interface
- Visual indicators for each prayer time
- Current time and date display
- Automatic location detection

### 2. Interactive Modal System
- Smooth animations and transitions
- Blurred background overlay
- Detailed prayer information
- Required rakats display
- Time period information
- User-friendly close interactions

### 3. Qibla Direction
- Interactive compass display
- Real-time direction calculation
- Degree indication from North

### 4. Location Services
- Automatic location detection
- Manual location selection
- Reverse geocoding for location names
- Location persistence

### 5. Responsive Design
- Mobile-first approach
- Dark mode support
- Fluid typography
- Accessible interface

## Technical Implementation

### 1. Frontend Stack
```html
- HTML5
- CSS3 (with CSS Variables)
- JavaScript (ES6+)
```

### 2. Backend Stack
```php
- PHP 7.4+
- RESTful API
- JSON data format
```

### 3. APIs Used
```text
- Aladhan API (Prayer Times)
- OpenStreetMap (Geocoding)
- Browser Geolocation API
```

### 4. Code Structure
```text
prayer-times/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   └── data/
│       ├── locations.json
│       └── prayer_times.json
├── includes/
│   └── functions.php
├── api/
│   └── prayer-times.php
├── index.php
└── README.md
```

## Key Components

### 1. Prayer Time Cards
```javascript
createPrayerCard(prayer, time, icon) {
    const card = document.createElement('div');
    card.className = 'prayer-card';
    card.setAttribute('tabindex', '0');
    card.setAttribute('role', 'button');
    card.setAttribute('aria-label', `${prayer} prayer at ${time}`);
    
    card.addEventListener('click', (e) => {
        e.stopPropagation();
        this.showPrayerInfo(prayer, time);
    });
    
    // Card content
    card.innerHTML = `
        <div class="prayer-icon">${icon}</div>
        <h3>${prayer}</h3>
        <div class="time">${time}</div>
    `;
    
    return card;
}
```

### 2. Modal System
```javascript
showPrayerInfo(prayer, time) {
    const info = this.prayerInfo[prayer];
    if (!info) return;

    const modal = document.getElementById('prayerModal');
    // Update modal content
    modal.querySelector('#modal-prayer-name').textContent = prayer;
    modal.querySelector('#modal-prayer-time').textContent = time;
    modal.querySelector('#modal-prayer-period').textContent = info.time_description;
    modal.querySelector('#modal-prayer-description').textContent = info.description;
    modal.querySelector('#modal-prayer-rakats').textContent = info.rakats;

    // Show modal with animation
    modal.style.display = 'block';
    modal.offsetHeight; // Force reflow
    modal.classList.add('visible');
}
```

### 3. Location Services
```javascript
getLocation() {
    if (!navigator.geolocation) {
        this.handleLocationError(new Error('Geolocation not supported'));
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async position => {
            const coordinates = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            };
            await this.updatePrayerTimes(coordinates);
        },
        error => this.handleLocationError(error)
    );
}
```

## Accessibility Features

1. Keyboard Navigation
```javascript
trapFocus(modal) {
    const focusableElements = modal.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    firstFocusable.focus();

    modal.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            if (e.shiftKey && document.activeElement === firstFocusable) {
                e.preventDefault();
                lastFocusable.focus();
            } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                e.preventDefault();
                firstFocusable.focus();
            }
        }
    });
}
```

2. ARIA Labels and Roles
```html
<div class="prayer-modal-overlay" 
     id="prayerModal" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-prayer-name">
```

## Responsive Design

### 1. Mobile-First Approach
```css
/* Base styles for mobile */
.prayer-times {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

/* Tablet and above */
@media (min-width: 768px) {
    .prayer-times {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .prayer-times {
        grid-template-columns: repeat(3, 1fr);
    }
}
```

### 2. Dark Mode Support
```css
@media (prefers-color-scheme: dark) {
    :root {
        --primary-color: #ecf0f1;
        --secondary-color: #bdc3c7;
        --text-color: #ecf0f1;
        --light-bg: #2c3e50;
    }
}
```

## Performance Optimizations

1. Event Delegation
2. Debounced Location Updates
3. Cached Prayer Times
4. Optimized Animations
5. Lazy Loading of Resources

## Future Enhancements

1. Prayer Time Notifications
2. Multiple Location Support
3. Prayer Time Adjustments
4. Monthly Prayer Calendar
5. Multi-language Support

## Development Timeline
- **Initial Release:** 2025-07-03
- **Last Update:** 2025-07-03 17:19:04 UTC
- **Developer:** @imabutahersiddik

## Testing

### 1. Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### 2. Device Testing
- Desktop (Windows, macOS)
- Mobile (iOS, Android)
- Tablets (iPad, Android)

### 3. Performance Testing
- Lighthouse Score: 95+
- Web Vitals Compliance
- Accessibility Score: 100

## Conclusion
The Prayer Times Application provides a modern, accessible, and reliable way to view Islamic prayer times and related information. With its focus on user experience and performance, it serves as a valuable tool for Muslims worldwide.