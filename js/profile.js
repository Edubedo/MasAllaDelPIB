document.addEventListener('DOMContentLoaded', () => {
    const settingsIcon = document.getElementById('settings-icon');
    const popup = document.getElementById('userPopup');

    if (settingsIcon && popup) {
        settingsIcon.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            // Alternar la clase 'visible'
            popup.classList.toggle('visible');
        });

        document.addEventListener('click', (e) => {
            if (!popup.contains(e.target) && e.target !== settingsIcon && !settingsIcon.contains(e.target)) {
                popup.classList.remove('visible');
            }
        });
    }
});