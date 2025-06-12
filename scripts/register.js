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

         fetch('https://mainproject/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', 
                'X-Custom-Header': 'MyValue' 
            },
            body: JSON.stringify(this)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Успех:', data);
        })
    }    
}

const registerSubmit = document.querySelector('.register__submit');
console.log(registerSubmit, 'submit');

registerSubmit.addEventListener('click', (event) => {
    event.preventDefault();
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

})




