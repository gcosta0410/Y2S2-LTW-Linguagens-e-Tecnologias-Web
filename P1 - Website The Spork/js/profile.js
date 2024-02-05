function selectActiveProfile(jef){
    const button = jef.firstElementChild;
    const buttonID = jef.firstElementChild.id;

    if(button.classList.contains('active-profile')){}

    var profileInfolist = document.getElementsByClassName('profile-info')
    var profileTypeList = document.getElementsByClassName('profile-type')

    for (const profiletype of profileTypeList) {
        
        if(profiletype.classList.contains('active-profile')) {
            profiletype.classList.toggle('active-profile')
        }
        if(profiletype.id === buttonID){
            profiletype.classList.toggle('active-profile')
        }
    }

    for (const profileInfo of profileInfolist) {
        if (profileInfo.classList.contains('nonselected') === false) {profileInfo.classList.toggle('nonselected')}
        if (profileInfo.classList.contains(buttonID)) {
            profileInfo.classList.toggle('nonselected')
        }   
    }
}


/**
 * 
 * @param {Element} button 
 */
function toggleEditing(button){
    let inputs = button.parentElement.getElementsByTagName('input');
    let state = button.textContent;
    if(state === 'Edit'){
        for (const input of inputs) {
            input.disabled = false
        }
        button.textContent = "Save"
        return false
    }
    if(state === 'Save'){
        for (const input of inputs) {
            input.disabled = true
        }
        button.textContent = "Edit"
        return true
    }
}

/**
 * 
 * @param {Element} button 
 */
function togglePWfORM(button){
    let menu = button.parentElement.nextElementSibling
    
    if(menu.classList.contains('nonselected') === false){
        button.firstElementChild.classList.toggle("fa-chevron-up"); 
        button.firstElementChild.classList.toggle("fa-chevron-down")
    } else{
        button.firstElementChild.classList.remove("fa-chevron-down")
        button.firstElementChild.classList.add("fa-chevron-up")
    }
        menu.classList.toggle('nonselected')
}

function isValidUsername(){
    const username = document.getElementById("username").value;
    const invalid_chars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~«»ºª]/;
    return !invalid_chars.test(username);
}

function isValidName(){
    const name = document.getElementById("full-name").value;
    const invalid_chars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~1234567890«»ºª]/;
    return !invalid_chars.test(name);
}

function isValidEmail(){
    const email = document.getElementById("email-address").value;
    const required_chars = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return required_chars.test(email);
}

function isValidPhoneNO(){
    const phoneNO = document.getElementById("phone-no").value;
    const invalid_chars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~«»ºª]/;
    const required_chars = /\d+/;
    return phoneNO[0] === '+' && required_chars.test(phoneNO.substring(1)) && !invalid_chars.test(phoneNO.substring(1));
}

function validateUserInfo(){
    if(!isValidUsername()){
        document.getElementById("usernameError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidName()){
        document.getElementById("nameError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidEmail()){
        document.getElementById("emailError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidPhoneNO()){
        document.getElementById("phoneError").classList.remove("hiddenError");
        return false;
    }
    return true;
}


function isValidPassword1(){
    const pwd = document.getElementById("newPassword1").value;
    const required_schars = /[ `!#$%^&*()_+\-=\[\]{};':"\\|,<>\/?~«»ºª]/;
    const required_numbers =/[1234567890]/;
    const required_upper =/[A-Z]/;
    const required_lower =/[a-z]/;
    return required_schars.test(pwd) && required_numbers.test(pwd) && required_upper.test(pwd) && required_lower.test(pwd);
}

function isValidPassword2(){
    const pwd1 = document.getElementById("newPassword1").value;
    const pwd2 = document.getElementById("newPassword2").value;
    return pwd1 === pwd2;
}

function validateChangePWInputs(){

   if(!isValidPassword1()){
        document.getElementById("pw1Error").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidPassword2()){
        document.getElementById("pw2Error").classList.remove("hiddenError");
        return false;
    }
    return true;

}

document.getElementById("fileInput").onchange = function() {
    document.getElementById("picform").submit();
};

/**
 * 
 * @param {Element} button 
 */
function dropDownOrders(button){
    document.querySelector('.restaurant-orders').classList.toggle('nonselected')
}
