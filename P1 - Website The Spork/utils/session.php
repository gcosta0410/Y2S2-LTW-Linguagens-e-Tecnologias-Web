<?php 
    class Session{
        private array $messages;

        public function __construct(){
            session_start();

            $this->messages = $_SESSION['messages'] ?? array();
            unset($_SESSION['messages']);
        }

        public function isLoggedIn() : bool{
            return isset($_SESSION['username']);
        }

        public function logout(){
            session_destroy();
        }

        public function setUsername(string $username) {
            $_SESSION['username'] = $username;
        }

        public function getUsername() : ?string {
            return $_SESSION['username'] ?? null;
        }

        public function setName(string $name) {
            $_SESSION['name'] = $name;
        }

        public function getName() : ?string {
            return $_SESSION['name'] ?? null;
        }

        public function addMessage(string $type, string $text) {
            $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
        }

        public function getMessages() {
            return $this->messages;
        }

        public function setEmail(string $email){
            $_SESSION['email'] = $email;
        }

        public function getEmail() : ?string {
            return $_SESSION['email'] ?? null;
        }

        public function setPhoneNo(string $phoneNo){
            $_SESSION['phoneNo'] = $phoneNo;
        }

        public function getPhoneNo() : ?string{
            return $_SESSION['phoneNo'] ?? null;
        }

        public function setImagePath(string $imagePath){
            $_SESSION['imagePath'] = $imagePath;
        }

        public function getImagePath() : ?string{
            return $_SESSION['imagePath'] ?? null;
        }

        public function setAddressID(int $addressID){
            $_SESSION['addressID'] = $addressID;
        }

        public function getAddressID(): ?int{
            return $_SESSION['addressID'] ?? null;
        }

        public function setPaymentInfo(string $info){
            $_SESSION['paymentInfo'] = $info;
        }

        public function getPaymentInfo(): ?string{
            return $_SESSION['paymentInfo'] ?? null;
        }

        public function doneSetup(){
            $_SESSION['setup'] = 'done';
        }

        public function isSetup() : bool{
            return isset($_SESSION['setup']);
        }

    }
