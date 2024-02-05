<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');

    $db = getDatabaseConnection();

    $user = User::getUserByUsername($db, $session->getUsername());

    if($user == User::getUserWithPassword($db, $session->getUsername(), $_POST['delete-pwd'])) {
        $user->delete($db);
        unset($user);
        $session->addMessage("Deleted", "User deleted successfully");
        $session->logout();
    }
    else{
        $session->addMessage("failed", "Passwords did not match");
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();