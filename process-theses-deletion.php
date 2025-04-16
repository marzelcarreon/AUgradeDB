<?php
    require_once "config.php";
    include("session-checker.php");

if(isset($_POST['btnsubmit'])) {
    $sql = "DELETE FROM tbltheses WHERE numID = ?";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt,"s", $_POST['numID']);
        if(mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "theses Management";
                $action = "Delete";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['numID'], $action, $_SESSION['username']);
                if(mysqli_stmt_execute($stmt)) {
                    header("Location: theses-management.php");
                        exit();

                    } else {
                        echo "<p class='error'>Error inserting log.</p>";
                    }
                }
            } else {
                echo "<p class='error'>Error deleting account.</p>";
            }
        }
    }
    

    ?>
    