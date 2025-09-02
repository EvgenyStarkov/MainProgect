const listElement = document.querySelector('.fresh-hits__list');
const listElement1 = document.querySelectorAll('.collections__item-body:not(.grid)'); // Измененный селектор

if (listElement) {
    listElement.addEventListener('wheel', (e) => {
        e.preventDefault();
        listElement.scrollLeft += e.deltaY * 3;
    }, { passive: false });
}

if (listElement1) {
    listElement1.forEach(el => {
        el.addEventListener('wheel', (e) => {
            e.preventDefault();
            el.scrollLeft += e.deltaY;
        }, { passive: false });
    });
}

console.log(listElement1);