<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__. '/../templates/common.tpl.php');
    require_once(__DIR__. '/../database/restaurant.class.php');
    require_once(__DIR__. '/../database/review.class.php');
    require_once(__DIR__. '/../database/comment.class.php');

    $db = getDatabaseConnection();



    drawHeader($session);

    $restaurant = Restaurant::getRestaurantByID($db, intval($_GET['id']));
    $reviews = Review::getReviewsbyRestID($db, intval($_GET['id']));

?>
    <link rel="stylesheet" href="../CSS/restaurant.css" type="text/css">
    <script src= "../js/restaurant.js" defer></script>
    <div class="main">
        <div class="restaurant-container">
            <div class="food-types">
                <?php foreach($restaurant->categories as $category) {?>
                    <span class="category"><i class="fab fa-envira nonselected"></i><?=$category?></span>
                <?php } ?>
            </div>
            <div class="title-rating">
                <span class="rest-title"><?=$restaurant->name?></span>
                <span class="rating"><?=Review::getAvgRatingbyRestID($db,intval($_GET['id']))?></span>
            </div>
            <div class="address">
                <i style="color: var(--light-red)" class="fas fa-map-marker-alt"></i><?=$restaurant->address->street?> <span class="area-code"><?=$restaurant->address->postalCode?></span>, <span class="city"><?=$restaurant->address->city?></span>
            </div>
            <div class="avg-price">Preço Médio: <span class="price"><?=$restaurant->averagePrice?></span>€</div>
            <img id="restaurant-pic" src="../<?=$restaurant->image->path ?>?>" alt="">

            <div class="restaurant-topics">
                <a id="abouts" class="restaurant-descriptors selected-descriptor about" href="javascript:void(0)">About us</a>
                <a id="meals" class="restaurant-descriptors menu-meal" href="#meal-types">Menu</a>
            </div>
            <div id="restaurant-description">
                <div id="description" class="descriptor">
                    Venha-nos conhecer na <?=$restaurant->address->street?>. <br>
                    Servimos comida 
                    
                    <?php foreach($restaurant->categories as $category) {?>
                        <span class="category-wo-style"><?=$category?></span>
                    <?php } ?>
                    Esperamos ve-lo cá!
                </div>
                <div id="menu-items" class="descriptor nonselected">
                    <a href="" id="meal-types"></a>
                    <div class="meals">
                        <div class="main-course"> <p> Prato Principal</p>
                            <ul>
                                <?php $items = MenuItem::getMenuItembyRestID($db, intval($_GET['id']));
                                        
                                foreach($items as $item) {
                                    if(in_array("comida",$item->categories)) {?>
                                        <li class="menu-item">
                                            <span class="item-name"><?=$item->name?></span>
                                            <span class="item-price"><?=$item->price?></span>
                                            <button class="add-item" onclick="addToOrder(this)"><i class="fas fa-plus"></i></button>
                                        </li>
                                <?php } }?>
                            </ul>
                        </div>
                        <div class="drinks"> <p> Drinks</p>
                            <ul>
                            <?php
                                foreach($items as $item) {
                                    if(in_array("bebida",$item->categories)) {?>
                                        <li class="menu-item">
                                            <span class="item-name"><?=$item->name?></span>
                                            <span class="item-price"><?=$item->price?></span>
                                            <button class="add-item" onclick="addToOrder(this)"><i class="fas fa-plus"></i></button>
                                        </li>
                                <?php } }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reviews">
                <a href="" id="reviews"></a>
                <div class="title" style="font-size: 1.6em; border-bottom: 3px solid var(--light-red);">Reviews</div>
                <div class="review-section">
                    <?php foreach($reviews as $review) { ?>
                    <div class="review-box">
                        <div class="review">
                            <div class="user">
                                <img class="profile-pic" src="../<?=$review->user->image->path?>" alt="">
                                <div class="user-name"><?=$review->user->username ?></div>
                            </div>
                            <div class="comment"><?=$review->text?></div>
                            <div class="review-score"><?=$review->rating?></div>
                        </div>
                        <!-- acrescentar condiçao pa ver se ja tem ou n replies esta review -->
                        <?php if($comments = Comment::getCommentsbyReview($db, $review->reviewID)){
                            foreach($comments as $comment){?>
                        <div class="reply">
                            <div class="user">
                                <img class="profile-pic" src="../<?=$comment->user->image->path?>" alt="">
                                <div class="user-name"><?=$comment->user->username?></div>
                            </div>
                            <div class="comment"><?=$comment->text?></div>
                        </div>
                        <?php } } ?>
                        <form action="../actions/action_create_reply.php?id=<?=$review->reviewID?>" method="POST" class="reply-section">
                            <?php if($session->isLoggedIn()) {?>
                            <div class="comment-section-and-button">
                                <textarea class="comment-section nonselected" name="commentText" placeholder="Não recomendo..." id="" cols="30" rows="10"></textarea>
                                <button onclick="replying(this)" type="button" class="reply-button">Reply</button>
                            </div>
                            <button class="submit-comment nonselected">Submit</button>
                            <?php }?>
                        </form>
                    </div>
                    <?php } ?>
                    <?php if($session->isLoggedIn()) {?>
                    <form action="../actions/action_create_review.php?id=<?=$_GET['id']?>" id="add-review" method="POST">
                        <div class="add-review-title">Review your own experience!</div>
                        <div class="new-review-section">
                            <div id="new-rating" class="nonselected">5</div>
                            <input oninput="this.previousElementSibling.textContent = this.value" id="review-score" name="reviewScore" class="review-rating nonselected" type="range" min="0" max="10">
                            <textarea form="add-review" name="reviewText" class="comment-section nonselected" placeholder="Leave a review..." id="" cols="30" rows="10"></textarea>
                            <button onclick="reviewing(this)" type="button" class="reply-button">Add review</button>
                        </div>
                        <button style="float: right" class="submit-comment nonselected">Submit</button>
                    </form>
                    <?php }?>
                </div>
            </div>

        </div>
        <div class="button-container">
            <a style="width: max-content;" href="#reviews">
                <button id="review" class="action-buttons" type="submit"> Review</button>
            </a><br>
            <button id="order-now" class="action-buttons" type="submit">Carrinho 🏎</button>
            <div class="order-menu nonselected">
                <h1>Pedido</h1>
                <form id="order-list" method="post" action="../actions/action_order.php?restID=<?=$restaurant->restaurantID?>">
                    <div>
                        <input type="text" name="item[]" class="item-name-inorder" disabled value="Guilty Salad">
                        <input type="number" name="quantity[]" class="quantity" value="1" disabled>
                        <input type="number" name="price[]" class="item-price-inorder" value="11" disabled>
                        <button type="button" class="remove-quantity"><i class="fas fa-minus" aria-hidden="true"></i></button>
                        <button type="button" ><i class="fas fa-plus" aria-hidden="true"></i></button>
                    </div>
                    <div>
                        <input type="text" name="item[]" class="item-name-inorder" disabled value="Salada Ucraniana">
                        <input type="number" name="quantity[]" class="quantity" value="2" disabled>
                        <input type="number" name="price[]" class="item-price-inorder" value="16" disabled>
                        <button type="button" class="remove-quantity"><i class="fas fa-minus" aria-hidden="true"></i></button>
                        <button type="button" ><i class="fas fa-plus" aria-hidden="true"></i></button>
                    </div>
                    <button id="buy-order">Go</button>
                </form>

                <!--<button id="buy-order">Pay up</button>-->
            </div>
        </div>
    </div>
<?php
    drawFooter();
?>