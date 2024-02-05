<?php
    declare(strict_types = 1);
    require_once(__DIR__.'/restaurant.class.php');
    require_once(__DIR__.'/user.class.php');

    class Quantity{
        public int $orderID;
        public int $itemID;
        public float $totalPrice;
        public int $quantity;

        public function __construct(PDO $db, int $orderID, int $itemID, float $totalPrice, int $quantity)
        {
            $this->orderID = $orderID;
            $this->itemID = $itemID;
            $this->totalPrice = $totalPrice;
            $this->quantity = $quantity;
        }

        function saveNew(PDO $db) {
            $stmt = $db->prepare('
                    INSERT INTO Quantity(quantity, totalPrice, pedidoID, itemID)
                    VALUES(?, ?, ?, ?)
                ');

            $stmt->execute(array($this->quantity, $this->totalPrice, $this->orderID, $this->itemID));
        }

    }