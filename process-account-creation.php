<?php
// process-account-creation.php

require_once "config.php";
if(isset($_POST['btnsubmit'])) {
    // Validate and sanitize form input data
    $username = $_POST['txtusername'];
    $password = $_POST['txtpassword']; // Note: You should hash the password before storing it in the database
    $retypePassword = $_POST['txtretypepassword'];
    $usertype = $_POST['cmbtype'];

    // Check if the username already exists
    $sql_check_username = "SELECT username FROM tblaccounts WHERE username = ?";
    if($stmt_check_username = mysqli_prepare($link, $sql_check_username)) {
        mysqli_stmt_bind_param($stmt_check_username, "s", $username);
        mysqli_stmt_execute($stmt_check_username);
        mysqli_stmt_store_result($stmt_check_username);
        if(mysqli_stmt_num_rows($stmt_check_username) > 0) {
            // Username already exists, show error message
            echo '<div class="alert alert-danger" role="alert">Username is already taken. Please choose a different username.</div>';
        } else {
            // Insert data into the database
            $sql_insert_account = "INSERT INTO tblaccounts (username, password, usertype, userstatus, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt_insert_account = mysqli_prepare($link, $sql_insert_account)) {
                $status = "ACTIVE";
                $date = date("m/d/Y");
                mysqli_stmt_bind_param($stmt_insert_account, "ssssss", $username, $password, $usertype, $status, $_SESSION['username'], $date);
                if(mysqli_stmt_execute($stmt_insert_account)) {
                    // Account added successfully
                    $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                    if($stmt = mysqli_prepare($link, $sql)) {
                        $date = date("m/d/Y");
                        $time = date("h:i:sa");
                        $module = "Account Management";
                        $action = "Create";
                        mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtusername'], $action, $_SESSION['username']);
                        // Execute the log statement
                        if (mysqli_stmt_execute($stmt)) {
                        // Account added successfully
                        echo '<div class="alert alert-success" role="alert">User account added!</div>';
                        } else {
                            echo "<p class='error'>Error inserting log.</p>";
                        }
                    } else {
                        echo "<p class='error'>Error preparing insert log statement.</p>";
                    }
                } else {
                    // Error in database insertion
                    echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
                }
                mysqli_stmt_close($stmt_insert_account);
            } else {
                // Error in prepared statement
                echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
            }
        }
        mysqli_stmt_close($stmt_check_username);
    } else {
        // Error in prepared statement
        echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
    }
}
?>
