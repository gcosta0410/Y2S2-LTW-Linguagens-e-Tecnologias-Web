<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $db = getDatabaseConnection();

  $user = User::getUserWithPassword($db, $_POST['email-user'], $_POST['pwd']);

  if ($user) {
    $session->setUsername($user->username);
    $session->setName($user->fullName);
    $session->setPhoneNo($user->phoneNo);
    $session->setEmail($user->email);
    $session->setImagePath($user->image->path);
    $session->setAddressID($user->address->id);
    $session->addMessage('success', 'Login successful!');
    if($session->getAddressID() != 1)
        $session->doneSetup();
  } else {
    $session->addMessage('loginError', 'Wrong credentials!');
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);
  die();