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
                    : '/views/uploads/Flag_of_the_United_Kingdom_(1-2).svg.png';
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

// Almacenar textos originales
let originalTexts = new Map();

function translatePage(targetLang) {
    // Elementos que queremos traducir
    const elementsToTranslate = [
        // Elementos de navegación
        ...document.querySelectorAll('.texto_a .hover-text'),
        ...document.querySelectorAll('.nav__items .hover-text'),
        document.getElementById('hola-text'),
        
        // Elementos de contenido principal
        ...document.querySelectorAll('.encabezado h1'),
        ...document.querySelectorAll('h1'),
        ...document.querySelectorAll('h2.texto[name="crear_posts"]'),
        ...document.querySelectorAll('h2:not([style*="color:white"])'),
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
        
        // Elementos de lista
        ...document.querySelectorAll('.list li a'),
        ...document.querySelectorAll('.list li span'),
        ...document.querySelectorAll('.list li .hover-text'),
        
        // Elementos específicos de posts-consulta.php
        ...document.querySelectorAll('table th'),
        ...document.querySelectorAll('table tbody tr td:not(:has(.btn))'),
        ...document.querySelectorAll('table tbody tr th'),
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
        
        // Botones de perfil
        ...document.querySelectorAll('.btn-editar-perfil'),
        
        // Botones de inicio de sesión y registro
        ...document.querySelectorAll('form button[type="submit"]'),
        ...document.querySelectorAll('.form-container button'),
        ...document.querySelectorAll('#btn__Iniciar-Sesión'),
        ...document.querySelectorAll('#btn__registrarse'),
        
        // Botones de publicación
        ...document.querySelectorAll('.btn-editar-publicacion'),
        ...document.querySelectorAll('.boton-agregar-referencia'),
        
        // Inputs de referencias
        ...document.querySelectorAll('.input-referencia'),
        
        // Elementos de comentarios
        ...document.querySelectorAll('textarea[name="content"]'),
        ...document.querySelectorAll('button[name="submit_comment"]'),
        
        // Texto de la página about
        ...document.querySelectorAll('.texto')
    ];

    // Filtrar elementos que tienen texto
    const elementsWithText = elementsToTranslate.filter(el => el.textContent.trim());
    
    // Debug: Imprimir elementos seleccionados
    console.log('Elementos a traducir:', elementsToTranslate);
    console.log('Elementos con texto:', elementsWithText);

    // Función para dividir texto en chunks más pequeños
    function splitTextIntoChunks(text, maxLength = 5000) {
        // Si el texto es corto, no lo dividimos
        if (text.length <= maxLength) {
            return [text];
        }

        const chunks = [];
        let currentChunk = '';
        const sentences = text.split(/(?<=[.!?])\s+/);
        
        for (const sentence of sentences) {
            if ((currentChunk + sentence).length > maxLength) {
                if (currentChunk) chunks.push(currentChunk.trim());
                currentChunk = sentence;
            } else {
                currentChunk += (currentChunk ? ' ' : '') + sentence;
            }
        }
        if (currentChunk) chunks.push(currentChunk.trim());
        return chunks;
    }

    // Función para traducir texto
    async function translateText(text, targetLang) {
        if (targetLang !== 'en') return text;
        
        // Dividir el texto en párrafos
        const paragraphs = text.split(/\n\s*\n/);
        const translatedParagraphs = [];
        
        for (const paragraph of paragraphs) {
            const chunks = splitTextIntoChunks(paragraph);
            const translatedChunks = [];
            
            for (const chunk of chunks) {
                const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=es&tl=${targetLang}&dt=t&q=${encodeURIComponent(chunk)}`;
                
                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    if (data && data[0]) {
                        const translatedText = data[0]
                            .map(item => item[0])
                            .filter(text => text)
                            .join(' ');
                        translatedChunks.push(translatedText);
                    }
                } catch (error) {
                    console.error('Error translating chunk:', error);
                    translatedChunks.push(chunk);
                }
            }
            
            translatedParagraphs.push(translatedChunks.join(' '));
        }
        
        // Unir los párrafos traducidos manteniendo los saltos de línea
        return translatedParagraphs.join('\n\n');
    }

    // Traducir cada elemento
    elementsWithText.forEach(async element => {
        const originalText = element.textContent.trim();
        const originalHTML = element.innerHTML;
        
        // Guardar el texto original y el HTML si no está guardado
        if (!originalTexts.has(element)) {
            originalTexts.set(element, {
                text: originalText,
                html: originalHTML,
                style: element.getAttribute('style') || '',
                class: element.getAttribute('class') || ''
            });
        }

        if (targetLang === 'en') {
            let translatedText;
            
            // Si es el elemento del saludo, usar la traducción predefinida
            if (element.id === 'hola-text') {
                translatedText = translations.en.hola;
            } else {
                translatedText = await translateText(originalText, targetLang);
            }
            
            // Preservar el formato HTML original
            const original = originalTexts.get(element);
            
            // Reemplazar el texto manteniendo las etiquetas HTML
            let newHTML = original.html;
            const paragraphs = originalText.split(/\n\s*\n/);
            const translatedParagraphs = translatedText.split(/\n\s*\n/);
            
            paragraphs.forEach((paragraph, index) => {
                if (translatedParagraphs[index]) {
                    newHTML = newHTML.replace(paragraph, translatedParagraphs[index]);
                }
            });
            
            element.innerHTML = newHTML;
            element.setAttribute('style', original.style);
            element.setAttribute('class', original.class);
        } else {
            // Restaurar el texto original en español con su formato
            const original = originalTexts.get(element);
            element.innerHTML = original.html;
            element.setAttribute('style', original.style);
            element.setAttribute('class', original.class);
        }
    });

    // Traducir específicamente el título de configuración
    const allH2s = document.querySelectorAll('h2:not([style*="color:white"])');
    allH2s.forEach(async h2 => {
        const text = h2.textContent.trim();
        if (text === 'Configuración') {
            // Guardar el texto original si no está guardado
            if (!originalTexts.has(h2)) {
                originalTexts.set(h2, text);
            }

            if (targetLang === 'en') {
                const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=es&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    if (data && data[0] && data[0][0] && data[0][0][0]) {
                        h2.textContent = data[0][0][0];
                    }
                } catch (error) {
                    console.error('Error translating configuration title:', error);
                }
            } else {
                h2.textContent = originalTexts.get(h2);
            }
        }
    });

    // Actualizar los placeholders de los buscadores
    const searchInputs = [
        document.getElementById('searchInput'),
        document.getElementById('search-input')
    ];

    searchInputs.forEach(async searchInput => {
        if (searchInput) {
            const placeholder = searchInput.placeholder;
            
            // Guardar el placeholder original si no está guardado
            if (!originalTexts.has(searchInput)) {
                originalTexts.set(searchInput, placeholder);
            }

            if (targetLang === 'en') {
                const translatedPlaceholder = await translateText(placeholder, targetLang);
                searchInput.placeholder = translatedPlaceholder;
            } else {
                searchInput.placeholder = originalTexts.get(searchInput);
            }
        }
    });

    // Traducir placeholders de los campos de registro
    const signinInputs = [
        document.querySelector('input[name="fullname"]'),
        document.querySelector('input[name="email"]'),
        document.querySelector('input[name="username"]'),
        document.querySelector('input[name="password"]'),
        document.querySelector('input[type="email"]'),
        document.querySelector('input[type="password"]'),
        document.querySelector('#passwordRegistrarse'),
        document.querySelector('#confirm_password')
    ];

    signinInputs.forEach(async input => {
        if (input) {
            const placeholder = input.placeholder;
            
            // Guardar el placeholder original si no está guardado
            if (!originalTexts.has(input)) {
                originalTexts.set(input, placeholder);
            }

            if (targetLang === 'en') {
                const translatedPlaceholder = await translateText(placeholder, targetLang);
                input.placeholder = translatedPlaceholder;
            } else {
                input.placeholder = originalTexts.get(input);
            }
        }
    });

    // Traducir placeholders de los campos de referencias
    const referenceInputs = document.querySelectorAll('.input-referencia');
    referenceInputs.forEach(async input => {
        if (input) {
            const placeholder = input.placeholder;
            
            // Guardar el placeholder original si no está guardado
            if (!originalTexts.has(input)) {
                originalTexts.set(input, placeholder);
            }

            if (targetLang === 'en') {
                const translatedPlaceholder = await translateText(placeholder, targetLang);
                input.placeholder = translatedPlaceholder;
            } else {
                input.placeholder = originalTexts.get(input);
            }
        }
    });

    // Traducir placeholders de los campos de comentarios
    const commentTextareas = document.querySelectorAll('textarea[name="content"]');
    commentTextareas.forEach(async textarea => {
        if (textarea) {
            const placeholder = textarea.placeholder;
            
            // Guardar el placeholder original si no está guardado
            if (!originalTexts.has(textarea)) {
                originalTexts.set(textarea, placeholder);
            }

            if (targetLang === 'en') {
                const translatedPlaceholder = await translateText(placeholder, targetLang);
                textarea.placeholder = translatedPlaceholder;
            } else {
                textarea.placeholder = originalTexts.get(textarea);
            }
        }
    });

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
                    : '/views/uploads/Flag_of_the_United_Kingdom_(1-2).svg.png';
                flagImg.alt = savedLang === 'es' ? 'Español' : 'English';
                console.log('Initial flag set to:', savedLang);
            }
        }
        translatePage(savedLang);
    }
}); 