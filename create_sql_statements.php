<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TWD-26-PHP Project</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<div class='wrapper'>
<header>
<h1>Administering DB From a Form</h1>
</header>
<main>

<?php
//process for insert user data to students table
if(isset($_GET['add'])) {
?>

<h2>Add a student:</h2>
<form action="complete_sql_statements.php" method="post">
  <fieldset>
    <legend>Add a record</legend>
        <input type="hidden" name="add" value="add" />
		<input type="text" name="studentId" id="studentId" autofocus/>
		<label for="studentId"> - Student #</label><br/>
		<input type="text" name="firstname" id="firstname"/>
		<label for="firstname"> - Firstname</label><br/>
		<input type="text" name="lastname" id="lastname" />
		<label for="lastname"> - Lastname</label><br/>
    <input type="submit" value="Submit">
  </fieldset>
</form>

<?php
//process for delete user data from students table 
}elseif(isset($_GET['delete'])) {
    //load external file for connecting database
    require_once("dbinfo.php");
    //create object for DB connection
    $database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME );
    //Returns the error code from last connect call
	if(mysqli_connect_errno() != 0) {
        echo "<p>Database is not connected. Please contact your database administrator.</p>";
      }
    //Escapes special characters in a string for use in an SQL statement
	$deleteId = $database->real_escape_string(trim($_GET['delete']));
    //create select query statement
    $selectSqlStatement = "SELECT id, firstname, lastname FROM students WHERE id='".$deleteId."';";
    //run a query
    $returnDataValues = $database->query($selectSqlStatement);
    //Fetch a row as an associative array
    $returnDataValue = $returnDataValues-> fetch_assoc();

?>

    <h2>Delete a student:</h2>
    <form action="complete_sql_statements.php" method="post">
    <fieldset>
        <legend>Delete a record - Are you sure?</legend>
            <p class='special-text'><?php echo $returnDataValue['id']." ". $returnDataValue['firstname']." ".$returnDataValue['lastname']; ?></p>
            <input type="hidden" name="delete" value="delete" />
            <input type="hidden" name="deleteId" value="<?php echo $returnDataValue['id']; ?>" />
            <input type="hidden" name="firstname" value="<?php echo $returnDataValue['firstname']; ?>" />
            <input type="hidden" name="lastname" value="<?php echo $returnDataValue['lastname']; ?>" />
            <input type='radio'  name='deleteChoise' id='yes' value='yes' checked='checked' />
            <label for='yes'>Yes</label><br />
            <input type='radio' name='deleteChoise' id='no' value='no' />
            <label for='no'>No</label><br />
            <input type="submit" value="Submit">
    </fieldset>
    </form>
<?php
//process for update user data to students table 
}elseif(isset($_GET['update'])){
    //load external file for connecting database
    require_once("dbinfo.php");
    //create object for DB connection
    $database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME );
    //Returns the error code from last connect call
	if(mysqli_connect_errno() != 0) {
        echo "<p>Database is not connected. Please contact your database administrator.</p>";
      }
    //Escapes special characters in a string for use in an SQL statement
	$updateId = $database->real_escape_string(trim($_GET['update']));
    //create select query statement
    $selectSqlStatement = "SELECT id, firstname, lastname FROM students WHERE id='".$updateId."';";
    //run a query
    $returnDataValues = $database->query($selectSqlStatement);
    //Fetch a row as an associative array
    $returnDataValue = $returnDataValues->fetch_assoc();

?>

    <h2>Update a record:</h2>
    <form action="complete_sql_statements.php" method="post">
    <fieldset>
        <legend>New data</legend>
            <input type="hidden" name="update" value="update" />
            <input type="hidden" name="updateId" value="<?php echo $returnDataValue['id']; ?>" />
            <input 	type="text"  name="studentId" id="studentId" value="<?php echo $returnDataValue['id']; ?>" autofocus/>
			<label 	for="studentId"> - Student #</label><br />
			<input 	type="text" name="firstname" id="firstname"	value="<?php echo $returnDataValue['firstname']; ?>" />
			<label for="firstname"> - Firstname</label><br />
			<input 	type="text" name="lastname" id="lastname" value="<?php echo $returnDataValue['lastname']; ?>" />
			<label for="lastname"> - Lastname</label><br />
            <input type="submit" value="Submit">
    </fieldset>
    </form>
<?php
}
?>
</main>
<footer><p>Developed by Denis Lim.</p></footer>
</div>    
</body>
</html>