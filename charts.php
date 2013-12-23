<?php
include_once './includes/magicquotes.inc.php';
include_once './includes/session.inc.php';
include_once './includes/db.inc.php';
include_once './includes/appvars.inc.php';
include_once './includes/helpers.inc.php';
include_once './includes/memberClass.inc.php';
  
// login url for login script
$login_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';  
 
// Check for login status, if not logged in then go to login script

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: ' . $login_url);
    exit();
}


// Using jpGraph.net
function jpGraph($width, $height, $data, $maxValue, $title, $xTitle, $yTitle, $filename) {
    
    require_once ('./jpgraph/jpgraph.php');
    require_once ('./jpgraph/jpgraph_bar.php');
    
    //$datay=array(62,105,85,50);
    
    $datay = array_values($data);
    $datax = array_keys($data);
  
    // Create the graph. These two calls are always required
    $graph = new Graph($width, $height,'auto');
    $graph->SetScale("textlin");
    //$graph->SetScale('intlin',0, $maxValue);
        
    //$theme_class="DefaultTheme";
    //$graph->SetTheme(new $theme_class());
    
    // set major and minor tick positions manually
    //$graph->yaxis->SetTickPositions(array(0, $maxValue));
    $graph->SetBox(false);
    
    //$graph->ygrid->SetColor('gray');
    $graph->ygrid->SetFill(false);

    // Setup X & Y-axis labels
    $graph->xaxis->SetTickLabels($datax);
    $graph->xaxis->SetLabelAngle(50);
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false,false);
    
    // Create the bar plots
    $b1plot = new BarPlot($datay);
    
    // ...and add it to the graph
    $graph->Add($b1plot);
    
    
    $b1plot->SetColor("white");
    $b1plot->SetFillGradient("#4B0082","white",GRAD_LEFT_REFLECTION);
    $b1plot->SetWidth(45);
    $graph->title->Set($title);
    $graph->xaxis->title->Set($xTitle);
    $graph->yaxis->title->Set($yTitle);
 
    // Display the graph
    $graph->Stroke($filename);           
}  

// Using the php wrapper for google charts
function draw_bar_graph($width, $height, $data, $max_value, $title)
{
    require_once('./includes/gChart.inc.php');

    // Need to separate the y values from the x labels
    // The labels are the keys, the values are the y values
    $myArray = array_values($data);
    $xLabels = array_keys($data);
    $cleanxLabels = array();
    
    foreach ($xLabels as $xLabel) {
        $cleanxLabels[] = str_replace(" " , "", $xLabel);
    }

    // This sets up the chart of google charts
    $barChart = new gBarChart();
    
    $barChart->setTitle($title);
    $barChart->addDataSet($myArray);            // add the y values
    $barChart->setAutoBarWidth();
    $barChart->setColors(array("ff3344", "11ff11", "22aacc"));
    $barChart->setGradientFill('c',0,array('FFE7C6',0,'76A4FB',1));
    $barChart->setDimensions($width, $height);
    $barChart->setVisibleAxes(array('x','y'));
    $barChart->setDataRange(0, $max_value);
    $barChart->addAxisRange(1, 0, $max_value);
    $barChart->addAxisLabel(0, $cleanxLabels);       

    // Interesting, google charts creates an image from their site
    echo "<img src=" . $barChart->getUrl() . " /> <br>";

} // End of draw_bar_graph() function


// The books example of how to draw a chart, with a change to how the data
// is organized
function draw_bar_graph2($width, $height, $data, $max_value, $filename)
{
    // Create the empty graph image
    $img = imagecreatetruecolor($width, $height);

    // Set a white background with black text and gray graphics
    $bg_color = imagecolorallocate($img, 255, 255, 255);       // white
    $text_color = imagecolorallocate($img, 255, 255, 255);     // white
    $bar_color = imagecolorallocate($img, 0, 0, 0);            // black
    $border_color = imagecolorallocate($img, 192, 192, 192);   // light gray

    // Fill the background
    imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);

    // Draw the bars
    $bar_width = $width / ((count($data) * 2) + 1);
    $xLabels = array_keys($data);
    for ($i = 0; $i < count($data); $i++)
    {
        imagefilledrectangle($img, ($i * $bar_width * 2) + $bar_width, $height,
            ($i * $bar_width * 2) + ($bar_width * 2),
             $height - (($height / $max_value) * $data[$xLabels[$i]]), $bar_color);
        imagestringup($img, 4, ($i * $bar_width * 2) + ($bar_width), $height - 5, $xLabels[$i], $text_color);
    }

    // Draw a rectangle around the whole thing
    imagerectangle($img, 0, 0, $width - 1, $height - 1, $border_color);

    // Draw the range up the left side of the graph
    for ($i = 1; $i <= $max_value; $i++)
    {
        imagestring($img, 5, 0, $height - ($i * ($height / $max_value)), $i, $bar_color);
    }

    // Write the graph image to a file
    imagepng($img, $filename, 5);
    imagedestroy($img);
} // End of draw_bar_graph2() function

function removeGraphImages () {
    // Remove old graph images from server    
    $fileList = glob('./img/charts/' . '{*-graph.png}', GLOB_BRACE);
    foreach($fileList as $filename) {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }         
}
removeGraphImages ();
$sql = 'SELECT * FROM signUp order BY age';
$members = Member::getAllMembers($sql);

$ageGraphData = array();
$positionGraphData = array();


// Build the data arrays
foreach($members as $member) {
    if (array_key_exists($member->age, $ageGraphData))
        $ageGraphData[$member->age]++;       // Yes, increment it
    else
        $ageGraphData[$member->age] = 1;     // No, set it to one
    if (array_key_exists($member->position, $positionGraphData))
        $positionGraphData[$member->position]++;       // Yes, increment it
    else
        $positionGraphData[$member->position] = 1;     // No, set it to one                
}

$maxAgeYValue = max($ageGraphData);
$maxPositionYValue = max($positionGraphData);

// Sort field positions
array_reorder_keys($positionGraphData, 'pitcher,catcher,first base,second base,third base,short stop,left field,center field,right field');

include 'charts.html.php';

?>
