<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');
    require_once(__DIR__ . '/../database/review.class.php');



$db = getDatabaseConnection();

    if ($session->isLoggedIn() && !$session->isSetup()){
        header('Location:address.php');
        die();
    }

    drawHeader($session);
?>
<script src="../js/utils.js" defer></script>
<div class="intro-banner bg1">
    <div class="welcome-message">
        <span class="title">Your Favourite <span class="title orange_title">Foods</span> in One <span class="title orange_title">Place.</span></span>
        <p class="plain_text">Find the best restaurants that deliver. Get contactless delivery for restaurant takeout and more! Order food online and review it.</p>
        <a style="width: max-content;" href="#top-picks">
            <button class="action-buttons" id="order-now" type="submit">Order Now</button>
        </a>
        <!--<button id="review" type="submit">Review</button>-->
    </div>
</div>

<div class="restaurant-present">
    <div class="categories">
        <div class="category-container">
            <a href="" id="top-picks"></a>
            <p class="category-title" id="">Top Picks👀</p>
            <div id="buttons-prev-next">
                <button onclick="onHandleClick(this)" class="handle previous-items locked-button">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                </button>
                <button onclick="onHandleClick(this)" class="handle next-items">
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>
            </div>
            <div class="restaurants-list">
                <ul class="slider-container">
                    <?php foreach(Restaurant::getNRestaurants($db, 8) as $restaurant){?>
                    <li class="restaurant-card">
                        <a href="restaurant.php?id=<?= $restaurant->restaurantID?>">
                            <img class="pic-restaurant" src="../<?= $restaurant->image->path ?>" alt="">
                            <p class="title_rating"><span class="rest-title"><?= $restaurant->name ?></span><span class="rating"><?=Review::getAvgRatingbyRestID($db, $restaurant->restaurantID);?>/10</span></p>
                            <p class="average-price">Preço Médio: <span class="value"><?= $restaurant->averagePrice ?></span>€</p>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="restaurants-list"></div>
        </div>
    </div>
</div>

<?php
    drawFooter();
?>
