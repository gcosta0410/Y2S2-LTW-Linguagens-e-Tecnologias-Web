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

    $user = User::getUserByUsername($db, $session->getUsername());
    $orders = Order::getOrdersByUsername($db, $session->getUsername());

    drawHeader($session);
    drawProfile($session, $user, $orders);
    drawFooter();
