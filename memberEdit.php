<?php 
include_once './includes/magicquotes.inc.php';
include_once './includes/session.inc.php';
include_once './includes/db.inc.php';
include_once './includes/appvars.inc.php';
include_once './includes/helpers.inc.php';
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

//get id from query string if empty
if (empty($id)) {
    $id = htmlspecialchars($_REQUEST['id']);
}  
//check that logged in user id matches edit profile id, otherwise go to login 
if ($id != $_SESSION['user_id']){
    echo "<p class='error'>You do not have access to this page.</p><p>Please <a href='".$login_url."'>login</a> or go to <a href='". $home_url ."'>Member Profiles</a></p>";
    exit();
}

$icon = '';
$outputForm = true;
     
if (isset($_POST['submit'])) {
    $id = trim($_REQUEST['id']);
    $firstName = trim($_REQUEST['firstName']);
    $lastName = trim($_REQUEST['lastName']);
    $email = trim($_REQUEST['email']);
    $phoneNumber = trim($_REQUEST['phoneNumber']);
    $message = trim($_REQUEST['message']);
    $guid = $_REQUEST['guid'];
    
    $streetAddress = trim($_REQUEST['streetAddress']);
    $city = trim($_REQUEST['city']);
    $state = trim($_REQUEST['state']);
    $zip = trim($_REQUEST['zip']);
    $age = trim($_REQUEST['age']);
    $position = trim($_REQUEST['position']);
    
    $icon = $_FILES['icon']['name'];
    $iconType = $_FILES['icon']['type'];
    $iconSize = $_FILES['icon']['size'];
    
    //flag for new icon on form submit
    $newIcon = !empty($_FILES['icon']['name']);
    
    //Set icon to current from hidden form field if no new file was uploaded
    if (!$newIcon){
        $icon = $_REQUEST['currentIcon'];
        $iconType = 'image/png';
        $iconSize = 20;
    }
        
    //Get time stamp
    date_default_timezone_set("America/Los_Angeles"); 
    $date = date('F j, Y');
    $time = date('g:i a');
    
    //Strip out everything but the domain from the email and check for valid email domain
    $validEmailDomain = checkdnsrr(preg_replace('/^\w+[\w-\.]*\@/','', $email ));
    
    //Check email and phone number with regexes 
    $validEmail = preg_match('/^\w+[\w-\.]*\@\w+((-\w+)|(\w*))\.[a-z]{2,3}$/', $email);
    $validPhone = preg_match('/^([0-9](?:[.-]\s*)?)?(\(?[0-9]{3}\)?|[0-9]{3})(?:[.-]\s*)?([0-9]{3}(?:[.-]\s*)?[0-9]{4}|[a-zA-Z0-9]{7})$/', $phoneNumber);
    $validZip = preg_match('/^\d{5}(-\d{4})?$/', $zip);
    
    //Check address using Google geocode to see if valid
    $validAddress = validAddress($streetAddress, $city, $state, $zip);
        
    //Get Latitude and Longitude of address from geocode data if valid address
    if ($validAddress) {
        $coord = getLongLat($streetAddress, $city, $state, $zip);
        $lat = $coord['lat'];
        $long = $coord['long'];
    }
    
    //Form Validation
    if (!empty($firstName) && !empty($lastName) && $validEmail && $validEmailDomain && $validPhone && $validAddress && !empty($icon) && !empty($streetAddress) && !empty($city) && !empty($state) && $validZip && !empty($age) 
    && !empty($position)) {
          if ((($iconType == 'image/gif') || ($iconType == 'image/jpeg') || ($iconType == 'image/pjpeg') || ($iconType == 'image/png')) && ($iconSize > 0) && ($iconSize <= LL_MAXFILESIZE)) {
                if ((($_FILES['icon']['error'] == 0) && $newIcon) || !$newIcon) {
                    if ($newIcon){
                        // Make filename unique by prefixing with unique id
                        $icon = uniqid().$icon;
                        // Replace all special characters with '_'
                        $icon = preg_replace("/[^-_a-z0-9.]/i", "_", $icon);
                        //Delete current icon file from server
                        @unlink(LL_UPLOADPATH.$_REQUEST['currentIcon']);
                    }
                    
                    $target = LL_UPLOADPATH . $icon;
                    
                    // Move the uploaed file to the target upload folder, if we have one
                    if (($newIcon && (move_uploaded_file($_FILES['icon']['tmp_name'], $target))) || !$newIcon) {
                        //Lock the signUp table
                        dbLock('signUp');   
                      
                        //Instantiate current member 
                        $member = new Member(); 
                        $member->get($id); 
                        $currentGuid = $member->guid; 
                        
                        //Clean up phone number to be only digits
                        $phoneNumber = preg_replace('/[\(\)\-\s]/', '', $phoneNumber);
                        
                        //If guid from form matches guid in db, then update member with information from form 
                        if ($currentGuid == $guid)  {
                            $member->set($firstName, $lastName, $email, $phoneNumber, $icon, $message, $streetAddress, $city, $state, $zip, $lat, $long, $age, $position); 
                            $member->update();
                            $outputForm = false;
                        } 
                        else {
                            echo '<h3><p class="error">Error updating data record. Please try again.</p></h3>';
                        }
                        //Unlock the signUp table
                        dbUnlock();
                     } 
                     else {
                        echo '<h3><p class="error">Error moving uploaded icon file.</p></h3>';
                     }
                } 
                else {
                    echo '<h3><p class="error">Error uploading icon file.</p></h3>';
                }
          } 
          else {
              echo '<h3><p class="error">The uploaded file must be a GIF, JPEG, or PNG image file no ' .'greater than ' . (LL_MAXFILESIZE / 1024) . ' KB in size.</p></h3>';  
          }  
          // Try to delete the temporary screen shot image file
          @unlink($_FILES['icon']['tmp_name']);  
    } 
    else {
        $errormsg = '';
        
        if (!$validAddress) {
            $errormsg .= 'A valid address is required.<br />';
        }  
        if (!$validEmail) {
            $errormsg .= 'A valid email is required.<br />';
        }
        if (!$validEmailDomain) {
            $errormsg .= 'A valid email domain is required.<br />';
        }  
        if (!$validPhone) {
            $errormsg .= 'A valid phone number is required.<br />';
        }
        if (!$validZip) {
            $errormsg .= 'A valid zip code is required.<br />';
        }        
        if (empty($firstName) || empty($lastName) || empty($icon) || empty($streetAddress) || empty($city) || empty($state)) {
            $errormsg .= 'Please fill in the required fields.<br />';
        }
    }           
}

//Instantiate current member 
$member = new Member(); 
$member->get($id); 
 
include 'membersEdit.html.php';
  
    

