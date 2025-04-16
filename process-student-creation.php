<?php
require_once "config.php";
include("session-checker.php");

if(isset($_POST['btnsubmit'])) {
    // Validate and sanitize form input data
    $studentnumber = $_POST['txtstudentnumber'];
    $lastname = $_POST['txtlastname'];
    $firstname = $_POST['txtfirstname'];
    $middlename = $_POST['txtmiddlename'];
    $course = $_POST['cmbcourse'];
    $yearlevel = $_POST['cmbtype'];
    $createdby = $_SESSION['username'];
    $datecreated = date("m/d/Y");

    // Check if the student number already exists
    $sql_check_studentnumber = "SELECT studentnumber FROM tblstudents WHERE studentnumber = ?";
    if($stmt_check_studentnumber = mysqli_prepare($link, $sql_check_studentnumber)) {
        mysqli_stmt_bind_param($stmt_check_studentnumber, "s", $studentnumber);
        mysqli_stmt_execute($stmt_check_studentnumber);
        mysqli_stmt_store_result($stmt_check_studentnumber);
        if(mysqli_stmt_num_rows($stmt_check_studentnumber) > 0) {
            // Student number already exists, show error message
            echo '<div class="alert alert-danger" role="alert">Student number is already taken. Please choose a different student number.</div>';
        } else {
            // Insert data into the database
            $sql_insert_student = "INSERT INTO tblstudents (studentnumber, lastname, firstname, middlename, course, yearlevel, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if($stmt_insert_student = mysqli_prepare($link, $sql_insert_student)) {
                mysqli_stmt_bind_param($stmt_insert_student, "ssssssss", $studentnumber, $lastname, $firstname, $middlename, $course, $yearlevel, $createdby, $datecreated);
                if(mysqli_stmt_execute($stmt_insert_student)) {
                    // Insert into tblaccounts
                    $password = $_POST['txtpassword']; // Use the password exactly as entered by the user
                    $sql_insert_account = "INSERT INTO tblaccounts (username, password, email, usertype, userstatus, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $email = ""; // Set your default email or leave it empty
                    $usertype = "STUDENT";
                    $userstatus = "ACTIVE"; 
                    if($stmt_insert_account = mysqli_prepare($link, $sql_insert_account)) {
                        mysqli_stmt_bind_param($stmt_insert_account, "sssssss", $studentnumber, $password, $email, $usertype, $userstatus, $createdby, $datecreated);
                        if(mysqli_stmt_execute($stmt_insert_account)) {
                            // Log the action
                            $sql_insert_log = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                            if($stmt_insert_log = mysqli_prepare($link, $sql_insert_log)) {
                                $date_log = date("m/d/Y");
                                $time_log = date("h:i:sa");
                                $module = "Student Management";
                                $action_log = "Create";
                                mysqli_stmt_bind_param($stmt_insert_log, "ssssss", $date_log, $time_log, $module, $studentnumber, $action_log, $createdby);
                                if(mysqli_stmt_execute($stmt_insert_log)) {
                                    // Student and account added successfully
                                    echo '<div class="alert alert-success" role="alert">Student added!</div>';
                                    header("Location: students-management.php");
                                    exit();
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
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
        }
        mysqli_stmt_close($stmt_check_studentnumber);
    } else {
        // Error in prepared statement
        echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
    }
}
?>
