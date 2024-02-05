<?php
    declare(strict_types = 1);
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    if (!$session->isLoggedIn()) {
        header('Location: ../pages/index.php');
        die();
    }
    require_once(__DIR__ . '/../database/connection.php');
    require_once(__DIR__ . '/../database/address.class.php');
    require_once(__DIR__ . '/../database/review.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');

    $db = getDatabaseConnection();
    $review = new Comment(
        $db,
        Comment::getLastID($db)+1,
        $_POST['commentText'],
        date("Y-m-d"),
        intval($_GET['id']),
        $session->getUsername()
    );
    $review->saveNew($db);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();