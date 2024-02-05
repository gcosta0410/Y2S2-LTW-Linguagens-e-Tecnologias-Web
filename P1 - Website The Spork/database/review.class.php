<?php 
    declare(strict_types = 1);
    require_once(__DIR__.'/restaurant.class.php');
    require_once(__DIR__.'/user.class.php');

    class Review{
        public int $reviewID;
        public string $text;
        public int $rating;
        public string $reviewDate;
        public User $user;
        public Restaurant $restaurant;

        public function __construct(PDO $db, int $reviewID, string $text, int $rating, string $reviewDate, string $username, int $restID)
        {
            $this->reviewID = $reviewID;
            $this->text = $text;
            $this->rating = $rating;
            $this->reviewDate = $reviewDate;
            $this->user = User::getUserByUsername($db, $username);
            $this->restaurant = Restaurant::getRestaurantByID($db, $restID);
        }

        static function getReviewsbyRestID(PDO $db, int $restID) : ?array{
            $stmt = $db->prepare('
            select * from Review where restaurantID = ?;
            ');
            $stmt->execute(array($restID)); //array()
        
            $reviews = array();

            while($review = $stmt->fetch()){

                $review1 = new Review(
                    $db,
                    intval($review['reviewID']),
                    $review['text'],
                    intval($review['rating']),
                    $review['reviewDate'],
                    $review['username'],
                    intval($review['restaurantID'])
                );

                $reviews[] = $review1;
            }
            return $reviews;
        }
        static function getReviewbyReviewID(PDO $db, int $reviewID){
            $stmt = $db->prepare('
            select * from Review where reviewID = ?;
            ');
            $stmt->execute(array($reviewID));

            if ($review = $stmt->fetch()) {
                return new Review(
                    $db,
                    intval($review['reviewID']),
                    $review['text'],
                    intval($review['rating']),
                    $review['reviewDate'],
                    $review['username'],
                    intval($review['restaurantID'])
                );

            } else return null;
        }
        
        static function getAvgRatingbyRestID(PDO $db, int $restID) : ?float{
            $stmt = $db->prepare('
            select avg(rating) as Rating from Review where restaurantID = ?;
            ');
            $stmt->execute(array($restID)); //array()

            $rating = $stmt->fetch();

            return $rating ? round($rating['Rating'],1) : 0 ;
        }

        function saveNew($db){
            $stmt = $db->prepare('
                INSERT INTO Review(text, rating, reviewDate, username, restaurantID) 
                VALUES(?,?,?,?,?)
            ');

            $stmt->execute(array($this->text,$this->rating,$this->reviewDate,$this->user->username,$this->restaurant->restaurantID));
        }

        static function getLastID(PDO $db) : int{
            $stmt = $db->prepare('
                  SELECT reviewID 
                  FROM Review
                  ORDER BY reviewID DESC
                  LIMIT 1
              ');
  
            $stmt->execute();
  
            return intval($stmt->fetch()['reviewID']);
        }
        
    }