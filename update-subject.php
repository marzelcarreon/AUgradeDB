<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account - AU Subject Advising System - AUSMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .header h4 {
            font-size: 16px;
            margin-top: 0;
        }

        .myDiv {
            background-color: white;
            width: 50%;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        input[type="password"], select, input[type="radio"], input[type="submit"], a {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: darkblue;
        }

        a {
            text-decoration: none;
            color: blue;
        }

        a:hover {
            color: darkblue;
        }

        .error {
            color: red;
        }
        .marginleft{
            margin-left: 110px;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position: absolute; top: 10px; left: 20px;">
    <h1>Update the Subject</h1>
</div>


</div>
<div class="myDiv">
<?php
require_once "config.php";
include("session-checker.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Updating account
    $sql = "UPDATE tblsubjects SET description = ?, unit = ?, course = ? WHERE code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtdescription'], $_POST['cmbunit'], $_POST['cmbcourse'], $_GET['code']);
        if (mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Subject Management";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['code'], $action, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p>Subject updated successfully.</p>";
                    header("location: subjects-management.php");
                    exit();
                } else {
                    echo "<p class='error'>Error inserting log.</p>";
                }
            } else {
                echo "<p class='error'>Error preparing insert log statement.</p>";
            }
        } else {
            echo "<p class='error'>Error updating subject.</p>";
        }
    } else {
        echo "<p class='error'>Error preparing update statement.</p>";
    }
}

// Loading the current values of the subject
if (isset($_GET['code']) && !empty(trim($_GET['code']))) {
    $sql = "SELECT * FROM tblsubjects WHERE code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_GET['code']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            echo "<p class='error'>Error loading current values.</p>";
        }
    }
}
?>
    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST">
        <label>Code: <?php echo $account['code']; ?></label><br><br>
        <label>Description: <input type="text" name="txtdescription" value="<?php echo $account['description']; ?>" required></label><br><br>
        <label>Current Unit: <?php echo $account['unit']; ?></label><br>
        <label for="cmbunit">Change Unit to:</label>
        <select class="form-control" id="cmbunit" name="cmbunit" required>
                <option value="">--Select Units--</option>
                <option value="1">--1.00--</option>
                <option value="2">--2.00--</option>
                <option value="3">--3.00--</option>
                <option value="4">--4.00--</option>
                 <option value="5">--5.00--</option>
        </select><br><br>

        <label for="cmbcourse">Course:</label>
                    <select class="form-control" id="cmbcourse" name="cmbcourse" required>
                        <option value="">--Select Course--</option>
                        <option value="BSHM">--Bachelor of Science in Hospitality Management--</option>
                        <option value="BSTM">--Bachelor of Science in Tourism Management--</option>
                        <option value="BSBA">--Bachelor of Science in Business Administration--</option>
                        <option value="BAEPPS">--Bachelor of Arts in English Language, Psychology, Political Science--</option>
                        <option value="BSE">--Bachelor of Secondary Education--</option>
                        <option value="BEE">--Bachelor of Elementary Education--</option>
                        <option value="BPE">--Bachelor of Physical Education--</option>
                        <option value="BSCS">--Bachelor of Science in Computer Science--</option>
                    </select>
        <center>
        <input type="submit" name="btnsubmit" value="Update">
        <a href="subjects-management.php">Cancel</a>
        </center
    </form>
</div>
</body>
</html>
