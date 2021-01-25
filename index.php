<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TWD-26-PHP Project</title>
    <link rel="stylesheet" href="styles/style.css">
    <!-- font awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class='wrapper'>
<header>
<h1>Administering DB From a Form</h1>
</header>
<main>

<?php
    //Start new or resume existing session
    session_start();
    //Output messages
    if(isset($_SESSION['outputMessage'])) {
        echo "<div class='output-message-container'>".$_SESSION['outputMessage']."</div>";
        unset($_SESSION['outputMessage']);
    }
?>

<h2>Student:</h2>
<?php

//load external file for connecting database
require_once('dbinfo.php');

//create object for DB connection
$database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Returns the error code from last connect call
if(mysqli_connect_errno() != 0) {
  die("<p><span class='special-text'>Database is not connected. Please contact your database administrator.</span></p>");
}

//create link for add student record
echo "<div class='add-container'><a class='btn-add' href='create_sql_statements.php?add'>Add a Student</a></div>";

//define variable for order
$sortby = "id";
if(isset($_GET['sortby'])) {
  $sortby = $_GET['sortby'];
}
//Escapes special characters in a string for use in an SQL statement
$sortby = $database->real_escape_string($sortby);
//create select query statement
$selectSqlStatement = "SELECT id, firstname, lastname FROM students ORDER BY $sortby;";
//run a query
$returnDataValues = $database->query($selectSqlStatement);
//No data if return value is zero
if($returnDataValues->num_rows == 0) {
  $_SESSION['outputMessage'] = "<p>There is no data.</p>";
}
//Get column information from target table
$titleColumns = $returnDataValues->fetch_fields();
echo "<table class='students'><thead><tr>";
foreach($titleColumns as $titleColumn) {
  echo "<th><a href='index.php?sortby=".$titleColumn->name."'>".ucfirst($titleColumn->name)."<i class='fa fa-sort-asc' aria-hidden='true'></i></a></th>";
}
echo "</tr></thead>";

echo "<tbody>";
//Fetch a row as an associative array
while($returnDataRows = $returnDataValues-> fetch_assoc()){
  echo "<tr>";
  echo "<td>".$returnDataRows['id']."</td>";
  echo "<td>".$returnDataRows['firstname']."</td>";
  echo "<td>".$returnDataRows['lastname']."</td>";
  echo "<td><a class='btn' href='create_sql_statements.php?delete=".$returnDataRows['id']."'>delete</a></td>";
  echo "<td><a class='btn' href='create_sql_statements.php?update=".$returnDataRows['id']."'>update</a></td>";
  echo "</tr>";
}
echo "</tbody>";
echo "</table>";
//terminate database connection 
$database->close();
?>
</main>
<footer><p>Developed by Denis Lim.</p></footer>
</div>    
</body>
</html>