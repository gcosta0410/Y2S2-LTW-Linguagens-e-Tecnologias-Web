//Set minimum and maximum date of birth for register form
function setMinMaxDoB(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    } 
    
    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("datePicker").setAttribute("max", today);
    document.getElementById("datePicker").setAttribute("min", "1900-01-01");
    document.getElementById("datePicker").value = today;
} setMinMaxDoB();



//Sliding container displaying restaurants
function onHandleClick(handle) {
    const slider = handle.closest(".category-container").querySelector(".restaurants-list").querySelector(".slider-container")
    const cards = slider.querySelectorAll(".restaurant-card")
    const sliderIndex = parseInt(getComputedStyle(slider).getPropertyValue("--slider-index"))
    if (handle.classList.contains("previous-items")) {
        if (sliderIndex === 0) {}
        else {
            for (const card of cards) {card.classList.toggle("hidden-card")}
            slider.style.setProperty("--slider-index", sliderIndex - 1)
            handle.nextElementSibling.classList.toggle("locked-button")
            handle.classList.toggle("locked-button")            
        }
    }
    if (handle.classList.contains("next-items")) {
        if (sliderIndex === 1) {}
        else {
            for (const card of cards) {card.classList.toggle("hidden-card")}
            slider.style.setProperty("--slider-index", sliderIndex + 1)
            handle.previousElementSibling.classList.toggle("locked-button")
            handle.classList.toggle("locked-button")
        }
    }
}

//Sliding container displaying restaurants --!!Change name or delete since it's embedded in index.php
function slidingContainer(){
    document.addEventListener("click", e => {
        let handle
        if (e.target.matches(".handle")) {
            handle = e.target
        } else {
            handle = e.target.closest(".handle")
        }
        if (handle != null) onHandleClick(handle)
    });
} slidingContainer();

//Add events to login and register form buttons
function loginRegisterFormsEventListener(){
    document.addEventListener("DOMContentLoaded", ()=>{
        const login = document.querySelector(".login-popup");
        const register = document.querySelector(".register-popup");
        const body = document.querySelector("body");
        
        //Clicking Register button on header
        document.getElementById("signUp").addEventListener("click", e =>{
            e.preventDefault();
            /*register.classList.remove("dontShow");
            login.classList.add("dontShow");*/
            body.classList.add("body-noscroll");
            register.showModal();
        });
        
        //Clicking Login button on header
        document.getElementById("signIn").addEventListener("click", e =>{
            e.preventDefault();
            /*register.classList.add("dontShow");
            login.classList.remove("dontShow");*/
            body.classList.add("body-noscroll");
            login.showModal();
        });
        
        
        //Clicking Register button on login form
        document.querySelector(".signup-now a").addEventListener("click", e =>{
            e.preventDefault();
            /*register.classList.remove("dontShow");
            login.classList.add("dontShow");*/
            body.classList.add("body-noscroll");
            register.showModal();
            login.close();
        });
        
        //Clicking Login button on register form
        document.querySelector(".login-now a").addEventListener("click", e =>{
            e.preventDefault();
            /*register.classList.add("dontShow");
            login.classList.remove("dontShow");*/
            body.classList.add("body-noscroll");
            register.close();
            login.showModal();
        });
        
        //Clicking x button on forms
        const closebtns = document.getElementsByClassName("close-btn");
        for (var i = 0; i < closebtns.length; i++) {
            const button = closebtns.item(i);
            button.addEventListener("click", e => {
                e.preventDefault();
                /*register.classList.add("dontShow");
                login.classList.add("dontShow");*/
                body.classList.remove("body-noscroll");
                login.close();
                register.close();
            });
        }
    });
} loginRegisterFormsEventListener();

function reOpenRegisterError(){
    const register = document.querySelector(".register-popup");
    register.showModal();
}