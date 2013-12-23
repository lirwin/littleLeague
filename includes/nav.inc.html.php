<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
        <li class = <?php echo (($_SERVER['PHP_SELF'] == dirname($_SERVER['PHP_SELF']) .'/index.php') || ($_SERVER['PHP_SELF'] == dirname($_SERVER['PHP_SELF']) . '/memberEdit.php')) ? "active" : "" ; ?>>
        <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' ?>">Members</a></li>
        <li class = <?php echo ($_SERVER['PHP_SELF'] == dirname($_SERVER['PHP_SELF']) .'/charts.php') ? "active" : "" ; ?>>
        <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/charts.php' ?>">Charts</a></li>
        <li class = <?php echo ($_SERVER['PHP_SELF'] == dirname($_SERVER['PHP_SELF']) .'/maps.php') ? "active" : "" ; ?>>
        <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/maps.php' ?>">Maps</a></li>
        <li class = <?php echo ($_SERVER['PHP_SELF'] == dirname($_SERVER['PHP_SELF']) .'/videos.php') ? "active" : "" ; ?>>
        <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/videos.php' ?>">Videos</a></li> 
        <li class = <?php echo ($_SERVER['PHP_SELF'] == dirname($_SERVER['PHP_SELF']) .'/newsfeed.php') ? "active" : "" ; ?>>
        <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/newsfeed.php' ?>" target="_blank"><img src="./img/rssicon.png" />&nbsp;Newsfeed</a></li>  
     </ul>
  </div>
</div>