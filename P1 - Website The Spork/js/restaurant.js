function orderDropdownMenu(){
    const button = document.getElementById("order-now");
    button.addEventListener("click", function (){
        const menu = button.nextElementSibling
        menu.classList.toggle("nonselected")

    });
} orderDropdownMenu();


function SelectedDescriptorListener(){
    document.addEventListener("click", e => {
        let descriptor
        if(e.target.matches('.restaurant-descriptors')){
            descriptor = e.target
        }
        if(descriptor != null) showSelectedDescriptor(descriptor);
    })
} SelectedDescriptorListener(); 

function showSelectedDescriptor(descriptor){
    let description = document.getElementById("description")
    let menuItems = document.getElementById("menu-items")
    if(descriptor.classList.contains('selected-descriptor')){}
    else{
        descriptor.classList.toggle('selected-descriptor')
        if(descriptor.nextElementSibling != null){descriptor.nextElementSibling.classList.toggle('selected-descriptor')}
        else descriptor.previousElementSibling.classList.toggle('selected-descriptor')
        description.classList.toggle('nonselected')
        menuItems.classList.toggle('nonselected')
    }
}
/**
 * 
 * @param {Element} button 
 */
function replying(button){
    //let replybuttons = document.getElementsByClassName('reply-button');
    button.previousElementSibling.classList.toggle('nonselected')
    button.parentElement.nextElementSibling.classList.toggle('nonselected')
    if(button.textContent === "Cancel"){button.textContent = "Reply"}
    else button.textContent = "Cancel"
}

function reviewing(button){
    let commentarea = button.previousElementSibling; commentarea.classList.toggle('nonselected')
    commentarea.previousElementSibling.classList.toggle('nonselected')
    commentarea.previousElementSibling.previousElementSibling.classList.toggle('nonselected')
    button.parentElement.nextElementSibling.classList.toggle('nonselected')
    if(button.textContent === "Cancel"){button.textContent = "Add review"}
    else button.textContent = "Cancel"
}

function foodtypes(){
    // const greenElement = document.createElement("i");
    // greenElement.classList.add("fa-envira")
    // greenElement.classList.add("fab")
    let categories = document.getElementsByClassName('category');
    for (const category of categories) {
        if (category.textContent === 'vegan' || category.textContent === 'vegetariano') {
            category.style.color = "green"
            category.style.setProperty('border-color', "green")
            //category.appendChild(greenElement);
            category.firstElementChild.classList.toggle('nonselected')
        }
    }
} foodtypes();

/**
 * 
 * @param {Element} button 
 */
function addToOrder(button){

    let orderList = document.getElementById('order-list');

    let quantity = document.createElement('input'); quantity.type="number"; quantity.name="quantity[]"; quantity.disabled=true; quantity.value = "1"; quantity.classList.add("quantity")

    let itemNameinOrder = document.createElement('input'); itemNameinOrder.classList.add("item-name-inorder");
    let itemPriceinOrder = document.createElement('input'); itemPriceinOrder.name="price[]"; itemPriceinOrder.type="number"; itemPriceinOrder.disabled=true; itemPriceinOrder.classList.add("item-price-inorder");

    let itemPrice = button.previousElementSibling; let itemName = itemPrice.previousElementSibling;
    
    itemNameinOrder.textContent = itemName.textContent
    itemPriceinOrder.value = parseInt(itemPrice.textContent);

    /*Buttons to add or reduce*/
    let listitem = document.createElement("li");
    let plusicon = document.createElement("i"); plusicon.classList.add("fas"); plusicon.classList.add("fa-plus");
    let minusicon = document.createElement("i"); minusicon.classList.add("fas"); minusicon.classList.add("fa-minus");
    let addItembutton = document.createElement("button"); addItembutton.onclick = function () { addQuantity(addItembutton,itemPrice.value);};
    addItembutton.appendChild(plusicon);
    let removeItembutton = document.createElement("button"); removeItembutton.onclick = function () {lowerQuantity(removeItembutton, button, itemPrice.value);};
    removeItembutton.appendChild(minusicon); removeItembutton.classList.add('remove-quantity')

    //Creating listitem of menuitem
    listitem.appendChild(itemNameinOrder); 
    listitem.appendChild(quantity)
    listitem.appendChild(itemPriceinOrder)
    listitem.appendChild(removeItembutton);
    listitem.appendChild(addItembutton);

    orderList.appendChild(listitem);
    totalOrderPrice();
    button.classList.add("nonselected")
}

//function handleQuantity(){};
/**
 * 
 * @param {Element} button 
 */
function addQuantity(button, price){
    let listitem = button.parentElement.children;
    let quantity = listitem.item(1); let itemprice = listitem.item(2);
    quantity.value++; let intprice = itemprice.value
    intprice+= price
    itemprice.value = intprice
    totalOrderPrice();
}

function lowerQuantity(button, buttoninmenu, price){
    let listitem = button.parentElement
    let listitems = listitem.children;
    let quantity = listitems.item(1); let itemprice = listitems.item(2);
    let intprice = itemprice.value
    intprice-= price
    

    if (quantity.value === 1) {
        listitem.remove();
        buttoninmenu.classList.toggle("nonselected")
        totalOrderPrice();        
    } else{
        quantity.value--
        itemprice.value = intprice
    }

    totalOrderPrice();
    
}

function totalOrderPrice(){

    let totalprice = document.getElementById('total-price')
    let itemprices = document.getElementsByClassName('item-price-inorder');
    
        totalprice.textContent = "0"
    
        let count = 0
        for (const itemprice of itemprices) {
            count+= itemprice.value
        }
        totalprice.textContent = count.toString();
    
} totalOrderPrice()

function updateNewRating(){
    
}