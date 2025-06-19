console.log('telMask script started');

const telInput = document.querySelectorAll('input[type="tel"]');
console.log(telInput, 'telInput');

function onPhoneInput(e) {
    const input = e.target;
    let inputValue = input.value;
    
    const selectionStart = input.selectionStart;
    const selectionEnd = input.selectionEnd;
    const isBackspace = inputValue.length < input._prevValue?.length;
    
    let cleaned = inputValue.replace(/\D/g, '');
    
    const isRussian = cleaned.length > 0 && ['7', '8', '9'].includes(cleaned[0]);
    let formattedValue = '';
    
    if (isRussian) {

        if (cleaned[0] === '9') cleaned = '7' + cleaned;
        if (cleaned[0] === '8') cleaned = '7' + cleaned.substring(1);
        
        if (cleaned.length > 0) {
            formattedValue = '+7';
            
            if (cleaned.length > 1) {
                formattedValue += ' (' + cleaned.substring(1, 4);
                
                if (cleaned.length >= 4) {
                    formattedValue += ')';
                    
                    if (cleaned.length > 4) {
                        formattedValue += ' ' + cleaned.substring(4, 7);
                        
                        if (cleaned.length > 7) {
                            formattedValue += '-' + cleaned.substring(7, 9);
                            
                            if (cleaned.length > 9) {
                                formattedValue += '-' + cleaned.substring(9, 11);
                            }
                        }
                    }
                }
            }
        }
    } else {
        formattedValue = cleaned ? '+' + cleaned : '';
    }
    
    input.value = formattedValue;
    input._prevValue = formattedValue; 

    if (!isBackspace && selectionStart === selectionEnd) {
        const newPosition = selectionStart + (formattedValue.length - inputValue.length);
        input.setSelectionRange(newPosition, newPosition);
    } else {
        input.setSelectionRange(selectionStart, selectionEnd);
    }
}

for (let i = 0; i < telInput.length; i++) {
    const input = telInput[i];
    input.addEventListener('input', onPhoneInput);
    
    input._prevValue = input.value;
}