<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__. '/../templates/common.tpl.php');
    require_once(__DIR__. '/../templates/profile.tpl.php');
    require_once(__DIR__. '/../database/user.class.php');


    $db = getDatabaseConnection();

    if (!$session->isLoggedIn()){
        header('Location:index.php');
        die();
    }

    if (!$session->isSetup()){
        header('Location:address.php');
        die();
    }

    $restaurant = Restaurant::getRestaurantByID($db, intval($_GET['id']));

    if($session->getUsername() != $restaurant->owner->username){
        header('Location:index.php');
        die();
    }

    $user = User::getUserByUsername($db, $session->getUsername());

    drawHeader($session);
?>
<script src= "../js/newRestaurant.js" defer></script>
<link rel="stylesheet" href="../CSS/newRestaurant.css">
<link rel="stylesheet" href="../CSS/profile.css">

<div id="create-title">Update your restaurant:</div>

<form id="main-container" action="../actions/action_update_restaurant.php?restID=<?=$restaurant->restaurantID?>" method="post" enctype="multipart/form-data">
    <div class="newRestInfo content-container">
        <div class="newrestaurant-title container-title">Restaurant info</div>
        <div class="restaurant-info">
            <div class="restaurant-setting">
                <label for="restName">Name</label>
                <input id="restName" name="restName" class="setting-input" required type="text" value="<?= $restaurant->name?>">
            </div>
            <div>
                <label for="street">Street</label>
                <input id="street" name="street" class="setting-input" required type="text" value="<?= $restaurant->address->street?>">
            </div>
            <div>
                <label for="city">City</label>
                <input id="city" name="city" class="setting-input" required type="text" value="<?= $restaurant->address->city?>">
            </div>
            <div>
                <label for="state">State</label>
                <input id="state" name="state" class="setting-input" required type="text" value="<?= $restaurant->address->state?>">
            </div>
            <div>
                <label for="country">Country</label>
                <input id="country" name="country" class="setting-input" required type="text" value="<?= $restaurant->address->country?>">
            </div>
            <div>
                <label for="postal">Postal code</label>
                <input id="postal" name="postal" class="setting-input" required type="text" value="<?= $restaurant->address->postalCode?>">
            </div>
            <div>
                <label for="categories">Categories</label>
                <input id="categories" name="categories" class="setting-input" required type="text" value="<?php foreach($restaurant->categories as $category) {echo $category.' ';}?>">
            </div>
            <div>
                <label for="restImage">Restaurant image</label>
                <input id="restImage" name="restImage" class="setting-input" type="file" accept=".jpg, .jpeg, .png">
            </div>
        </div>
        <button type="submit" class="submit-user-changes">Update restaurant</button>
    </div>
    <div class="newMenu content-container">
        <div class="titleandbutton">
            <div class="container-title">Menu</div>
            <button id="add-item" type="button" onclick="newMenuItem()"><i class="fas fa-plus"></i> Add Item</button>
        </div>
        <table id="menuTable">
            <thead>
            <tr>
                <th></th>
                <th>Product name</th>
                <th>Price</th>
                <th>Type</th>
                <th>Categories</th>
                <th>Allergens</th>
                <th>Image</th>
            </tr>
            </thead>
            <tbody>
                <?php $items = MenuItem::getMenuItembyRestID($db, intval($_GET['id']));
                    foreach($items as $item) {
                        if(in_array("comida",$item->categories)) {?>
                            <tr>
                                <td><button onclick="remMenuItem(this)" type="button" class="minus"><i class="fas fa-minus"></i></button></td>
                                <td><input type="text" name="dish[]" required value="<?= $item->name?>"></td>
                                <td><input type="number" step="0.01" name="price[]" required value="<?= $item->price?>">€</td>
                                <td>
                                    <select name="type[]">
                                        <option selected>comida</option>
                                        <option>bebida</option>
                                    </select>
                                </td>
                                <td><input type="text" name="categories2[]" required value="<?=implode(' ', $item->categories); ?>"></td>
                                <td><input type="text" name="allergens[]" value=""></td>
                                <td class="img-input"><i class="fas fa-image"></i><input name="item-img[]" class="item-img" type="file" accept=".jpg, .jpeg, .png"></td>
                            </tr>
                        <?php }
                        if(in_array("bebida",$item->categories)) {?>
                            <tr>
                                <td><button onclick="remMenuItem(this)" type="button" class="minus"><i class="fas fa-minus"></i></button></td>
                                <td><input type="text" name="dish[]" required value="<?= $item->name?>"></td>
                                <td><input type="number" step="0.01" name="price[]" required value="<?= $item->price?>">€</td>
                                <td>
                                    <select name="type[]">
                                        <option>comida</option>
                                        <option selected>bebida</option>
                                    </select>
                                </td>
                                <td><input type="text" name="categories2[]" required value="<?=implode(' ', $item->categories) ?>"></td>
                                <td><input type="text" name="allergens[]" value="<?=implode(' ', $item->allergens) ?>"></td>
                                <td class="img-input"><i class="fas fa-image"></i><input name="item-img[]" class="item-img" type="file" accept=".jpg, .jpeg, .png"></td>
                            </tr>
                        <?php }
                    }?>
            </tbody>
        </table>
    </div>
</form>
<?php
    drawFooter();
?>
