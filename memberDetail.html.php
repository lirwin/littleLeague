<?php 
$title = 'Little League Member Profile';
require_once './includes/header.inc.html.php';
?>
    
<div id="mainContent">
  <div class="span8">
    <h1>Little League Member Profile</h1> 
 
        <?php 
          if (is_file(LL_UPLOADPATH.$member->icon ) && filesize(LL_UPLOADPATH.$member->icon ) > 0) {
                $currentIcon =  $member->icon;           
          } else {
                $currentIcon = LL_GENERIC_ICON;
          } ?> 
                            
        <p><img class="icon" src="<?php htmlout(LL_UPLOADPATH.$currentIcon); ?>" alt="Profile Image"/></p>
        <strong>Name:</strong>&nbsp;&nbsp;<?php htmlout($member->first_name.' '.$member->last_name); ?><br />
        <strong>Position:</strong>&nbsp;&nbsp;<?php htmlout($member->position); ?><br />
        <strong>Age:</strong>&nbsp;&nbsp;<?php htmlout($member->age); ?><br />
  </div>
</div>

<?php 
require_once './includes/footer.inc.html.php';
?>