<?php
//Build $lastNames array for search form
try
{
  $result = $pdo->query('SELECT id, last_name FROM signUp ORDER BY last_name');
  
}
catch (PDOException $e)
{
  $error = 'Error fetching last names from database!';
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $lastNames[] = array('id' => $row['id'], 'last_name' => $row['last_name']);
}

//Build $firstNames array for search form
try
{
  $result = $pdo->query('SELECT id, first_name FROM signUp ORDER BY first_name');
  
}
catch (PDOException $e)
{
  $error = 'Error fetching first names from database!';
  include 'error.html.php';
  exit();
}

foreach ($result as $row)
{
  $firstNames[] = array('id' => $row['id'], 'first_name' => $row['first_name']);
}