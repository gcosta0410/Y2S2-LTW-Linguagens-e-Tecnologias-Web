<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__. '/../templates/common.tpl.php');

    $db = getDatabaseConnection();

    if (!$session->isLoggedIn()){
        header('Location:index.php');
        die();
    }

    if (!$session->isSetup()){
        header('Location:address.php');
        die();
    }

    drawHeader($session);
?>

<script src= "../js/newRestaurant.js" defer></script>
<link rel="stylesheet" href="../CSS/newRestaurant.css">
<link rel="stylesheet" href="../CSS/profile.css">

<div id="create-title">Create your restaurant:</div>

<form id="main-container" action="../actions/action_create_restaurant.php" method="post" enctype="multipart/form-data">
    <div class="newRestInfo content-container">
        <div class="newrestaurant-title container-title">Restaurant info</div>
        <div class="restaurant-info">
            <div class="restaurant-setting">
                <label for="restName">Name</label>
                <input id="restName" name="restName" class="setting-input" required type="text" placeholder="Your restaurant name">
            </div>
            <div>
                <label for="street">Street</label>
                <input id="street" name="street" class="setting-input" required type="text" placeholder="Street">
            </div>
            <div>
                <label for="city">City</label>
                <input id="city" name="city" class="setting-input" required type="text" placeholder="City">
            </div>
            <div>
                <label for="state">State</label>
                <input id="state" name="state" class="setting-input" required type="text" placeholder="State">
            </div>
            <div>
                <label for="country">Country</label>
                <input id="country" name="country" class="setting-input" required type="text" placeholder="Country">
            </div>
            <div>
                <label for="postal">Postal code</label>
                <input id="postal" name="postal" class="setting-input" required type="text" placeholder="Postal-code">
            </div>
        <div>
            <label for="categories">Categories</label>
            <input id="categories" name="categories" class="setting-input" required type="text" placeholder="Categories, separated by a space">
        </div>
        <div>
            <label for="restImage">Restaurant image</label>
            <input id="restImage" name="restImage" class="setting-input" required type="file" accept=".jpg, .jpeg, .png">
        </div>
    </div>
        <button type="submit" class="submit-user-changes">Create restaurant</button>
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
            <tr>
                <td><button onclick="remMenuItem(this)" type="button" class="minus"><i class="fas fa-minus"></i></button></td>
                <td><input type="text" name="dish[]" required placeholder="Product name"></td>
                <td><input type="number" step="0.01" name="price[]" required placeholder="Price">€</td>
                <td>
                    <select name="type[]">
                        <option>comida</option>
                        <option>bebida</option>
                    </select>
                </td>
                <td><input type="text" name="categories2[]" required placeholder="Categories, separated by a space"></td>
                <td><input type="text" name="allergens[]" placeholder="Allergens, separated by a space"></td>
                <td class="img-input"><i class="fas fa-image"></i><input name="item-img[]" class="item-img" required type="file" accept=".jpg, .jpeg, .png"></td>
            </tr>
        </tbody>
    </table>
</div>
</form>
<?php drawFooter(); ?>
