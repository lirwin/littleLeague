<?php 
include_once './includes/magicquotes.inc.php';
include_once './includes/session.inc.php';
include_once './includes/db.inc.php';
include_once './includes/appvars.inc.php';
include_once './includes/memberClass.inc.php';
include_once './includes/helpers.inc.php';
  
// Clear the error message
$error_msg = "";

if (isset($_POST['submit'])) {
      
   // Grab the profile data from the POST
    $username = trim($_REQUEST['username']);
    $password1 = trim($_REQUEST['password1']);
    $password2 = trim($_REQUEST['password2']);
             
    $firstName = trim($_REQUEST['firstName']);
    $lastName = trim($_REQUEST['lastName']);
    $email = trim($_REQUEST['email']);
    $phoneNumber = trim($_REQUEST['phoneNumber']);
    $message = trim($_REQUEST['message']);
 
    $streetAddress = trim($_REQUEST['streetAddress']);
    $city = trim($_REQUEST['city']);
    $state = trim($_REQUEST['state']);
    $zip = trim($_REQUEST['zip']);
    $age = trim($_REQUEST['age']);
    $position = trim($_REQUEST['position']);
    
    $icon = $_FILES['icon']['name'];
    $iconType = $_FILES['icon']['type'];
    $iconSize = $_FILES['icon']['size'];
    
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

    // Check the CAPTCHA pass-phrase for verification
    if (valid_captcha(sha1($_REQUEST['verify']))) {                 
        //Form Validation
        if (!empty($firstName) && !empty($lastName) && $validEmail && $validEmailDomain && $validPhone && $validAddress && !empty($icon) && !empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)
         && !empty($streetAddress) && !empty($city) && !empty($state) && $validZip && !empty($age) && !empty($position)) {
            try {
              // Make sure someone isn't already registered using this username
              $sql = "SELECT COUNT(*) FROM signUp WHERE username = '$username'";
              if ($result = $pdo->query($sql)) {
                  if ($result->fetchColumn() == 0) {
                  // The username is unique, so insert the data into the database
                    if ((($iconType == 'image/gif') || ($iconType == 'image/jpeg') || ($iconType == 'image/pjpeg') || ($iconType == 'image/png')) && ($iconSize > 0) && ($iconSize <= LL_MAXFILESIZE)) {
                        if ($_FILES['icon']['error'] == 0) {
                    
                            // Make filename unique by prefixing with unique id
                            $icon = uniqid().$icon;
                            // Replace all special characters with '_'
                            $icon = preg_replace("/[^-_a-z0-9.]/i", "_", $icon);
                        
                            $target = LL_UPLOADPATH . $icon;
                        
                            // Move the file to the target upload folder
                            if (move_uploaded_file($_FILES['icon']['tmp_name'], $target)) {
                                
                                //Clean up phone number to be only digits
                                $phoneNumber = preg_replace('/[\(\)\-\s]/', '', $phoneNumber);
                                
                                //Instantiate new member 
                                $member = new Member($username, $password1, $firstName, $lastName, $email, $phoneNumber, $icon, $message, $streetAddress, $city, $state, $zip, $lat, $long, $age, $position); 
                                
                                //Create new member
                                $id = $member->create();
                                                            
                                // Log in new member and redirect to home page
                                $_SESSION['user_id'] = $id;
                                $_SESSION['username'] = $username;
                                setcookie('user_id', $id, time() + (60 * 60 * 24 * 30));    // expires in 30 days
                                setcookie('username', $username, time() + (60 * 60 * 24 * 30));  // expires in 30 days
                                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
                      
                                //if cookies are disabled pass session id (SID) through the URL
                                if (isset($_COOKIE['PHPSESSID'])) {
                                    header('Location: ' . $home_url);
                                }
                                else {
                                    header('Location: ' . $home_url.'?'.SID);
                                } 
                                exit();
                            } 
                            else {
                                $error_msg = '<p class="error">Error moving uploaded icon file.</p>';  
                            }
                        } 
                        else {
                           $error_msg = '<p class="error">Error uploading icon file.</p>';    
                        }
                      } 
                      else {
                          $error_msg = '<p class="error">The uploaded file must be a GIF, JPEG, or PNG image file no greater than ' . (LL_MAXFILESIZE / 1024) . ' KB in size.</p>';               
                      }//icon check
                  
                  // Try to delete the temporary screen shot image file
                  @unlink($_FILES['icon']['tmp_name']);           
                  }
                  else {
                    // An account already exists for this username, so display an error message
                    $error_msg = '<p class="error">An account already exists for this username. Please use a different username.</p>';
                    $username = "";
                  } 
              }   
            }
            catch (PDOException $e) {
              $error = 'Error retrieving username: ' . $e->getMessage();
              include 'error.html.php';
              exit();
            }        
            
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
            if (empty($firstName) || empty($lastName) || empty($icon) || empty($streetAddress) || empty($city) || empty($state) || 
             empty($icon) || empty($username) || empty($password1) || empty($password2) || ($password1 != $password2)) {
                $errormsg .= 'Please enter all of the sign-up data, including the desired password twice.<br />';
            }
        }

    }
    else {
        $errormsg = 'Please enter the verification pass-phrase exactly as shown.';
    }
}
include 'signUp.html.php';