document.addEventListener('DOMContentLoaded', () => {
    // Перевірка видимості елемента
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Функція створення Ghost Button
    function triggerGhostNav(direction, title, xPos) {
        const ghost = document.createElement('div');
        ghost.className = `nav-ghost ghost-${direction}`;
        
        // Встановлюємо горизонтальну позицію
        if (direction === 'left') {
            ghost.style.left = xPos + 'px';
        } else {
            // Для правої кнопки ми отримали rect.right, але CSS right працює від правого краю
            const rightPos = document.documentElement.clientWidth - xPos;
            ghost.style.right = rightPos + 'px';
        }
        
        // Формуємо контент: Стрілка + Назва
        let contentHtml = '';
        if (direction === 'left') {
            contentHtml = `<span class="nav-arrow">←</span><span class="nav-title">${title}</span>`;
        } else {
            contentHtml = `<span class="nav-title">${title}</span><span class="nav-arrow">→</span>`;
        }
        
        ghost.innerHTML = contentHtml;
        
        document.body.appendChild(ghost);

        setTimeout(() => {
            ghost.remove();
        }, 600);
    }

    // Навігація стрілками
    document.addEventListener('keydown', function(event) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') return;

        if (event.key === 'ArrowLeft') {
            const prevLink = document.querySelector('.nav-prev');
            if (prevLink) {
                if (isElementInViewport(prevLink)) {
                    // Якщо кнопка видима - просто клікаємо з ефектом
                    prevLink.classList.add('active-press');
                    setTimeout(() => prevLink.classList.remove('active-press'), 200);
                    setTimeout(() => prevLink.click(), 100);
                } else {
                    // Якщо не видима - показуємо привида
                    const title = prevLink.querySelector('.nav-title').innerText;
                    // Вираховуємо позицію відносно контейнера навігації
                    const navContainer = prevLink.closest('.nav-container-wrapper'); // Шукаємо обгортку
                    if (navContainer) {
                        const rect = navContainer.getBoundingClientRect();
                        triggerGhostNav('left', title, rect.left);
                    } else {
                        // Фолбек, якщо обгортки немає (хоча має бути)
                        triggerGhostNav('left', title, 20); 
                    }
                    
                    setTimeout(() => prevLink.click(), 300);
                }
            }
        } else if (event.key === 'ArrowRight') {
            const nextLink = document.querySelector('.nav-next');
            if (nextLink) {
                if (isElementInViewport(nextLink)) {
                    nextLink.classList.add('active-press');
                    setTimeout(() => nextLink.classList.remove('active-press'), 200);
                    setTimeout(() => nextLink.click(), 100);
                } else {
                    const title = nextLink.querySelector('.nav-title').innerText;
                    const navContainer = nextLink.closest('.nav-container-wrapper');
                    if (navContainer) {
                        const rect = navContainer.getBoundingClientRect();
                        triggerGhostNav('right', title, rect.right);
                    } else {
                        triggerGhostNav('right', title, 20);
                    }
                    
                    setTimeout(() => nextLink.click(), 300);
                }
            }
        }
    });
});