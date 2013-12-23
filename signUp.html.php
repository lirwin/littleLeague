<?php 
$title = 'Little League Sign Up';
require_once './includes/header.inc.html.php';
?>   
<div id="mainContent">
    <div class="span8">
        <h1>Little League Sign Up</h1> 
        <?php
          // If the session var is empty, show any error message and the sign-up form; otherwise confirm the log-in
          if (empty($_SESSION['user_id'])) {
            if (!empty($errormsg)){
               echo '<h3><p class="error">'.$errormsg.'</p></h3>'; 
            }
        ?>
        
        <form action="?" enctype="multipart/form-data" class="form-horizontal well" id="signUp" method="post">
           <legend>Personal Info</legend>
           <fieldset>
                <div class="control-group">
                <label class="control-label" for="icon">Profile Picture</label>
                <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-picture"></i></span>
                            <input type="file" name="icon" id="icon" />
                       </div>    
                </div>
               </div>      
               <div class="control-group">
                <label class="control-label" for="firstName">First Name</label>
                <div class="controls">
                       <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                            <input type="text" name="firstName" id="firstName" 
                            <?php if (!empty($firstName)) { echo 'value = "'; htmlout($firstName); echo '"'; } else echo 'placeholder="First Name"'; ?> />
                       </div>    
                </div>
                </div> 
                   <div class="control-group">
                    <label class="control-label" for="lastName">Last Name</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" name="lastName" id="lastName" 
                                <?php if (!empty($lastName)) { echo 'value = "'; htmlout($lastName); echo '"'; } else echo 'placeholder="Last Name"'; ?> />
                           </div>    
                    </div>
                </div>                 
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
                                <input type="email" name="email" id="email" 
                                <?php if (!empty($email)) { echo 'value = "'; htmlout($email); echo '"'; } else echo 'placeholder="Email Address"'; ?> />
                           </div>    
                    </div>
                </div>     
                <div class="control-group">
                    <label class="control-label" for="phoneNumber">Phone Number</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-bell"></i></span>
                                <input type="text" name="phoneNumber" id="phoneNumber" 
                                <?php if (!empty($phoneNumber)) { echo 'value = "'; htmlout($phoneNumber); echo '"'; } else echo 'placeholder="Phone Number"'; ?> />
                           </div>    
                    </div>
                </div> 
                <div class="control-group">
                    <label class="control-label" for="streetAddress">Street Address</label>
                    <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-book"></i></span>
                            <input type="text" class="input-xlarge" name="streetAddress" id="streetAddress" 
                            <?php if (!empty($streetAddress)) { echo 'value = "'; htmlout($streetAddress); echo '"'; } else echo 'placeholder="Street Address"'; ?> />
                        </div>    
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="streetAddress">City</label>
                    <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-book"></i></span>
                            <input type="text" class="input-xlarge" name="city" id="city" 
                            <?php if (!empty($city)) { echo 'value = "'; htmlout($city); echo '"'; } else echo 'placeholder="City"'; ?> />
                        </div>    
                    </div>
                </div> 
                <div class="control-group">
                    <label class="control-label" for="state">State</label>
                    <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-book"></i></span>
                            <select name="state" id="state">
                                <option value="">Select State</option>
                                <?php foreach($usStates as $key => $val) : ?>
                                <option value="<?php htmlout($key); ?>" <?php echo ($state == $key) ? 'selected': ''; ?>><?php htmlout($val); ?></option>
                                <?php endforeach; ?>
                            </select> 
                        </div>           
                    </div>
                </div>        
                <div class="control-group">
                    <label class="control-label" for="zip">Zip</label>
                    <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-book"></i></span>
                            <input type="text" class="input-xlarge" name="zip" id="zip" 
                            <?php if (!empty($zip)) { echo 'value = "'; htmlout($zip); echo '"'; } else echo 'placeholder="Zip"'; ?> />
                        </div>    
                    </div>
                </div> 
                <div class="control-group">
                    <label class="control-label" for="age">Age</label>
                    <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                            <select name="age" id="age">
                                <option value="">Select Age</option>
                                <?php foreach($ages as $selectAge) : ?>
                                <option value="<?php htmlout($selectAge); ?>" <?php echo ($selectAge == $age) ? 'selected': ''; ?>><?php htmlout($selectAge); ?></option>
                                <?php endforeach; ?>
                            </select> 
                        </div>           
                    </div>  
                </div>            
                <div class="control-group">
                    <label class="control-label" for="position">Position</label>
                    <div class="controls">
                        <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                            <select name="position" id="position">
                                <option value="">Select Field Position</option>
                                <?php foreach($positions as $selectPosition) : ?>
                                <option value="<?php htmlout($selectPosition); ?>" <?php echo ($selectPosition == $position) ? 'selected': ''; ?>><?php htmlout($selectPosition); ?></option>
                                <?php endforeach; ?>
                            </select> 
                        </div>           
                    </div> 
                </div>   
                <div class="control-group">
                    <label class="control-label" for="message">Message</label>
                    <div class="controls">
                            <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                                <textarea  class="input-xlarge" name="message" id="message" >
                                <?php if (!empty($message)) htmlout($message); ?>
                                </textarea>
                            </div>    
                    </div>
                </div>
           </fieldset>
           <legend>Login Info</legend>
           <fieldset>
              <div class="control-group">
                    <label class="control-label" for="username">Username</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" name="username" id="username" 
                                <?php if (!empty($username)) { echo 'value = "'; htmlout($username); echo '"'; } else echo 'placeholder="Username"'; ?> />
                           </div>    
                    </div>
               </div>  
               <div class="control-group">
                    <label class="control-label" for="password1">Password</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                                <input type="password" name="password1" id="password1" 
                                <?php if (!empty($password1)) { echo 'value = "'; htmlout($password1); echo '"'; } else echo 'placeholder="Password"'; ?> />
                           </div>    
                    </div>
                </div>
               <div class="control-group">
                    <label class="control-label" for="password2">Confirm Password</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                                <input type="password" name="password2" id="password2" 
                                <?php if (!empty($password2)) { echo 'value = "'; htmlout($password2); echo '"'; } else echo 'placeholder="Retype Password"'; ?> />
                           </div>    
                    </div>
                </div>
               <div class="control-group">
                    <label class="control-label" for="verify">Verification</label>
                    <div class="controls">
                           <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                                <input type="text" id="verify" name="verify" placeholder="Enter the pass-phrase." /><img src="captcha.php" alt="Verification pass-phrase" />
                           </div>    
                    </div>
                </div>                
            </fieldset>
            <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary btn-large">Sign Up</button>
            </div>            
        </form>
        <?php
          }
          else {
            // Confirm the successful sign up
            echo '<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>';
            echo '<button id="home" class="btn btn-success btn-large">Member Profiles</button>&nbsp;&nbsp;&nbsp;<button id="logout" class="btn btn-danger" data-sid="'. $sid. '">Log Out</button>';
          }
        ?>
    </div>
</div>
<?php 
require_once './includes/footer.inc.html.php';
?>