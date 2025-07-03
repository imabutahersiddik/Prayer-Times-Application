// Add keyboard navigation for prayer cards
document.querySelectorAll('.prayer-card').forEach(card => {
    card.setAttribute('tabindex', '0');
    card.setAttribute('role', 'button');
    card.setAttribute('aria-label', `Show ${card.querySelector('h3').textContent} prayer information`);
    
    card.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            card.click();
        }
    });
});

// Make modal accessible
const modal = document.getElementById('prayerModal');
modal.setAttribute('role', 'dialog');
modal.setAttribute('aria-modal', 'true');
modal.setAttribute('aria-labelledby', 'modal-prayer-name');