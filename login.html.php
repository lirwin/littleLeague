<?php 
$title = 'Little League Login';
require_once './includes/headerNoNav.inc.html.php';
?>

<div id="mainContent">
    <div class="span8">
        <h1>Little League Log In</h1>    

<?php
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">'.$error_msg.'</p>';
?>

  <form method="post" action="?" id="loginForm" class="form-horizontal well">
       <div class="control-group">
            <label class="control-label" for="username">Username</label>
            <div class="controls">
                    <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                        <input type="text" name="username" id="username" value="<?php if (!empty($user_username)) echo $user_username; ?>"/>
                   </div>    
            </div>
       </div>      
       <div class="control-group">
            <label class="control-label" for="password">Password</label>
            <div class="controls">
                   <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                        <input type="password" name="password" id="password" />
                   </div>    
            </div>
       </div> 
       <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary btn-large">Log In</button>
            <div class="separator">
                <h3>Haven't Signed Up Yet?</h3>
                <button class="btn-success btn-large" id="signUp">Sign Up</button>
            </div>
       </div>
  </form>
  
<?php
  }
  else {
    // Confirm the successful log-in
    echo '<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>';
    echo '<button id="home" class="btn btn-success btn-large">Member Profiles</button>&nbsp;&nbsp;&nbsp;<button id="logout" class="btn btn-danger" data-sid="'. $sid. '">Log Out</button>';
  }
?>
    </div>
</div>

<?php 
require_once './includes/footer.inc.html.php';
?>