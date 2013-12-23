<?php

include_once './includes/session.inc.php';
include_once './includes/helpers.inc.php';
include_once './includes/memberClass.inc.php';
include_once './includes/appvars.inc.php';
 
// Clear the error message
$error_msg = "";

// If the user isn't logged in, try to log them in
if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
    
     include_once './includes/db.inc.php';
        
     // Grab the user-entered log-in data
     $user_username = html($_POST['username']);
     $user_password = html($_POST['password']);
          
     if (!empty($user_username) && !empty($user_password)) {
         
         // Check if username and password exist for current member
         if (Member::checkPassword($user_username, $user_password)) {
             $member = new Member();
             if ($member->fetchPassword($user_username, $user_password)) {
                  // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                  $_SESSION['user_id'] = $member->id;
                  $_SESSION['username'] = $member->username;
                  setcookie('user_id', $member->id, time() + (60 * 60 * 24 * 30));    // expires in 30 days
                  setcookie('username', $member->username, time() + (60 * 60 * 24 * 30));  // expires in 30 days
                  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
                  
                  //if cookies are disabled pass session id (SID) through the URL
                  if (isset($_COOKIE['PHPSESSID'])) {
                       header('Location: ' . $home_url);
                  }
                  else {
                       header('Location: ' . $home_url.'?'.SID);
                  }                    
             }
             else {
                 // Error retrieving username/password 
                 $error_msg = "Sorry, there is an error retrieving your username and password.";
             }
         }
         else {
              // The username/password are incorrect so set an error message
             $error_msg = "Sorry, you must enter a valid username and password to log in.";
         }
         
     } else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter your username and password to log in.';
     }
  }
}
include 'login.html.php';
