<?php 
$title = 'Little League Edit Profile';
require_once './includes/header.inc.html.php';
?>
    
<div id="mainContent">
  <div class="span8">
    <button class="btn-success btn pull-right" id="logout" data-sid="<?php echo $sid; ?>">Log Out</button>
    <p class="pull-right">Hello, <?php echo $_SESSION['username'] ?>&nbsp;&nbsp;</p>
    
    <?php if ($outputForm) { ?>
    <h1>Little League Edit Profile</h1> 
    <form action="?<?php if ($sid) echo SID;  ?>" enctype="multipart/form-data" id="signUpEdit" class="form-horizontal well" method="post">
 
        <?php 
          if (!empty($errormsg)) {
               echo '<h3><p class="error">'.$errormsg.'</p></h3>'; 
          }        
        
          if (is_file(LL_UPLOADPATH.$member->icon ) && filesize(LL_UPLOADPATH.$member->icon ) > 0) {
                $currentIcon =  $member->icon;           
          } else {
                $currentIcon = NULL;
          } ?>                   
        <input type="hidden" name="currentIcon" value="<?php htmlout($currentIcon); ?>" />
        
        <div class="control-group">
            <label class="control-label" for="icon"><img class="icon" src="<?php htmlout($currentIcon != NULL ? LL_UPLOADPATH.$currentIcon :  LL_UPLOADPATH. LL_GENERIC_ICON); ?>" alt="Profile Image" /></label>
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
                    <input type="text" class="input-xlarge" name="firstName" id="firstName" 
                    value="<?php empty($firstName) ? htmlout($member->first_name) : htmlout($firstName); ?>"  />
                </div>    
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="lastName">Last Name</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    <input type="text" class="input-xlarge" name="lastName" id="lastName" 
                    value="<?php empty($lastName) ? htmlout($member->last_name) : htmlout($lastName); ?>"  />
                </div>    
            </div>
        </div>                    
        <div class="control-group">
            <label class="control-label" for="email">Email Address</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
                    <input type="text" class="input-xlarge" name="email" id="email"  
                    value="<?php empty($email) ? htmlout($member->email) : htmlout($email); ?>"  />
                </div> 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="phoneNumber">Phone Number</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-bell"></i></span>
                    <input type="text" class="input-xlarge" name="phoneNumber" id="phoneNumber" 
                    value="<?php empty($phoneNumber) ? htmlout($member->phone_number) : htmlout($phoneNumber); ?>"  />
                </div>    
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="streetAddress">Street Address</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-book"></i></span>
                    <input type="text" class="input-xlarge" name="streetAddress" id="streetAddress"
                    value="<?php empty($streetAddress) ? htmlout($member->street_address) : htmlout($streetAddress); ?>"  />
                </div>    
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="streetAddress">City</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-book"></i></span>
                    <input type="text" class="input-xlarge" name="city" id="city"
                    value="<?php empty($city) ? htmlout($member->city) : htmlout($city); ?>"  />
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
                    <option value="<?php htmlout($key); ?>" <?php echo ($member->state == $key) ? 'selected': ''; ?>><?php htmlout($val); ?></option>
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
                    value="<?php empty($zip) ? htmlout($member->zip) : htmlout($zip); ?>"  />
                </div>    
            </div>
        </div> 
        <div class="control-group">
            <label class="control-label" for="age">Age</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    <select name="age" id="age">
                        <option value="">Select Age</option>
                        <?php foreach($ages as $age) : ?>
                        <option value="<?php htmlout($age); ?>" <?php echo ($member->age == $age) ? 'selected': ''; ?>><?php htmlout($age); ?></option>
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
                        <?php foreach($positions as $position) : ?>
                        <option value="<?php htmlout($position); ?>" <?php echo ($member->position == $position) ? 'selected': ''; ?>><?php htmlout($position); ?></option>
                        <?php endforeach; ?>
                    </select> 
                </div>           
            </div> 
        </div>            
        <div class="control-group">
            <label class="control-label" for="message">Message</label>
            <div class="controls">
                <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                    <textarea  class="input-xlarge" name="message" id="message" ><?php empty($message) ? htmlout($member->message) : htmlout($message); ?></textarea>
                </div>    
            </div>
        </div>            
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" id="guid" name="guid" value="<?php echo $member->guid; ?>" /> 
 
        <br />
        <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary btn-large">Update</button>
            <button type="reset" class="btn">Cancel</button>
        </div>
    </form>         
    
    <?php }  else { ?>
    <!--Output update confirmation -->
    <h1>Your Profile Update Was Successful</h1><br />
    <p><img class="icon" src="<?php htmlout($target); ?>"/></p>
    <strong>Icon Filename:</strong>&nbsp;&nbsp;<?php htmlout($target); ?><br />
    <strong>Name:</strong>&nbsp;&nbsp;<?php htmlout($firstName.' '.$lastName); ?><br />
    <strong>Email:</strong>&nbsp;&nbsp;<?php htmlout($email); ?><br />
    <strong>Phone Number:</strong>&nbsp;&nbsp;<?php htmlout($phoneNumber); ?><br />
    <strong>Street Address:</strong>&nbsp;&nbsp;<?php htmlout($streetAddress); ?><br />
    <strong>City:</strong>&nbsp;&nbsp;<?php htmlout($city); ?><br />
    <strong>State:</strong>&nbsp;&nbsp;<?php htmlout($state); ?><br />
    <strong>Zip:</strong>&nbsp;&nbsp;<?php htmlout($zip); ?><br />
    <strong>Latitude:</strong>&nbsp;&nbsp;<?php htmlout($lat); ?><br />
    <strong>Longitude:</strong>&nbsp;&nbsp;<?php htmlout($long); ?><br />
    <strong>Age:</strong>&nbsp;&nbsp;<?php htmlout($age); ?><br />
    <strong>Position:</strong>&nbsp;&nbsp;<?php htmlout($position); ?><br />
    <strong>Message:</strong>&nbsp;&nbsp;<?php htmlout($message); ?><br />
    <strong>Date/Time:</strong>&nbsp;&nbsp;<?php htmlout($date.' at '. $time); ?><br /><br />                         
    <?php }  ?>
  
    <button class="pull-right btn-success btn-large" id="home" data-sid="<?php echo $sid; ?>">Back to Member Profiles</button>
  </div>
  <div class="clearfix"></div>
</div>

<?php 
require_once './includes/footer.inc.html.php';
?>