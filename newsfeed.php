<?php header('Content-Type: text/xml'); ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<rss version="2.0">
  <channel>
    <title>Little League Members - Newsfeed</title>
    <?php 
    require_once './includes/memberClass.inc.php';
    require_once './includes/helpers.inc.php';
    require_once './includes/appvars.inc.php';
    
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';  
    ?>
    <link><?php echo $home_url; ?></link>    <description>Little league member profiles.</description>    <language>en-us</language>

<?php

    $members = Member::getAllMembers();
    
  // Loop through the array of members, formatting it as RSS
  foreach ($members as $member) {
    if (is_file(LL_UPLOADPATH.$member->icon) && filesize(LL_UPLOADPATH.$member->icon) > 0) {
            $currentIcon =  $member->icon;           
    } else {
            $currentIcon = LL_GENERIC_ICON;
    }      
    // Display each row as an RSS item
    echo '<item>';
    echo '  <title>' . html($member->first_name) . ' ' . html($member->last_name). '</title>';
    echo '  <link>'. $home_url . 'memberDetail.php?id=' . html($member->id) . '</link>';
    echo '  <pubDate>' . html($member->when_added) . '</pubDate>';
    echo '  <description> <![CDATA[<img align="left" width="100" src="'. $home_url. LL_UPLOADPATH . $currentIcon. '"/>&nbsp;&nbsp;Position: '.
      html($member->position) . '<br />&nbsp;&nbsp;Age: ' . html($member->age). ' <br />]]></description>';   
    echo '</item>';
  }
?>

  </channel>
</rss>
