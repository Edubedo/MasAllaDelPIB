document.addEventListener('DOMContentLoaded', function() {
    console.log('Language script loaded');
    const languageIcon = document.getElementById('language-icon');
    
    if (languageIcon) {
        console.log('Language icon found');
        languageIcon.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Language icon clicked');
            const currentLang = this.getAttribute('data-lang');
            console.log('Current language:', currentLang);
            const newLang = currentLang === 'es' ? 'en' : 'es';
            console.log('New language:', newLang);
            
            // Update the flag image
            const flagImg = this.querySelector('.flag-icon');
            if (flagImg) {
                flagImg.src = newLang === 'es' 
                    ? '/views/uploads/Bandera_de_España.svg.png'
                    : '/views/uploads/Flag_of_the_United_States.svg.png';
                flagImg.alt = newLang === 'es' ? 'Español' : 'English';
                console.log('Flag image updated');
            }
            
            // Update the data attribute
            this.setAttribute('data-lang', newLang);
            console.log('Data attribute updated');

            // Translate the page
            translatePage(newLang);
        });
    } else {
        console.log('Language icon not found');
    }
});

function translatePage(targetLang) {
    // Elementos que queremos traducir
    const elementsToTranslate = [
        // Elementos de navegación
        ...document.querySelectorAll('.nav__items .hover-text'),
        ...document.querySelectorAll('.nav__logo-name h2'),
        
        // Elementos de contenido principal
        ...document.querySelectorAll('.encabezado h1'),
        ...document.querySelectorAll('h1'),
        ...document.querySelectorAll('h2'),
        ...document.querySelectorAll('h3'),
        ...document.querySelectorAll('h4'),
        ...document.querySelectorAll('.titulo-com'),
        ...document.querySelectorAll('.textarea'),
        ...document.querySelectorAll('label'),
        ...document.querySelectorAll('input'),
        ...document.querySelectorAll('p'),
        
        ...document.querySelectorAll('input[type="text"]'),
        ...document.querySelectorAll('input[type="email"]'),
        ...document.querySelectorAll('input[type="password"]'),
        ...document.querySelectorAll('#userPopup button'),
        ...document.querySelectorAll('#userPopup strong'),
        // Elementos específicos de posts-consulta.php
        
        ...document.querySelectorAll('table tfoot tr td'),
        ...document.querySelectorAll('.table-responsive table th'),
        ...document.querySelectorAll('.table-responsive table td'),
        ...document.querySelectorAll('.btn'),
        ...document.querySelectorAll('.modal-title'),
        ...document.querySelectorAll('.modal-body label'),
        ...document.querySelectorAll('.modal-body input[type="text"]'),
        ...document.querySelectorAll('.modal-body textarea'),
        ...document.querySelectorAll('.modal-footer button'),
        ...document.querySelectorAll('.alert'),
        ...document.querySelectorAll('.form-group label'),
        ...document.querySelectorAll('.form-group input'),
        ...document.querySelectorAll('.form-group textarea'),
        ...document.querySelectorAll('.form-group select'),
        ...document.querySelectorAll('.form-group select option'),
        ...document.querySelectorAll('.form-group button'),
        // Categorías y valores
        ...document.querySelectorAll('select option'),
        ...document.querySelectorAll('input[type="text"]'),
        ...document.querySelectorAll('input[type="search"]'),
        ...document.querySelectorAll('.category-value'),
        ...document.querySelectorAll('[data-category]'),

       
    ];

    // Filtrar elementos que tienen texto
    const elementsWithText = elementsToTranslate.filter(el => el.textContent.trim());

    // Traducir cada elemento
    elementsWithText.forEach(element => {
        const originalText = element.textContent.trim();
        
        // Usar la API de Google Translate
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(originalText)}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data[0] && data[0][0] && data[0][0][0]) {
                    element.textContent = data[0][0][0];
                }
            })
            .catch(error => console.error('Error translating:', error));
    });

    // Actualizar el placeholder del buscador
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        const placeholder = searchInput.placeholder;
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(placeholder)}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data[0] && data[0][0] && data[0][0][0]) {
                    searchInput.placeholder = data[0][0][0];
                }
            })
            .catch(error => console.error('Error translating placeholder:', error));
    }

    // Guardar el idioma seleccionado
    localStorage.setItem('selectedLanguage', targetLang);
    console.log('Language saved to localStorage');
}

// Load saved language preference on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checking saved language preference');
    const savedLang = localStorage.getItem('selectedLanguage') || 'es';
    console.log('Saved language:', savedLang);
    
    if (savedLang !== 'es') {
        const languageIcon = document.getElementById('language-icon');
        if (languageIcon) {
            languageIcon.setAttribute('data-lang', savedLang);
            const flagImg = languageIcon.querySelector('.flag-icon');
            if (flagImg) {
                flagImg.src = savedLang === 'es' 
                    ? '/views/uploads/Bandera_de_España.svg.png'
                    : '/views/uploads/Flag_of_the_United_States.svg.png';
                flagImg.alt = savedLang === 'es' ? 'Español' : 'English';
                console.log('Initial flag set to:', savedLang);
            }
        }
        translatePage(savedLang);
    }
}); 