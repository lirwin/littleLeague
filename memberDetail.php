<?php 
include_once './includes/helpers.inc.php';
include_once './includes/memberClass.inc.php';
include_once './includes/appvars.inc.php';

     
if (isset($_REQUEST['id'])) {
    $id = html($_REQUEST['id']);

    //Instantiate current member 
    $member = new Member(); 
    $member->get($id); 
}
include 'memberDetail.html.php';
  
    

