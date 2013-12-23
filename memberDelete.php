<?php 
include_once './includes/session.inc.php';
include_once './includes/db.inc.php';
include_once './includes/appvars.inc.php';
include_once './includes/memberClass.inc.php';  

// login url
$login_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';  
// home url 
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';    
 
// Check for login status, if not logged in then go to login script
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: ' . $login_url);
    exit();
}

// get data from AJAX post
$id = $_REQUEST['id'];
$iconFullPath = $_REQUEST['iconFullPath'];

// check that logged in user id matches edit profile id, otherwise go back to main page 
if ($id != $_SESSION['user_id']){
    echo "<p>You do not have access to this page.</p><p>Please <a href='".$login_url."'>login</a> or go to <a href='". $home_url ."'>Member Profiles</a></p>";
    exit();
}
 
// Delete icon image file from server if not the default icon
if ($iconFullPath != LL_UPLOADPATH.LL_GENERIC_ICON) {
     @unlink($iconFullPath);
}

// Delete member with id=$id
Member::delete($id);