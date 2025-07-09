const selects = document.querySelectorAll('.filter__select');
console.log(selects, 'селекты');

selects.forEach(el =>{
    el.addEventListener('change', ()=> {
        el.blur()
    })
});
