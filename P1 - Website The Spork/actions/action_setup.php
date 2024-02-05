<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/address.class.php');


    $db = getDatabaseConnection();

    $id = Address::getLastID($db) +1;
    $address = new Address($id, $_POST['street'], $_POST['city'], $_POST['state'], $_POST['postal-code'], $_POST['country']);
    $address->saveNew($db);
    User::changeUserAddress($db, $session->getUsername(), $id);
    $session->setAddressID($address->id);

    $session->doneSetup();

    header('Location: ../pages/index.php');
    die();