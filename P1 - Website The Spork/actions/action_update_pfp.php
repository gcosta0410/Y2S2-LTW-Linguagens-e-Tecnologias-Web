<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/index.php');
        die();
    }

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/image.class.php');
    require_once(__DIR__ . '/../database/user.class.php');


    $db = getDatabaseConnection();

    $imgPath = 'docs/profiles/' . $session->getUsername() . '.png';

    $image = Image::getImageByID($db, User::getUserByUsername($db, $session->getUsername())->image->id);

    if(file_exists($_FILES['pfp']['tmp_name']) && is_uploaded_file($_FILES['pfp']['tmp_name'])){
        move_uploaded_file($_FILES['pfp']['tmp_name'], '../'.$imgPath);
    }
    $image->path=$imgPath;
    $image->save($db);

    header('Location: ../pages/profile.php');
    die();