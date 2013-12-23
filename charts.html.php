<?php 
include_once './includes/appvars.inc.php';

$title = 'Little League Member Charts';
require_once './includes/header.inc.html.php';
include_once './includes/helpers.inc.php';
?>

<div id="mainContent">
    <div class="span12">
        <button class="btn-success btn pull-right" id="logout" data-sid="<?php echo $sid; ?>">Log Out</button>
        <p class="pull-right">Hello, <?php echo $_SESSION['username'] ?>&nbsp;&nbsp;</p>

        <h1>Little League Member Charts</h1> 
      <?php
        $ageFileName = './img/charts/' . $_SERVER['UNIQUE_ID'] . '-graph.png';
        jpGraph(700, 300, $ageGraphData, $maxAgeYValue + 1, "Member Ages", "Ages", "Number of Members", $ageFileName);
        echo '<br><img src="' . $ageFileName . '" alt="Age Bar Graph" /><br /><br />';  
        
        draw_bar_graph(700, 300, $ageGraphData, $maxAgeYValue + 1, "Member Ages");
        
        $ageFileName = './img/charts/' . $_SERVER['UNIQUE_ID'] . '2-graph.png';
        draw_bar_graph2(580, 300, $ageGraphData, $maxAgeYValue + 1, $ageFileName);
        echo '<br><img src="' . $ageFileName . '" alt="Age Bar Graph" /><br /><br /><br />';  
    
        $positionFileName = './img/charts/' . $_SERVER['UNIQUE_ID'] . '1-graph.png';
        jpGraph(700, 420, $positionGraphData, $maxPositionYValue + 1, "Member Field Positions", "Positions", "Number of Members", $positionFileName);
        echo '<br><img src="' . $positionFileName . '" alt="Field Position Bar Graph" /><br /><br />'; 
        
        draw_bar_graph(700, 300, $positionGraphData, $maxPositionYValue + 1, "Member Field Positions");
        
        
        $positionFileName = './img/charts/' . $_SERVER['UNIQUE_ID'] . '3-graph.png';
        draw_bar_graph2(580, 300, $positionGraphData, $maxPositionYValue + 1, $positionFileName);
        echo '<br><img src="' . $positionFileName . '" alt="Field Position Bar Graph" /><br /><br />';  
        
      ?>            
    </div>
    <div class="clearfix"></div>
</div>
  
<?php 
require_once './includes/footer.inc.html.php';
?>