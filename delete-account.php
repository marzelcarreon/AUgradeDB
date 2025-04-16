<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account - AU Subject Advising System - AUSMS</title>
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

        input[type="text"], input[type="submit"], a {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: darkred;
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
    </style>
</head>
<body>
<div class="header">
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position: absolute; top: 10px; left: 20px;">
    <h1>Delete Account</h1>
</div>

<div class="myDiv">
    <?php
    require_once "config.php";
    include("session-checker.php");

    if(isset($_POST['btnsubmit'])) {
        $sql = "DELETE FROM tblaccounts WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt,"s", $_POST['txtusername']);
            if(mysqli_stmt_execute($stmt)) {
                $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("m/d/Y");
                    $time = date("h:i:sa");
                    $module = "Accounts";
                    $action = "Delete";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtusername'], $action, $_SESSION['username']);
                    if(mysqli_stmt_execute($stmt)) {
                        echo "<p>User account deleted successfully.</p>";
                        header("location: accounts-management.php");
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="txtusername" value="<?php echo trim($_GET['username']); ?>" />
        <center>
        <p>Are you sure you want to delete this account?</p><br>
        <input type="submit" name="btnsubmit" value="Yes">
        <a href="accounts-management.php">No</a>
    </form>
</div>

</body>
</html>
