document.addEventListener('DOMContentLoaded', () => {
    const heroSection = document.querySelector('.hero');
    const menuSection = document.querySelector('.menu');
    const footerSection = document.querySelector('.footer');
    const header = document.querySelector('.header');
    const nav = document.querySelector('.header__nav');
    
    if (!heroSection || !menuSection || !footerSection || !header || !nav) {
        console.error('One or more elements are missing');
        return;
    }

    // Функция для проверки видимости элемента
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= window.innerHeight * 0.9 && 
            rect.bottom >= window.innerHeight * 0.1
        );
    }

    // Observer для hero-секции
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                header.classList.add('sticky-header');
            } else {
                header.classList.remove('sticky-header');
                nav.style.display = 'none'; // Гарантированно скрываем нав в hero
            }
        });
    }, { threshold: 0.1 });

    // Observer для секции menu
    const menuObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                nav.style.display = 'none'; // Скрываем нав в menu
            }
        });
    }, { threshold: 0.1 });

    // Observer для секции fresh-hits
    const freshHitsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                nav.style.display = 'none'; // Скрываем нав в fresh-hits
            }
        });
    }, { threshold: 0.1 });

    // Observer для футера
    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                header.classList.remove('sticky-header');
            } else {
                if (!isElementInViewport(heroSection)) {
                    header.classList.add('sticky-header');
                    // Показываем нав только если мы в collection
                    if (!isElementInViewport(menuSection) && 
                        !isElementInViewport(freshHitsSection)) {
                        nav.style.display = 'flex';
                    }
                }
            }
        });
    }, { threshold: 0.1 });

    // Находим секцию fresh-hits
    const freshHitsSection = document.querySelector('.fresh-hits');
    
    // Запускаем observers
    heroObserver.observe(heroSection);
    menuObserver.observe(menuSection);
    footerObserver.observe(footerSection);
    
    if (freshHitsSection) {
        freshHitsObserver.observe(freshHitsSection);
    }

    // Дополнительный обработчик скролла для точного контроля
    window.addEventListener('scroll', () => {
        if (header.classList.contains('sticky-header')) {
            // Показываем нав только в collection
            if (isElementInViewport(menuSection) || 
                isElementInViewport(freshHitsSection) ||
                isElementInViewport(heroSection)) {
                nav.style.display = 'none';
            } else {
                nav.style.display = 'flex';
            }
        }
    });
});