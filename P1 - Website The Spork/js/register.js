function isValidUsername(){
    const username = document.getElementById("username").value;
    const invalid_chars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~«»ºª]/;
    return !invalid_chars.test(username);
}

function isValidName(){
    const name = document.getElementById("full_name").value;
    const invalid_chars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~1234567890«»ºª]/;
    return !invalid_chars.test(name);
}

function isValidEmail(){
    const email = document.getElementById("email").value;
    const required_chars = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return required_chars.test(email);
}

function isValidPassword1(){
    const pwd = document.getElementById("pwd1").value;
    const required_schars = /[ `!#$%^&*()_+\-=\[\]{};':"\\|,<>\/?~«»ºª]/;
    const required_numbers =/[1234567890]/;
    const required_upper =/[A-Z]/;
    const required_lower =/[a-z]/;
    return required_schars.test(pwd) && required_numbers.test(pwd) && required_upper.test(pwd) && required_lower.test(pwd);
}

function isValidPassword2(){
    const pwd1 = document.getElementById("pwd1").value;
    const pwd2 = document.getElementById("pwd2").value;
    return pwd1 === pwd2;
}

function isValidDOB(){
    const today = new Date();
    const date18YO = (today.getFullYear() - 18) + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    const dob = document.getElementById("datePicker").value;
    return dob < date18YO;
}

function isValidPhoneNo(){
    const phoneNO = document.getElementById("telephoneNr").value;
    const required_chars = /^\d+$/;
    return required_chars.test(phoneNO);
}

function validateRegisterInputs(){
    document.getElementById("usernameError").classList.add("hiddenError");
    document.getElementById("fullNameError").classList.add("hiddenError");
    document.getElementById("emailError").classList.add("hiddenError");
    document.getElementById("pw1Error").classList.add("hiddenError");
    document.getElementById("pw2Error").classList.add("hiddenError");
    document.getElementById("dobError").classList.add("hiddenError");
    document.getElementById("phoneError").classList.add("hiddenError");

    if(!isValidUsername()) {
        document.getElementById("usernameError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidName()){
        document.getElementById("fullNameError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidEmail()){
        document.getElementById("emailError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidPassword1()){
        document.getElementById("pw1Error").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidPassword2()){
        document.getElementById("pw2Error").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidDOB()){
        document.getElementById("dobError").classList.remove("hiddenError");
        return false;
    }
    else if(!isValidPhoneNo()){
        document.getElementById("phoneError").classList.remove("hiddenError");
        return false;
    }
    return true;

}