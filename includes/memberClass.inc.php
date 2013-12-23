<?php

require_once './includes/db.inc.php';
require_once './includes/helpers.inc.php';

// Declare the interface 'iMember'
interface iMember {
    public function __construct($username, $password, $first_name, $last_name, $email, $phone_number, $icon, $message, $street_address, $city, $state, $zip, $lat, $lng, $age, $position);
    public function get($id);
    public function set($first_name, $last_name, $email, $phone_number, $icon, $message, $street_address, $city, $state, $zip, $lat, $lng, $age, $position);
    public static function getAllMembers($query, $placeholders);
    public function getFullName();
    public function create();
    public function update();
    public static function delete($id);
    public static function checkPassword($username, $password);
    public function fetchPassword($username, $password);  
}

// Implement the interface 'iMember'
class Member implements iMember {
    var $id, $username, $password, $first_name, $last_name, $email, $phone_number, $icon, $message, $guid,
        $street_address, $city, $state, $zip, $lat, $lng, $age, $position;
    
    
    public function __construct($username, $password, $first_name, $last_name, $email, $phone_number, $icon, $message, $street_address, $city, $state, $zip, $lat, $lng, $age, $position) {          
        $this->username = $username;    
        $this->password = $password;            
        $this->first_name = $first_name;            
        $this->last_name = $last_name;            
        $this->email = $email;            
        $this->phone_number = $phone_number;            
        $this->icon = $icon;            
        $this->message = $message;
        $this->street_address = $street_address; 
        $this->city = $city;            
        $this->state = $state;            
        $this->zip = $zip; 
        $this->lat = $lat; 
        $this->lng = $lng; 
        $this->age = $age;  
        $this->position = $position;    
    } 
    
 
    // populates instantiated member object properties with member data where id = $id
    public function get($id) {
        global $pdo;
        
        try {
            $result = $pdo->query('SELECT * FROM signUp WHERE id=' . $pdo->quote($id));
            $array = $result->fetch();
            foreach ($array as $key => $value) {
                $this->$key = $value;
            }
        }
        catch (PDOException $e) {
            $error = 'Error fetching member: ' . $e->getMessage();
            include 'error.html.php';
            exit();
        }  
    }
    
    // sets instantiated member object properties with member data passed into params
    public function set($first_name, $last_name, $email, $phone_number, $icon, $message, $street_address, $city, $state, $zip, $lat, $lng, $age, $position) {   
        $this->username = $username;    
        $this->password = $password;            
        $this->first_name = $first_name;            
        $this->last_name = $last_name;            
        $this->email = $email;            
        $this->phone_number = $phone_number;            
        $this->icon = $icon;            
        $this->message = $message; 
        $this->street_address = $street_address; 
        $this->city = $city;            
        $this->state = $state;            
        $this->zip = $zip; 
        $this->lat = $lat; 
        $this->lng = $lng; 
        $this->age = $age;  
        $this->position = $position;    
    } 
       
    // returns $members array with all member data from DB matching $query
    // param $query is sql query, if blank will select ALL members
    // param $placeholders for prepared statement execute
    public static function getAllMembers($query, $placeholders) {
        global $pdo;
        $members = array();
    
        try {
          if ( isset($query) and isset($placeholders)) {
            $result = $pdo->prepare($query);
            $result->execute($placeholders);
          } 
          else {
            if ( ! isset($query)) {
              $query = 'SELECT * FROM signUp order BY last_name';
            }
            $result = $pdo->query($query);
          } 
 
          foreach ($result as $row) {
              $member = new Member();
              foreach ($row as $key => $value) {
                  $member->$key = $value;
              } 
              $members[] = $member;
          }
        }
        catch (PDOException $e) {
          $error = 'Error fetching members: ' . $e->getMessage();
          include 'error.html.php';
          exit();
        }
        return $members;
    } 
     
    // returns member full name
    public function getFullName() {
        return $this->first_name. ' '. $this->last_name;
    }
    
    // creates new member if member object properties are already assigned
    // returns id of new member
    public function create() {
        global $pdo;
        
        try {
            $newGuid = uniqid();
            $sql = "INSERT INTO signUp SET
                    username = :username, password = SHA('$this->password'), first_name = :first_name, last_name = :last_name,
                    email = :email, phone_number = :phone_number, icon = :icon, message = :message, 
                    street_address = :street_address, city = :city, state = :state, zip = :zip, lat = :lat, lng = :lng, age = :age, position = :position,
                    guid='$newGuid'"; 
            $s = $pdo->prepare($sql);
            $s->bindValue(':username', $this->username);
            $s->bindValue(':first_name', $this->first_name);
            $s->bindValue(':last_name', $this->last_name);
            $s->bindValue(':email', $this->email);
            $s->bindValue(':phone_number', $this->phone_number);
            $s->bindValue(':icon', $this->icon);
            $s->bindValue(':message', $this->message);
            $s->bindValue(':street_address', $this->street_address);
            $s->bindValue(':city', $this->city);
            $s->bindValue(':state', $this->state);
            $s->bindValue(':zip', $this->zip);
            $s->bindValue(':lat', $this->lat);
            $s->bindValue(':lng', $this->lng);
            $s->bindValue(':age', $this->age);
            $s->bindValue(':position', $this->position);
            $s->execute();
          }
        catch (PDOException $e)  {
            $error = 'Error creating member: ' . $e->getMessage();
            include 'error.html.php';
            exit();
        }
        return $pdo->lastInsertId();
    }  

    // updates member if member object properties are already assigned
    public function update() {
        global $pdo;
        
        try {
            $newGuid = uniqid();
            $sql = "UPDATE signUp SET
            first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number, icon = :icon, 
            message = :message,
            street_address = :street_address, city = :city, state = :state, zip = :zip, lat = :lat, lng = :lng, age = :age, position = :position,
            when_added = NOW(),
            guid='$newGuid' WHERE id=".$pdo->quote($this->id);
            $s = $pdo->prepare($sql);
            $s->bindValue(':first_name', $this->first_name);
            $s->bindValue(':last_name', $this->last_name);
            $s->bindValue(':email', $this->email);
            $s->bindValue(':phone_number', $this->phone_number);
            $s->bindValue(':icon', $this->icon);
            $s->bindValue(':message', $this->message);
            $s->bindValue(':street_address', $this->street_address);
            $s->bindValue(':city', $this->city);
            $s->bindValue(':state', $this->state);
            $s->bindValue(':zip', $this->zip);
            $s->bindValue(':lat', $this->lat);
            $s->bindValue(':lng', $this->lng);
            $s->bindValue(':age', $this->age);
            $s->bindValue(':position', $this->position);
            $s->execute();
          }
        catch (PDOException $e)  {
            $error = 'Error updating member: ' . $e->getMessage();
            include 'error.html.php';
            exit();
        }
    }
    // deletes member data from DB with id= $id
    public static function delete($id) {
        global $pdo;
        
        try {
            //Build query string
            $sql = "DELETE FROM signUp WHERE id = :id LIMIT 1";
            $s = $pdo->prepare($sql);
            $s->bindValue(':id', $id);
            $s->execute(); 
        }
        catch (PDOException $e) {
            $error = 'Error deleting member from database.: ' . $e->getMessage();
            include 'error.html.php';
            exit();
        }        
    }
    
    // returns TRUE if $username and $password are a match for only one existing user, otherwise returns FALSE
    public static function checkPassword($username, $password) {
        global $pdo;
        
        try {
            $result = $pdo->query("SELECT COUNT(*) FROM signUp WHERE username = '$username' AND password = SHA('$password')");   
        }
        catch (PDOException $e) {
            $error = 'Error checking password: ' . $e->getMessage();
            include 'error.html.php';
            exit();        
        }
        if ($result->fetchColumn() == 1) return true;
        else return false;
    }  
    
    // fetches a row of member data based on the value of $username and $password and sets the member properties to match it.
    public function fetchPassword($username, $password) {
        global $pdo;

        try {
            $result = $pdo->query("SELECT * FROM signUp WHERE username = '$username' AND password = SHA('$password')");
            $array = $result->fetch();
            if (empty($array)) {
                return false;
            }  
        }
        catch (PDOException $e) {
            $error = 'Error fetching password: ' . $e->getMessage();
            include 'error.html.php';
            exit();        
        }
        foreach ($array as $key=>$value) {
            $this->$key = $value;
        }
        return true;
    }      
    
}
?>