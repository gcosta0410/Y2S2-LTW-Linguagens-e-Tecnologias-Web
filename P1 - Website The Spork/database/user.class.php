<?php 
    declare(strict_types = 1);
    require_once(__DIR__.'/image.class.php');
    require_once(__DIR__.'/address.class.php');

    class User{
        public string $username;
        public string $fullName;
        public string $phoneNo;
        public string $email;
        public Image $image;
        public Address $address;
    
        public function __construct(PDO $db, string $username, string $fullName, string $phoneNo, string $email, int $imageID, int $addressID){
            $this->username = $username;
            $this->fullName = $fullName;
            $this->phoneNo = $phoneNo;
            $this->email = $email;
            $this->image = Image::getImageByID($db, $imageID);
            $this->address = Address::getAddressByID($db, $addressID);
        }

        static function getUserWithPassword(PDO $db, string $username, string $password) : ?User {
            $stmt = $db->prepare('
              SELECT username, password, fullName, phoneNumber, emailAddress, imageID, addressID
              FROM User 
              WHERE username = ?
            ');


            $stmt->execute(array($username));
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
              return new User(
                $db,
                $user['username'],
                $user['fullName'],
                $user['phoneNumber'],
                $user['emailAddress'],
                intval($user['imageID']),
                intval($user['addressID'])
              );
            } else return null;
        }

        static function getUserByUsername(PDO $db, string $username) : ?User {
            $stmt = $db->prepare('
              SELECT username, password, fullName, phoneNumber, emailAddress, imageID, addressID
              FROM User 
              WHERE username = ?
            ');
      
            $stmt->execute(array($username));
            
            if ($user = $stmt->fetch()) {
              return new User(
                  $db,
                  $user['username'],
                  $user['fullName'],
                  $user['phoneNumber'],
                  $user['emailAddress'],
                  intval($user['imageID']),
                  intval($user['addressID'])
              );
            } else return null;
        }

        static function getUserByPhoneNO(PDO $db, string $phoneNO) : ?User {
            $stmt = $db->prepare('
              SELECT username, password, fullName, phoneNumber, emailAddress, imageID, addressID
              FROM User 
              WHERE phoneNumber = ?
            ');

            $stmt->execute(array($phoneNO));

            if ($user = $stmt->fetch()) {
                return new User(
                    $db,
                    $user['username'],
                    $user['fullName'],
                    $user['phoneNumber'],
                    $user['emailAddress'],
                    intval($user['imageID']),
                    intval($user['addressID'])
                );
            } else return null;
        }

        static function getUserByEmail(PDO $db, string $email) : ?User {
            $stmt = $db->prepare('
              SELECT username, password, fullName, phoneNumber, emailAddress, imageID, addressID
              FROM User 
              WHERE emailAddress = ?
            ');

            $stmt->execute(array($email));

            if ($user = $stmt->fetch()) {
                return new User(
                    $db,
                    $user['username'],
                    $user['fullName'],
                    $user['phoneNumber'],
                    $user['emailAddress'],
                    intval($user['imageID']),
                    intval($user['addressID'])
                );
            } else return null;
        }

        function delete($db){
            $stmt = $db->prepare('
                DELETE FROM User 
                WHERE  username = ?
            ');
            $stmt->execute(array($this->username));
        }

        function save($db, $oldUsername ='') {
            $stmt = $db->prepare('
                UPDATE User SET username = ?, fullName = ?, phoneNumber = ?, emailAddress = ?, addressID = ?
                WHERE username = ?
            ');
            if($oldUsername === ''){

                $stmt->execute(array($this->username, $this->fullName, $this->phoneNo, $this->email, $this->address->id, $this->username));
            }
            else{

                $stmt->execute(array($this->username, $this->fullName, $this->phoneNo, $this->email, $this->address->id, $oldUsername));
            }
        }

        static function changeUserAddress(PDO $db, string $username, int $addressID){
            $stmt = $db->prepare('
                UPDATE User SET addressID = ?
                WHERE username = ?
            ');

            $stmt->execute(array($addressID, $username));
        }

        function changePassword(PDO $db, string $newPassword){
            $stmt = $db->prepare('
                UPDATE User SET password = ?
                WHERE username = ?
            ');

            $stmt->execute(array($newPassword, $this->username));
        }

    }
