<?php
require_once 'connectvars.inc.php';

//Connect to DB
try {
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES "utf8"');
}
catch (PDOException $e) {
  $error = 'Unable to connect to the database server.';
  include 'error.html.php';
  exit();
}    

//Lock DB table
//@table param
function dbLock($table) {
    global $pdo;
    
    try {
      //Lock the signUp table
      $sql = "LOCK TABLES $table WRITE";
      $result = $pdo->query($sql);
    }
    catch (PDOException $e) {
      $error = 'Error locking table: ' . $e->getMessage();
      include 'error.html.php';
      exit();
    }   
}


//Unlock DB tables
function dbUnlock() {
    global $pdo;
    
    try {
      $sql = "UNLOCK TABLES";
      $result = $pdo->query($sql);
    }
    catch (PDOException $e) {
      $error = 'Error unlocking table: ' . $e->getMessage();
      include 'error.html.php';
      exit();
    } 
}
