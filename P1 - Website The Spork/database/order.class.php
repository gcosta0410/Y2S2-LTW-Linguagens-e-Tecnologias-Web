<?php 
    declare(strict_types = 1);
    require_once(__DIR__.'/restaurant.class.php');
    require_once(__DIR__.'/user.class.php');

    class Order{
        public int $orderID;
        public string $deliveryStatus;
        public float $price;
        public string $orderDate;
        public User $user;
        public Restaurant $restaurant;

        public function __construct(PDO $db, int $orderID, string $deliveryStatus, float $price,  string $orderDate, string $username, int $restID)
        {
            $this->orderID = $orderID;
            $this->deliveryStatus = $deliveryStatus;
            $this->price = $price;
            $this->orderDate = $orderDate;
            $this->user = User::getUserByUsername($db, $username);
            $this->restaurant = Restaurant::getRestaurantByID($db, $restID);
        }

        static function getOrdersByUsername(PDO $db, string $username) : ?array{
            $stmt = $db->prepare('
                select * from Pedido where username = ?;
            ');
            $stmt->execute(array($username)); //array()
            
            $orders = array();

            while($order = $stmt->fetch()){

                $order1 = new Order(
                    $db,
                    intval($order['pedidoID']),
                    $order['deliveryStatus'],
                    floatval($order['price']),
                    $order['orderDate'],
                    $order['username'],
                    intval($order['restaurantID'])
                );

                $orders[] = $order1;
            }
            return $orders;
        }

        static function getLastID(PDO $db) : int{
            $stmt = $db->prepare('
                SELECT pedidoID
                FROM Pedido
                ORDER BY pedidoID DESC
                LIMIT 1
            ');

            $stmt->execute();

            return intval($stmt->fetch()['pedidoID']);
        }

        function saveNew(PDO $db) {
            $stmt = $db->prepare('
                INSERT INTO Pedido(pedidoID, deliveryStatus, price, orderDate, username, restaurantID)
                VALUES(?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute(array($this->orderID, $this->deliveryStatus, $this->price, $this->orderDate, $this->user->username, $this->restaurant->restaurantID));
        }

        function save(PDO $db) {
            $stmt = $db->prepare('
                UPDATE Pedido SET deliveryStatus = ?
                WHERE username = ? and restaurantID = ?
            ');
            $stmt->execute(array($this->deliveryStatus, $this->user->username, $this->restaurant->restaurantID));

        }

        static function getOrdersByRestID(PDO $db, int $restID) : ?array{
            $stmt = $db->prepare('
                select * from Pedido where restaurantID = ?;
            ');
            $stmt->execute(array($restID)); //array()

            $orders = array();

            while($order = $stmt->fetch()){

                $order1 = new Order(
                    $db,
                    intval($order['pedidoID']),
                    $order['deliveryStatus'],
                    floatval($order['price']),
                    $order['orderDate'],
                    $order['username'],
                    intval($order['restaurantID'])
                );

                $orders[] = $order1;
            }
            return $orders;
        }


    }