console.log('more script started')

const moreBtn =  document.getElementById('moreBtn');
console.log(moreBtn);
const more = document.querySelector('.hero__more')
console.log(more);
const moreCloseBtn = document.querySelector('.hero__more-window-close-img');
const moreWindow = document.querySelector('.hero__more-window');
const slides = document.querySelectorAll('.hero__slides > *');
console.log(slides, 'slides');
let currentSlide = 1;
const moreRightBtn = document.querySelector('.hero__slider-right-btn');
const moreLeftBtn = document.querySelector('.hero__slider-left-btn');
const paginationMenu = document.querySelector('.hero__slider-pagination');

function slideSwitchStart() {
    let slide = slides[currentSlide - 1];
    console.log(slide,'slide');
    slide.style.display = 'none';
    let pogination = document.querySelector(`.hero__slider-pagination :nth-child(${currentSlide})`);
    console.log(pogination)
    pogination.classList.remove('is-active')
}

function slideSwitchEnd() {
    let slide = slides[currentSlide - 1];
    console.log(slide,'slide');
    slide.style.display = 'block';
    let pogination = document.querySelector(`.hero__slider-pagination :nth-child(${currentSlide})`);
    console.log(pogination)
    pogination.classList.add('is-active')
}

function slideSwitch(n){
    slideSwitchStart();
    currentSlide  = currentSlide +n;
    slideSwitchEnd();
};

moreBtn.addEventListener('click',()=>{
    window.scrollTo({top: 0, left: 0});
    document.body.style.overflow = 'hidden';
    more.style.display = 'none';
    moreWindow.style.display = 'block'
});

moreCloseBtn.addEventListener('click',()=>{
    document.body.style.overflow = 'scroll';
    moreWindow.style.display = 'none';
    more.style.display = 'block';
});

moreLeftBtn.addEventListener('click', ()=>{
    if (currentSlide > 1) {
        slideSwitch(-1);
        console.log(currentSlide)
    }
})

moreRightBtn.addEventListener('click', ()=>{
    if (currentSlide < slides.length) {
        slideSwitch(1);
        console.log(currentSlide)
    }
});

paginationMenu.addEventListener('click', (event) => {
    if (event.target.dataset.set != undefined) {
        slideSwitchStart();
        currentSlide = parseInt(event.target.dataset.set);
        slideSwitchEnd();
    } else console.log('none');

})