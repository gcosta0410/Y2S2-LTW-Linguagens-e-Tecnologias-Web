<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/user.class.php');

    $db = getDatabaseConnection();

    if(User::getUserByUsername($db, $_POST["username"])){
        $usernameError = "Username already taken";
        $session->addMessage("registerError", "Username already taken");
    }
    else if(User::getUserByEmail($db, $_POST["email"])){
        $emailError = "email already registered";
        $session->addMessage("registerError", "Email already registered");
    }
    else if(User::getUserByPhoneNO($db, $_POST["countryCode"].$_POST["telephoneNr"])){
        $phoneNOError = "phone number already registered";
        $session->addMessage("registerError", "Phone number already registered");
    }
    else{
        $options = ['cost' => 12];
        $stmt = $db->prepare("INSERT INTO User VALUES (?, ?, ?, ?, ?, 1, 1)");
        $stmt->execute(array($_POST["username"], password_hash($_POST["pwd1"], PASSWORD_DEFAULT, $options), $_POST["full_name"], $_POST["countryCode"].$_POST["telephoneNr"], $_POST["email"]));
        $session->setUsername($_POST["username"]);
        $session->setName($_POST["full_name"]);
        $session->setPhoneNo($_POST["countryCode"].$_POST["telephoneNr"]);
        $session->setEmail($_POST["email"]);
        $session->setImagePath(Image::getImageByID($db, 1)->path);
        $session->addMessage('success', 'Register successful!');

        header('Location: ../pages/address.php');
        die();

    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();