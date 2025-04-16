<?php
        require_once "config.php";
        include("session-checker.php");

    if(isset($_POST['btnsubmit'])) {
        $sql = "DELETE FROM tblgrades WHERE studentnumber = ? AND subjectcode = ? AND grade = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $_POST['txtstudentnumber'], $_POST['txtcode'], $_POST['txtgrade']);
            if(mysqli_stmt_execute($stmt)) {
                $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("m/d/Y");
                    $time = date("h:i:sa");
                    $module = "Grade Management";
                    $action = "Delete";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnumber'], $action, $_SESSION['username']);
                    if(mysqli_stmt_execute($stmt)) {
                        header("Location: grades-management.php");
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
    