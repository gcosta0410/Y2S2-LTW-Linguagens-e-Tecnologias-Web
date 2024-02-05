<?php
  declare(strict_types = 1);
  require_once(__DIR__.'/image.class.php');
  require_once(__DIR__.'/restaurant.class.php');

  class MenuItem{

    public int $itemID;
    public string $name;
    public float $price;
    public array $categories;
    public array $allergens;
    public Image $image;
    public Restaurant $restaurant;

    public function __construct(int $itemID, string $name, float $price, array $categories, array $allergens, Image $image, Restaurant $restaurant)
    {
      $this->itemID = $itemID;
      $this->name = $name;
      $this->price = $price;
      $this->categories = $categories;
      $this->allergens = $allergens;
      $this->image = $image;
      $this->restaurant = $restaurant;
    }

    static function getMenuItem(PDO $db, string $itemID) : ?MenuItem {
          
      $stmt = $db->prepare('
        SELECT itemID, name, price, categories, allergens, imageID, restaurantID
        FROM MenuItem 
        WHERE itemID = ? 
      ');

      $stmt->execute(array($itemID));
      
      if ($menuItem = $stmt->fetch()) {

        $categories = explode(',',$menuItem['categories']);
        $allergens = explode(',',$menuItem['allergens']);

        $image = Image::getImageByID($db, intval($menuItem['imageID'])); //getImageByID
        $restaurant = Restaurant::getRestaurantByID($db, intval($menuItem['restaurantID'])); //getRestaurantByID
        
        return new MenuItem(
          intval($menuItem['itemID']),
          $menuItem['name'],
          intval($menuItem['price']),
          $categories,
          $allergens,
          $image,
          $restaurant
        );
      } else return null;
    }
    static function getAveragePricebyRestID(PDO $db, int $restID) :float{
      $stmt = $db->prepare('
        SELECT avg(price) as price
        FROM MenuItem 
        WHERE restaurantID = ? and categories LIKE "%comida%"
      ');

      $stmt->execute(array($restID));

      $average = $stmt->fetch();

      return $average ? round($average['price'],2) : 0 ;
    }

    static function searchMenuItems(PDO $db, string $search, int $count) : array {
        $stmt = $db->prepare('SELECT itemID, name, price, categories, allergens, imageID, restaurantID
                                    FROM MenuItem
                                    WHERE name LIKE ?
                                    LIMIT ?');
        $stmt->execute(array('%'. $search . '%', $count));

        $items = array();
        while ($menuItem = $stmt->fetch()) {
            $categories = explode(',',$menuItem['categories']);
            $allergens = explode(',',$menuItem['allergens']);
            $image = Image::getImageByID($db, intval($menuItem['imageID'])); //getImageByID
            $restaurant = Restaurant::getRestaurantByID($db, intval($menuItem['restaurantID'])); //getRestaurantByID
            $items[] = new MenuItem(
                intval($menuItem['itemID']),
                $menuItem['name'],
                intval($menuItem['price']),
                $categories,
                $allergens,
                $image,
                $restaurant
            );
        }

        return $items;
    }

    static function getMenuItembyRestID(PDO $db, int $restID) :?array{
      $stmt = $db->prepare('
        SELECT itemID, name, price, categories, allergens, imageID
        FROM MenuItem
        WHERE restaurantID = ?
      ');

      $stmt->execute(array($restID));

      $items = array();

      while($menuItem = $stmt->fetch()){
        $categories = explode(',',$menuItem['categories']);
        if($menuItem['allergens'] === NULL){$allergens = '';}
        else {
          $allergens = explode(',',$menuItem['allergens']);
        }
        $image = Image::getImageByID($db, intval($menuItem['imageID']));
        $restaurant = Restaurant::getRestaurantByID($db, intval($restID));

        $item1 = new MenuItem(
          intval($menuItem['itemID']),
          $menuItem['name'],
          intval($menuItem['price']),
          $categories,
          array($allergens),
          $image,
          $restaurant
        );

        $items[] = $item1;
      }
      return $items;

    }

      function saveNew($db) {
          $stmt = $db->prepare('
                INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) 
                VALUES(?, ?, ?, ?, ?, ?)
            ');

          $categories = implode(',',$this->categories);
          $allergens = implode(",", $this->allergens);
          $stmt->execute(array($this->name, $this->price, $categories, $allergens, $this->image->id, $this->restaurant->restaurantID));
      }

      static function getLastID(PDO $db) : int{
          $stmt = $db->prepare('
                SELECT itemID 
                FROM MenuItem
                ORDER BY itemID DESC
                LIMIT 1
            ');

          $stmt->execute();

          return intval($stmt->fetch()['itemID']);
      }

      static function deleteAllFromRestaurant(PDO $db, int $restaurantID){
            $stmt = $db->prepare('DELETE FROM MenuItem WHERE restaurantID = ?');
            $stmt->execute(array("$restaurantID"));
      }

      function save(PDO $db) {
          $stmt = $db->prepare('
                UPDATE MenuItem SET name = ?, price = ?, categories = ?, allergens = ?, imageID = ?
                WHERE restaurantID = ?
            ');
          $stmt->execute(array($this->name, $this->price, $this->categories, $this->allergens, $this->image->id, $this->restaurant->restaurantID));

      }

      static function getIDfromRestItem(PDO $db, int $restID, string $name) : int{
          $stmt = $db->prepare('
                SELECT itemID 
                FROM MenuItem
                WHERE restaurantID = ? AND name = ?
            ');

          $stmt->execute(array($restID, $name));

          return $stmt['itemID'];
      }
  }