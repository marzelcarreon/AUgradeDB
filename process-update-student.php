<?php
// Check if the form is submitted
if(isset($_POST['btnsubmit'])) {
    require_once "config.php";
    include("session-checker.php");
    // Prepare SQL statement to update the user account
    $sql = "UPDATE tblstudents SET lastname = ?, firstname = ?, middlename = ? , course = ?, yearlevel = ? WHERE studentnumber = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtlastname'], $_POST['txtfirstname'], $_POST['txtmiddlename'], $_POST['cmbcourse'], $_POST['cmbtype'], $_POST['txtstudentnumber']);
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Student Management";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnumber'], $action, $_SESSION['username']);
                // Execute the log statement
                if (mysqli_stmt_execute($stmt)) {
                    // Log insertion successful
                    echo "<p>Student account updated successfully.</p>";
                    header("location: students-management.php");
                    exit();
                } else {
                    echo "<p class='error'>Error inserting log.</p>";
                }
            } else {
                echo "<p class='error'>Error preparing insert log statement.</p>";
            }
        } else {
            echo "<p class='error'>Error updating student.</p>";
        }
    } else {
        echo "<p class='error'>Error preparing update statement.</p>";
    }
}
 else {
    // Redirect if accessed directly
    header("location: students-management.php");
    exit();
}
?>
