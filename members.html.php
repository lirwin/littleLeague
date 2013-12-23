<?php 
include_once './includes/appvars.inc.php';

$title = 'Little League Member Profiles';
require_once './includes/header.inc.html.php';
include_once './includes/helpers.inc.php';
?>

<div id="mainContent">
    <div class="span12">
        <button class="btn-success btn pull-right" id="logout" data-sid="<?php echo $sid; ?>">Log Out</button>
        <p class="pull-right">Hello, <?php echo $_SESSION['username'] ?>&nbsp;&nbsp;</p>

        <?php 
        include 'searchform.php';
        include 'searchform.html.php'; 
        
        if (isset($searchMsg)) {
            echo $searchMsg;
        }
        if (count($members) == 0) {
            exit();
        }
        ?> 

        <h1>Little League Member Profiles</h1> 
        
        <!--Output status messages -->
        <div id="status"></div>
        <table class="table table-striped table-bordered table-condensed table-hover">  
           <tbody>
               <tr>
                    <th>Profile Icon</th>
                    
                    <?php if (isset($sortLinks)) {
                        echo $sortLinks;
                    }
                    else {?>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <?php } ?>
                    
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Age</th>
                    <th>Position</th>
                    <th>Message</th>
                    <th>Edit/Delete</th>
                </tr> 
            </tbody>
            <tbody id="members">
                <?php foreach ($members as $member): 
                  if (is_file(LL_UPLOADPATH.$member->icon) && filesize(LL_UPLOADPATH.$member->icon) > 0) {
                        $currentIcon =  $member->icon;           
                  } else {
                        $currentIcon = LL_GENERIC_ICON;
                  } ?>                   
                  <tr id="<?php echo $member->id; ?>" data-name="<?php htmlout($member->getFullName()); ?>">
                    <td class="icon"><img src="<?php htmlout(LL_UPLOADPATH.$currentIcon); ?>" alt="Profile Image" /></td>
                    <td><?php htmlout($member->first_name); ?></td>
                    <td><?php htmlout($member->last_name); ?></td>
                    <td><?php htmlout($member->email); ?></td>
                    <td><?php htmlout($member->phone_number); ?></td>
                    <td><?php htmlout($member->street_address); ?><br /><?php empty($member->city) ? htmlout($member->state) : htmlout($member->city.', '.$member->state); ?><br /><?php htmlout($member->zip); ?></td>
                    <td><?php htmlout($member->age); ?></td>
                    <td><?php htmlout($member->position); ?></td>
                    <td><?php htmlout($member->message); ?></td>
                    <td data-sid="<?php echo $sid; ?>"><?php
                      if ($member->id == $_SESSION['user_id']){
                         echo '<button class="btn btn-primary edit" type="button">Edit</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-danger delete" type="button">Delete</button>';
                      }?>
                    </td>
                  </tr>  
                <?php endforeach; ?>  
            </tbody>
        </table>
        <?php  
            if (isset($pageLinks)) {
                echo $pageLinks;
            }
         ?>
    </div>
    <div class="clearfix"></div>
</div>
  
<?php 
require_once './includes/footer.inc.html.php';
?>