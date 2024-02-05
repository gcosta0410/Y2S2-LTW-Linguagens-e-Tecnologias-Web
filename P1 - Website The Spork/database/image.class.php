<?php 
    declare(strict_types = 1);

    class Image{
        public int $id;
        public string $path;
    

        public function __construct(int $id, string $path){
            $this->id = $id;
            $this->path = $path;
        }


        static function getImageByID(PDO $db, int $imageID) : ?Image{
            $stmt = $db->prepare('
              SELECT imageID, path
              FROM Image 
              WHERE imageID = ?
            ');

            $stmt->execute(array($imageID));
            if($image = $stmt->fetch()){
                return new Image($imageID, $image['path']);
            }else return null;
        }

        static function getLastID(PDO $db) : int{
            $stmt = $db->prepare('
                SELECT imageID 
                FROM Image
                ORDER BY imageID DESC
                LIMIT 1
            ');

            $stmt->execute();

            return intval($stmt->fetch()['imageID']);
        }

        function saveNew(PDO $db) {
            $stmt = $db->prepare('
                INSERT INTO Image(path) 
                VALUES(?)
            ');

            $stmt->execute(array($this->path));
        }

        function save($db) {
            $stmt = $db->prepare('
                UPDATE Image SET path = ?
                WHERE imageID = ?
            ');
            $stmt->execute(array($this->path, $this->id));

        }

    }