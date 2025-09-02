console.log(' register script start')

class User{
    constructor(a,b,c,d,e,f){
        this.name = a;
        this.userName = b;
        this.email = c;
        this.tel = d;
        this.password = e;
        this.emailConsent = f;
        console.log(this, 'ОбЬект');
    }    
}

const registerSubmit = document.querySelector('.register__submit');
console.log(registerSubmit, 'submit');

registerSubmit.addEventListener('click', (event) => {
    console.log('submit click');

    let registerName = document.getElementById('rName').value;
    let registerUserName = document.getElementById('rUserName').value;
    let registerEmail = document.getElementById('rEmail').value;
    let registerTel = document.getElementById('rTel').value;
    let registerPassword = document.getElementById('rPassword').value;
    let registerPassword2 = document.getElementById('rPasswordTwo').value;
    let registerCheckbox = document.getElementById('rCheckbox').checked;
    let registerCheckbox2 = document.getElementById('rCheckboxTwo').checked;

    console.log(registerName, registerUserName, registerEmail, registerTel, registerPassword, registerPassword2, registerCheckbox, registerCheckbox2, 'ИНПУТЫ');

    if(registerPassword == registerPassword2){
        const newUser = new User(registerName,registerUserName,registerEmail,registerTel,registerPassword,registerCheckbox2);
        console.log(newUser, "Новый пользователь")
    } else{alert('Парроли не совпадаютю Повторите попытку!!!!!')}

});

const accauntBtn = document.getElementById('accauntBtn');
const register = document.querySelector('.register');
const registerCloseBtn = document.querySelector('.register__close');
const login = document.querySelector('.login');
const loginCloseBtn = document.querySelector('.login__close');
const registerOpenBtn = document.querySelector('.login__register-link');
const accauntChangeBtn = document.querySelector('.accaunt__info-btn');

accauntBtn.addEventListener('click', ()=>{
    document.body.style.overflow = 'hidden';
    login.style.display = 'block';
});

if(accauntChangeBtn){
accauntChangeBtn.addEventListener('click', ()=>{
    document.body.style.overflow = 'hidden';
    register.style.display = 'block';
});
};


registerCloseBtn.addEventListener('click',()=>{
    document.body.style.overflow = 'scroll';
    register.style.display = 'none'
});

loginCloseBtn.addEventListener('click',()=>{
    document.body.style.overflow = 'scroll';
    login.style.display = 'none'
})

registerOpenBtn.addEventListener('click',()=>{
    login.style.display = 'none'
    register.style.display = 'block'
})





