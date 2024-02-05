<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/index.php');
        die();
    }

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');

    $db = getDatabaseConnection();

    $user = User::getUserByUsername($db, $session->getUsername());

    if ($user) {
        $usernameAlreadyExists = User::getUserByUsername($db, $_POST['username']);
        $emailAlreadyExists = User::getUserByEmail($db, $_POST['email-address']);
        $phoneAlreadyExits = User::getUserByPhoneNO($db, $_POST['phone-no']);

        if ($usernameAlreadyExists != $user) {
            $session->addMessage("failed", "Username already taken!");
        } else if ($emailAlreadyExists != $user) {
            $session->addMessage("failed", "Email already registered!");
        } else if ($phoneAlreadyExits != $user) {
            $session->addMessage("failed", "Phone number already registered!");
        } else {
            $oldUsername = $user->username;
            $user->username = $_POST['username'];
            $user->fullName = $_POST['full-name'];
            $user->phoneNo = $_POST['phone-no'];
            $user->email = $_POST['email-address'];
            $user->address = Address::getAddressByID($db, $session->getAddressID());
            $user->address->street = $_POST['street'];
            $user->address->city = $_POST['city'];
            $user->address->state = $_POST['state'];
            $user->address->country = $_POST['country'];
            $user->address->postalCode = $_POST['postal'];
            $user->address->save($db);

            $user->save($db, $oldUsername);

            $session->setUsername($user->username);
            $session->setName($user->fullName);
            $session->setPhoneNo($user->phoneNo);
            $session->setEmail($user->email);
            $session->setImagePath($user->image->path);
            $session->setAddressID($user->address->id);
        }
    }

    header('Location: ../pages/profile.php');
    die();
