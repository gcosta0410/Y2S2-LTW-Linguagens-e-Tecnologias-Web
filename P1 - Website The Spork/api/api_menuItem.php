<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/menuItem.class.php');

    $db = getDatabaseConnection();

    $items = MenuItem::searchMenuItems($db, $_GET['search'], 8);

    echo json_encode($items);
