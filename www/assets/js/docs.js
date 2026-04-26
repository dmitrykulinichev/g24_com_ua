document.addEventListener('DOMContentLoaded', function() {
    const allImgs = document.querySelectorAll('.screenshot-content img, .screenshot-phone-content img');
    console.log('[Lightbox] DOMContentLoaded. Screenshot images found:', allImgs.length);
    allImgs.forEach((img, i) => console.log(`[Lightbox]   [${i}] src=${img.src} parent=${img.parentElement?.className}`));

    // 1. Створюємо розмітку модального вікна динамічно
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox-modal';
    lightbox.innerHTML = `
        <span class="lightbox-close">&times;</span>
        <img class="lightbox-content" src="" alt="Enlarged screenshot">
    `;
    document.body.appendChild(lightbox);

    const lightboxImg = lightbox.querySelector('.lightbox-content');
    const closeBtn = lightbox.querySelector('.lightbox-close');

    // 2. Функція відкриття
    function openLightbox(imgElement) {
        console.log('Opening lightbox for:', imgElement.src);
        
        // Беремо currentSrc, щоб отримати саме те зображення, яке зараз бачить користувач
        const src = imgElement.currentSrc || imgElement.src;
        
        lightboxImg.src = src;
        lightboxImg.alt = imgElement.alt;
        
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden'; // Блокуємо скрол сторінки
    }

    // 3. Функція закриття
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = ''; // Відновлюємо скрол
        setTimeout(() => { 
            lightboxImg.src = ''; 
        }, 300); // Очистка після анімації
    }

    // 4. Делегування подій (Event Delegation)
    // Слухаємо кліки на всьому документі, але реагуємо тільки якщо клікнули на картинку всередині .screenshot-content
    document.addEventListener('click', function(e) {
        console.log('[Lightbox] click on:', e.target.tagName, e.target.className || e.target.src);
        if (e.target.tagName === 'IMG') {
            const parent = e.target.closest('.screenshot-content, .screenshot-phone-content');
            console.log('[Lightbox] IMG clicked. Closest screenshot parent:', parent ? parent.className : 'NOT FOUND');
        }
        if (e.target.tagName === 'IMG' && e.target.closest('.screenshot-content, .screenshot-phone-content')) {
            openLightbox(e.target);
        }
        
        // Закриття по кліку на хрестик
        if (e.target.classList.contains('lightbox-close')) {
            closeLightbox();
        }

        // Закриття по кліку на фон
        if (e.target.classList.contains('lightbox-modal')) {
            closeLightbox();
        }
    });

    // Підтримка клавіатури (Enter на картинці)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName === 'IMG' && e.target.closest('.screenshot-content, .screenshot-phone-content')) {
            openLightbox(e.target);
        }
        
        // Закриття по Esc
        if (e.key === 'Escape' && lightbox.classList.contains('active')) {
            closeLightbox();
        }
    });

    // Додаємо tabindex для доступності всім картинкам
    const screenshots = document.querySelectorAll('.screenshot-content img, .screenshot-phone-content img');
    screenshots.forEach(img => {
        img.setAttribute('tabindex', '0');
    });
});