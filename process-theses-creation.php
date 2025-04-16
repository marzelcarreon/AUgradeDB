<?php
// process-account-creation.php

require_once "config.php";
if(isset($_POST['btnsubmit'])) {
    // Validate and sanitize form input data
    $title = $_POST['txttitle'];
    $authors = $_POST['txtauthors'];
    $adviser = $_POST['txtresearchadviser'];
    $usertype = $_POST['cmbtype'];
    $filelink = '';
    if(isset($_FILES['txtfilelink']) && $_FILES['txtfilelink']['error'] == 0) {
        $target_dir = "D:/xampp/htdocs/ITC127/database/uploads/";
        $filelink = $target_dir . basename($_FILES["txtfilelink"]["name"]);
        if (move_uploaded_file($_FILES["txtfilelink"]["tmp_name"], $filelink)) {
            // File uploaded successfully
        } else {
            echo '<div class="alert alert-danger" role="alert">File upload failed.</div>';
            exit;
        }
    }
    else {
        // Handle cases where no file was uploaded or an error occurred
        echo '<div class="alert alert-warning" role="alert">No file uploaded or there was an error with the upload.</div>';
        $filelink = ''; // Ensure filelink is empty if no file is uploaded
    }
    // Check if the code already exists
    $sql_check_code = "SELECT title FROM tbltheses WHERE title = ?";
    if($stmt_check_code = mysqli_prepare($link, $sql_check_code)) {
        mysqli_stmt_bind_param($stmt_check_code, "s", $title);
        mysqli_stmt_execute($stmt_check_code);
        mysqli_stmt_store_result($stmt_check_code);
        if(mysqli_stmt_num_rows($stmt_check_code) > 0) {
            // code already exists, show error message
            echo '<div class="alert alert-danger" role="alert">Title is already taken. Please choose a different title.</div>';
        } else {
            // Insert data into the database
            $sql_insert_account = "INSERT INTO tbltheses (title, authors, researchadviser, usertype, filelink, createdby, datesubmitted) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($stmt_insert_account = mysqli_prepare($link, $sql_insert_account)) {
                $date = date("m/d/Y");
                mysqli_stmt_bind_param($stmt_insert_account, "sssssss", $title, $authors, $adviser, $usertype, $filelink, $_SESSION['username'], $date);
                if(mysqli_stmt_execute($stmt_insert_account)) {
                    // Account added successfully
                    $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                    if($stmt = mysqli_prepare($link, $sql)) {
                        $date = date("m/d/Y");
                        $time = date("h:i:sa");
                        $module = "Thesis Management";
                        $action = "Create";
                        mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtauthors'], $action, $_SESSION['username']);
                        // Execute the log statement
                        if (mysqli_stmt_execute($stmt)) {
                        // Account added successfully
                        echo '<div class="alert alert-success" role="alert">Thesis added!</div>';
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
        mysqli_stmt_close($stmt_check_code);
    } else {
        // Error in prepared statement
        echo '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($link) . '</div>';
    }
}
?>