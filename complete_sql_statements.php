<?php
    //Start new or resume existing session
    session_start();
    //load external file for connecting database
    require_once("dbinfo.php");
    //create object for DB connection
    $database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    //Returns the error code from last connect call
    if(mysqli_connect_errno() != 0) {
        $_SESSION['outputMessage'] = "<p>Database is not connected. Please contact your database administrator.</p>";
        header("Location: index.php");
        die();
    }
    //define variable for student number pattern
    const STUDENT_ID_PATTERN = "/^A0[0-9]{7}$/i";
    //process for insert user data to students table
    if(isset($_POST['add'])) {
        //check for input values validation
        if(!isset($_POST['studentId']) || !isset($_POST['firstname']) || !isset($_POST['lastname'])) {
            $_SESSION['outputMessage'] = "<p>Input data is not set</p>";
            header("Location: index.php");
            die();
        }
        if(empty(trim($_POST['studentId']))) {
            $_SESSION['outputMessage'] = "<p>Student number is required. Please fill in your student number.</p>";
            header("Location: index.php");
            die();
        }
        if(preg_match(STUDENT_ID_PATTERN, $_POST['studentId'] ) == 0 ){ 
            $_SESSION['outputMessage'] = "<p>Record NOT Added (Student Number must match the pattern: A0*******): <span class='special-text'>".
                                          $_POST['studentId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
                                          header("Location: index.php");
                                          die();
        }
        if(empty(trim($_POST['firstname']))) {
            $_SESSION['outputMessage'] = "<p>Firstname is required. Please fill in your firstname.</p>";
            header("Location: index.php");
            die();
        }
        if(empty(trim($_POST['lastname']))) {
            $_SESSION['outputMessage'] = "<p>Lastname is required. Please fill in your lastname.</p>";
            header("Location: index.php");
            die();
        }
        //Escapes special characters in a string for use in an SQL statement
        $studentId = $database->real_escape_string(trim($_POST['studentId']));
        $firstname = $database->real_escape_string(trim($_POST['firstname']));
        $lastname  = $database->real_escape_string(trim($_POST['lastname']));
        //create select query statement
        $selectSqlStatement = "SELECT * FROM students WHERE id='$studentId';";
        $returnSelectedResult = $database->query($selectSqlStatement);
        //checking if the entered student number is already in use
        if($returnSelectedResult->num_rows > 0){
            $_SESSION['outputMessage'] = "<p><span class='special-text'>$studentId</span> is already in use, please choose a different one.</p>";
            header("Location: index.php");
            die();
        }
        //run insert data into students table
        $insertSqlStatement = "INSERT INTO students (id, firstname, lastname) VALUES ( '$studentId','$firstname','$lastname');";
        $returnDataValues = $database->query($insertSqlStatement);
        //if return value > 0, Insert success
        if($database->affected_rows > 0) {
            $_SESSION['outputMessage'] = "<p>Record Added: <span class='special-text'>".$_POST['studentId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
            header("Location: index.php");		
            die();
        }else{
            $_SESSION['outputMessage'] = "<p>Record not Added: <span class='special-text'>".$_POST['studentId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
            header("Location: index.php");		
            die();
        }
        //terminate database connection 
        $database->close();

    //process for delete user data from students table 
    }elseif(isset($_POST['delete'])) {
        //If the user chooses 'yes'
        if($_POST['deleteChoise'] == "yes"){
            //Escapes special characters in a string for use in an SQL statement
            $deleteId = $database->real_escape_string(trim($_POST['deleteId']));
            //create delete query statement
            $deleteSqlStatement="DELETE FROM students WHERE id='$deleteId';";
            //run a query
            $database->query($deleteSqlStatement);
            //set a message in session
            $_SESSION['outputMessage'] = "<p>Record Deleted: <span class='special-text'>".$_POST['deleteId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
            //Return the page to the index.php
            header("Location: index.php");		
            die();
        }else{
            $_SESSION['outputMessage'] = "<p>Delete record aborted</p>";
            header("Location: index.php");		
            die();
        }
        //terminate database connection 
        $database->close();

    //process for update user data to students table 
    }elseif(isset($_POST['update'])) {

        //check for student number value validation
        if(preg_match(STUDENT_ID_PATTERN, $_POST['studentId'] ) == 0 ){ 
            $_SESSION['outputMessage'] = "<p>Record NOT Added (Student Number must match the pattern: A0*******): <span class='special-text'>".
                                          $_POST['studentId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
                                          header("Location: index.php");
                                          die();
        }
        //Escapes special characters in a string for use in an SQL statement
        $studentId = $database->real_escape_string(trim($_POST['studentId']));
        $firstname = $database->real_escape_string(trim($_POST['firstname']));
        $lastname  = $database->real_escape_string(trim($_POST['lastname']));
        $updateId  = $database->real_escape_string(trim($_POST['updateId']));

        //create update query statement
        $updateSqlStatement="UPDATE students SET id='$studentId', firstname='$firstname', lastname='$lastname' WHERE id='$updateId';";
        //run a query
        $returnDataValue = $database->query($updateSqlStatement);
        //check for return value
        if( $database->affected_rows == 0){
            $_SESSION['outputMessage'] = "<p>Record NOT Updated: <span class='special-text'>".$_POST['studentId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
            header("Location: index.php");
            die();
        }else{
            $_SESSION['outputMessage'] = "<p>Record Updated: <span class='special-text'>".$_POST['studentId']." ".$_POST['firstname']." ".$_POST['lastname']."</span></p>";
            header("Location: index.php");
            die();
        }
        //terminate database connection 
        $database->close();
    }
?>