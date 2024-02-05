<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');

    $db = getDatabaseConnection();

    $restaurants = Restaurant::searchRestaurants($db, $_GET['search'], 8);
    echo json_encode($restaurants);
