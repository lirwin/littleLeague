<?php

session_start();

//if PHPSESSID is passed through URL query (cookies disabled), then store it in var $sid
if  (isset($_GET['PHPSESSID'])) {
  $sid = $_GET['PHPSESSID'];
} 
  
// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['username'] = $_COOKIE['username'];
    }
}