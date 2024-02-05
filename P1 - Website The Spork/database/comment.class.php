<?php 
    declare(strict_types = 1);
    require_once(__DIR__.'/restaurant.class.php');
    require_once(__DIR__.'/user.class.php');
    require_once(__DIR__.'/review.class.php');


    class Comment{
        public int $commentID;
        public string $text;
        public string $commentDate;
        public Review $review;
        public User $user;

        public function __construct(PDO $db, int $commentID, string $text, string $commentDate, int $reviewID, string $username)
        {
            $this->commentID = $commentID;
            $this->text = $text;
            $this->commentDate = $commentDate;
            $this->review = Review::getReviewbyReviewID($db, $reviewID);
            $this->user = User::getUserByUsername($db, $username);
        }

        static function getCommentsbyReview(PDO $db, int $reviewID) : ?array{
            $stmt = $db->prepare('
            select * from Comment where reviewID = ?;
            ');
            $stmt->execute(array($reviewID)); //array()
        
            $comments = array();

            while($comment = $stmt->fetch()){
                
                $comment1 = new Comment(
                    $db,
                    intval($comment['commentID']),
                    $comment['text'],
                    $comment['commentDate'],
                    intval($comment['reviewID']),
                    $comment['username'],
                );
                $comments[] = $comment1;

            }
            return $comments;
        }

        function saveNew($db){
            $stmt = $db->prepare('
                INSERT INTO Comment(text, commentDate, reviewID, username) 
                VALUES(?,?,?,?)
            ');

            $stmt->execute(array($this->text,$this->commentDate,$this->review->reviewID,$this->user->username));
        }

        static function getLastID(PDO $db) : int{
            $stmt = $db->prepare('
                  SELECT commentID 
                  FROM Comment
                  ORDER BY commentID DESC
                  LIMIT 1
              ');
  
            $stmt->execute();
  
            return intval($stmt->fetch()['commentID']);
        }

    }