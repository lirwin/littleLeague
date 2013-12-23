<?php
require_once './includes/db.inc.php';
require_once './includes/helpers.inc.php';

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node); 

// Select all the rows in the markers table
$query = "SELECT first_name, last_name, street_address, city, state, zip, lat, lng FROM signUp";
try {
    $result = $pdo->prepare($query);
    $result->execute();
}
catch (PDOException $e) {
  $error = 'Error fetching members: ' . $e->getMessage();
  include 'error.html.php';
  exit();
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

foreach ($result as $row) {
  // ADD TO XML DOCUMENT NODE
  $address = html($row['street_address']). '<br /> ' . html($row['city']). ', ' .html($row['state']). ' ' .html($row['zip']);
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("firstName", html($row['first_name']));
  $newnode->setAttribute("lastName", html($row['last_name']));
  $newnode->setAttribute("address", $address);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
}

echo $dom->saveXML();
?>

