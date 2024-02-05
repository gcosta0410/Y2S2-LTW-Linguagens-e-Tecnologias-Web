<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/profile.php');
        die();
    }

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');

    $db = getDatabaseConnection();

    $restaurant = Restaurant::getRestaurantByID($db, intval($_GET['restID']));

    if($restaurant) {

        $restaurant->name = $_POST['restName'];
        $restaurant->address = Address::getAddressByID($db, $session->getAddressID());
        $restaurant->address->street = $_POST['street'];
        $restaurant->address->city = $_POST['city'];
        $restaurant->address->state = $_POST['state'];
        $restaurant->address->country = $_POST['country'];
        $restaurant->address->postalCode = $_POST['postal'];;
        $restaurant->address->save($db);
        $restaurant->categories = explode(' ', $_POST['categories']);

        $restaurant->save($db);

        $imgPath = 'docs/restaurants/' . strval(Restaurant::getLastID($db)+1) . '.png';

        $image = Image::getImageByID($db, $restaurant->image->id);

        if(file_exists($_FILES['restImage']['tmp_name']) && is_uploaded_file($_FILES['restImage']['tmp_name'])){
            move_uploaded_file($_FILES['restImage']['tmp_name'], '../'.$imgPath);
        }
        $image->path=$imgPath;
        $image->save($db);

        MenuItem::deleteAllFromRestaurant($db, $restaurant->restaurantID);

        for($i = 0; $i < count($_POST["dish"]); $i++){
            $itemCategories = explode(" ", $_POST['categories2'][$i]);
            if($_POST['type'][$i] == 'comida'){
                $itemCategories[] = 'comida';
            }else{
                $itemCategories[] = 'bebida';
            }
            $menuItem = new MenuItem(MenuItem::getLastID($db)+1,
                $_POST['dish'][$i],
                floatval($_POST['price'][$i]),
                $itemCategories,
                explode(" ", $_POST['allergens'][$i]),
                Image::getImageByID($db, 1),
                $restaurant);
            $menuItem->saveNew($db);
        }

    }
    header('Location: ../pages/profile.php');
    die();



