<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/index.php');
        die();
    }

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');
    require_once(__DIR__ . '/../database/address.class.php');
    require_once(__DIR__ . '/../database/menuItem.class.php');
    require_once(__DIR__ . '/../database/image.class.php');

    $db = getDatabaseConnection();

    $address = new Address(Address::getLastID($db) + 1,
        $_POST['street'],
        $_POST['city'],
        $_POST['state'],
        $_POST['postal'],
        $_POST['country']);

    $address->saveNew($db);

    $imgPath = 'docs/restaurants/' . strval(Restaurant::getLastID($db)+1) . '.png';

    $image = new Image(Image::getLastID($db) + 1, $imgPath);

    $image->saveNew($db);

    $restaurant = new Restaurant($db,
        Restaurant::getLastID($db)+1,
        $_POST['restName'],
        0,
        explode(" ", $_POST['categories']),
        (array)'1,10',
        $session->getUsername(),
        $address->id,
        $image->id,
    );

    $restaurant->saveNew($db);

    if(file_exists($_FILES['restImage']['tmp_name']) && is_uploaded_file($_FILES['restImage']['tmp_name'])){
        move_uploaded_file($_FILES['restImage']['tmp_name'], '../'.$imgPath);
    }


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


    header('Location: ../pages/restaurant.php?id='. $restaurant->restaurantID .'.php');
    die();