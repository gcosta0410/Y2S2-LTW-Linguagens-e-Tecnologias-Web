<?php
    declare(strict_types = 1);
    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();
    if (!$session->isLoggedIn()) {
        header('Location: ../pages/index.php');
        die();
    }

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/order.class.php');
    require_once(__DIR__ . '/../database/quantity.class.php');


    $db = getDatabaseConnection();

    /*$price = 0;
    //var_dump($_POST['price'][0]);
    for($i = 0; $i < count($_POST["price"]); $i++){
        $price += floatval($_POST["price"][$i]);
    }

    //var_dump($price);*/

    $price = 1;

    $order = new Order(
        $db,
        intval(Order::getLastID($db) +1),
        "requested",
        floatval($price),
        date("Y/m/d"),
        $session->getUsername(),
        intval($_GET['restID'])
    );
    $order->saveNew($db);

    for($i = 0; $i < count($_POST['price']); $i++){
        $itemID = MenuItem::getIDfromRestItem($db, $_GET['restID'], $_POST['item'][$i]);
        $quantity = new Quantity($db, intval($order->orderID),intval($itemID), floatval($_POST['price'][$i]), intval($_POST['quantity'][$i]));
        $quantity->saveNew($db);
    }


    header('Location: ' . $_SERVER['HTTP_REFERER']);
   die();