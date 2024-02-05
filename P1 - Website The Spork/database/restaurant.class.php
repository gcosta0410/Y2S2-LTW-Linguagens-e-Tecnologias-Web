<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/user.class.php');
  require_once(__DIR__.'/address.class.php');
  require_once(__DIR__.'/menuItem.class.php');

  class Restaurant{

    public int $restaurantID;
    public string $name;
    public float $averageRating;
    public array $categories;
    public array $priceRange;
    public User $owner;
    public Address $address;
    public Image $image;
    public float $averagePrice;

    public function __construct(PDO $db, int $restaurantID, string $name, float $averageRating, array $categories, array $priceRange, string $ownerUsername, int $addressID, int $imageID){
      $this->restaurantID = $restaurantID;
      $this->name = $name;
      $this->averageRating = $averageRating;
      $this->categories = $categories;
      $this->priceRange = $priceRange;
      $this->owner = User::getUserByUsername($db, $ownerUsername);
      $this->address = Address::getAddressByID($db, $addressID);
      $this->image = Image::getImageByID($db, $imageID);
      $this->averagePrice = MenuItem::getAveragePricebyRestID($db, $restaurantID);
    }

    static function getRestaurantByID(PDO $db, int $restaurantID) : ?Restaurant {
          
      $stmt = $db->prepare('
        SELECT restaurantID, name, averageRating, categories, priceRange, username, addressID, imageID
        FROM Restaurant 
        WHERE restaurantID = ?
      ');

      $stmt->execute(array($restaurantID));
      
      if ($restaurant = $stmt->fetch()) {

        $categories = explode(',',$restaurant['categories']);
        $priceRange = explode(',',$restaurant['priceRange']);
        
        return new Restaurant(
            $db,
            intval($restaurant['restaurantID']),
            $restaurant['name'],
            floatval($restaurant['averageRating']),
            $categories,
            $priceRange,
            $restaurant['username'],
            intval($restaurant['addressID']),
            intval($restaurant['imageID'])
        );
      } else return null;
    }

    static function getNRestaurants(PDO $db, int $number_of_restaurants) : ?array{
        $stmt = $db->prepare('
            SELECT restaurantID, name, averageRating, categories, priceRange, username, addressID, imageID
            FROM Restaurant
            LIMIT ?
        ');

        $stmt->execute(array($number_of_restaurants)); //array()

        $restaurants = array();
        while($restaurant = $stmt->fetch()){
            $categories = explode(',',$restaurant['categories']);
            $priceRange = explode(',',$restaurant['priceRange']);

            $restaurant1 = new Restaurant(
                $db,
                intval($restaurant['restaurantID']),
                $restaurant['name'],
                floatval($restaurant['averageRating']),
                $categories,
                $priceRange,
                $restaurant['username'],
                intval($restaurant['addressID']),
                intval($restaurant['imageID'])
            );

            $restaurants[] = $restaurant1;
        }
        return $restaurants;
    }

    static function searchRestaurants(PDO $db, string $search, int $count) : array{

        if (ctype_space($search) || $search == '') {
            return array();
        }

        $stmt = $db->prepare('SELECT restaurantID, name, averageRating, categories, priceRange, username, addressID, imageID 
                                    FROM Restaurant 
                                    WHERE name LIKE ? 
                                    LIMIT ?');
        $stmt->execute(array('%' . $search . '%', $count));

        $restaurants = array();
        while ($restaurant = $stmt->fetch()) {
            $categories = explode(',',$restaurant['categories']);
            $priceRange = explode(',',$restaurant['priceRange']);
            $restaurants[] = new Restaurant(
                $db,
                intval($restaurant['restaurantID']),
                $restaurant['name'],
                floatval($restaurant['averageRating']),
                $categories,
                $priceRange,
                $restaurant['username'],
                intval($restaurant['addressID']),
                intval($restaurant['imageID'])
            );
        }

        return $restaurants;
    }

      public static function getRestaurantsByOwnerUsername(PDO $db, string $username) : array{

          $stmt = $db->prepare('SELECT restaurantID, name, averageRating, categories, priceRange, username, addressID, imageID 
                                    FROM Restaurant 
                                    WHERE username = ?');
          $stmt->execute(array($username));

          $restaurants = array();
          while ($restaurant = $stmt->fetch()) {
              $categories = explode(',',$restaurant['categories']);
              $priceRange = explode(',',$restaurant['priceRange']);
              $restaurants[] = new Restaurant(
                  $db,
                  intval($restaurant['restaurantID']),
                  $restaurant['name'],
                  floatval($restaurant['averageRating']),
                  $categories,
                  $priceRange,
                  $restaurant['username'],
                  intval($restaurant['addressID']),
                  intval($restaurant['imageID'])
              );
          }

          return $restaurants;
      }

      function delete($db){
          $stmt = $db->prepare('
                DELETE FROM Restaurant 
                WHERE  restaurantID = ?
            ');
          $stmt->execute(array($this->restaurantID));
      }

      function save($db) {
          $stmt = $db->prepare('
                UPDATE Restaurant SET name = ?, categories = ?, addressID = ?, imageID = ?
                WHERE restaurantID = ?
            ');

          $categories = implode(',',$this->categories);

          $stmt->execute(array($this->name, $categories, $this->address->id, $this->image->id, $this->restaurantID));
      }

      static function getLastID(PDO $db) : int{
          $stmt = $db->prepare('
                SELECT restaurantID 
                FROM Restaurant
                ORDER BY restaurantID DESC
                LIMIT 1
            ');

          $stmt->execute();

          return intval($stmt->fetch()['restaurantID']);
      }

      function saveNew($db) {
          $stmt = $db->prepare('
                INSERT INTO Restaurant(name, averageRating, categories, priceRange, username, addressID, imageID) 
                VALUES(?, ?, ?, ?, ?, ?, ?)
            ');

          $categories = implode(',',$this->categories);
          $stmt->execute(array($this->name, $this->averageRating, $categories, $this->priceRange, $this->owner->username, $this->address->id, $this->image->id));
      }

  }
