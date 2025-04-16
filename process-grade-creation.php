<?php
require_once "config.php";
include("session-checker.php");
if(isset($_POST['btnsubmit'])) {
    // Validate and sanitize form input data
    $subjectcode = $_POST['subjectcode'];
    $description = $_POST['txtdescription'];
    $unit = $_POST['cmbunit'];
    $grade = $_POST['cmbgrade'];
    // Check if the student number already exists
    $sql_check_studentnumber = "SELECT studentnumber FROM tblgrades, tblstudents WHERE studentnumber = ?";
    if($stmt_check_studentnumber = mysqli_prepare($link, $sql_check_studentnumber)) {
        mysqli_stmt_bind_param($stmt_check_studentnumber, "s", $studentnumber);
        mysqli_stmt_execute($stmt_check_studentnumber);
        mysqli_stmt_store_result($stmt_check_studentnumber);
        if(mysqli_stmt_num_rows($stmt_check_studentnumber) > 0) {
            // Student number already exists, show error message
            echo '<div class="alert alert-danger" role="alert">Student number is already taken. Please choose a different student number.</div>';
        } else {
            // Insert data into the database
            $sql_insert_student = "INSERT INTO tblgrades (subjectcode, description, unit, grade) VALUES (?, ?, ?, ?)";
            if($stmt_insert_student = mysqli_prepare($link, $sql_insert_student)) {
                mysqli_stmt_bind_param($stmt_insert_student, "ssss", $subjectcode, $description, $unit, $grade);
                if(mysqli_stmt_execute($stmt_insert_student)) {
                    $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                    if($stmt = mysqli_prepare($link, $sql)) {
                        $date = date("m/d/Y");
                        $time = date("h:i:sa");
                        $module = "Student Management";
                        $action = "Create";
                        mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['studentnumber'], $action, $_SESSION['studentnumber']);
                        // Execute the log statement
                        if (mysqli_stmt_execute($stmt)) {
                        // Student added successfully
                        echo '<div class="alert alert-success" role="alert">Student added!</div>';
                        header("Location: grades-management.php");
                        exit();
                        } else {
                            echo "<p class='error'>Error inserting log.</p>";
                        }
                    } else {
                        echo "<p class='error'>Error preparing insert log statement.</p>";
                    }
                }  else {
                    // Error in database insertion
                    echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
                }
                mysqli_stmt_close($stmt_insert_student);
            } else {
                // Error in prepared statement
                echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
            }
        
        mysqli_stmt_close($stmt_check_studentnumber);
    } else {
        // Error in prepared statement
        echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
    }
} else { // Loading the current values of the account
    if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))) {
        $sql = "SELECT * FROM tblgrades WHERE studentnumber = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_GET['studentnumber']);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                echo "<p class='error'>Error loading current values.</p>";
            }
        }
    }
}
}
?>