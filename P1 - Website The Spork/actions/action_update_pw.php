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

    $db = getDatabaseConnection();

    $user = User::getUserWithPassword($db, $session->getUsername(), $_POST['currentPwd']);

    if ($user) {
        $options = ['cost' => 12];
        $newPassword = password_hash($_POST['newPassword1'], PASSWORD_DEFAULT, $options);
        $user->changePassword($db, $newPassword);
        $session->addMessage('success', 'Password changed!');
    }
    else{
        $session->addMessage('failedPw', 'Wrong credentials!');
    }

    header('Location: ../pages/profile.php');
    die();

