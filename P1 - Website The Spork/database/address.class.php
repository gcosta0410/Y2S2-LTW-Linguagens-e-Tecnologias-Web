<?php
  declare(strict_types = 1);

  class Address {
        public int $id;
        public string $street;
        public string $city;
        public string $state;
        public string $postalCode;
        public string $country;

        public function __construct(int $id, string $street, string $city, string $state, string $postalCode, string $country){
            $this->id = $id;
            $this->street = $street;
            $this->city = $city;
            $this->state = $state;
            $this->postalCode = $postalCode;
            $this->country = $country;
        }

        static function getAddressByID(PDO $db, int $id) : ?Address {
            $stmt = $db->prepare('
              SELECT addressID, street, city, state, postalCode, country
              FROM Address 
              WHERE addressID = ?
            ');

            $stmt->execute(array($id));

            if ($address = $stmt->fetch()) {
                return new Address(
                  intval($address['addressID']),
                  $address['street'],
                  $address['city'],
                  $address['state'],
                  $address['postalCode'],
                  $address['country']
                );
            } else return null;
        }

        static function getLastID(PDO $db) : int{
            $stmt = $db->prepare('
                SELECT addressID 
                FROM Address
                ORDER BY addressID DESC
                LIMIT 1
            ');

            $stmt->execute();

            return intval($stmt->fetch()['addressID']);
        }

      function saveNew($db) {
          $stmt = $db->prepare('
                INSERT INTO Address(street, city, state, postalCode, country) 
                VALUES(?,?,?,?,?)
            ');

          $stmt->execute(array($this->street, $this->city, $this->state, $this->postalCode, $this->country));
      }

      function save($db) {
          $stmt = $db->prepare('
                UPDATE Address SET street = ?, city = ?, state = ?, postalCode = ?, country = ? 
                WHERE addressID = ?
            ');

          $stmt->execute(array($this->street, $this->city, $this->state, $this->postalCode, $this->country, $this->id));
      }

  }