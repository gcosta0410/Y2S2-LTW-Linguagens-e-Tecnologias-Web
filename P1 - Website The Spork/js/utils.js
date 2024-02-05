//HiddenCards

function hiddenRestaurantCard(){
    let restaurantCards = document.getElementsByClassName('restaurant-card');
    for (let index = 4; index < restaurantCards.length; index++) {
        let restCard = restaurantCards[index];
        restCard.classList.toggle('hidden-card')    
    }
} hiddenRestaurantCard();