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
            /* Eye icon */
    .eye-icon {
        position: absolute;
        right: 480px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px; 
        height: auto; 
        cursor: pointer; 
    }
        .input-group {
    position: relative;
}


    </style>
</head>
<body>
<div class="header">
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position: absolute; top: 10px; left: 20px;">
    <h1>Update the Account</h1>
</div>


</div>
<div class="myDiv">
    <?php
    require_once "config.php";
    include("session-checker.php");

    if (isset($_POST['btnsubmit'])) {
        // Updating account
        $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, userstatus = ? WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);
            if (mysqli_stmt_execute($stmt)) {
                $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $date = date("m/d/Y");
                    $time = date("h:i:sa");
                    $module = "Accounts Management";
                    $action = "Update";
                    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['username'], $action, $_SESSION['username']);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<p>User account updated successfully.</p>";
                        header("location: accounts-management.php");
                        exit();
                    } else {
                        echo "<p class='error'>Error inserting log.</p>";
                    }
                } else {
                    echo "<p class='error'>Error preparing insert log statement.</p>";
                }
            } else {
                echo "<p class='error'>Error updating account.</p>";
            }
        } else {
            echo "<p class='error'>Error preparing update statement.</p>";
        }
    } else { // Loading the current values of the account
        if (isset($_GET['username']) && !empty(trim($_GET['username']))) {
            $sql = "SELECT * FROM tblaccounts WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                } else {
                    echo "<p class='error'>Error loading current values.</p>";
                }
            }
        }
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST">
        <label>Username: <?php echo $account['username']; ?></label><br>
        <!-- Inside the form -->
<div class="form-group">
    <label for="txtpassword">Password:</label>
    <div class="input-group">
        <input type="password" class="form-control" id="txtpassword" name="txtpassword" value="<?php echo $account['password']; ?>" required>
        <div class="input-group-append">
            <span class="input-group-text" id="togglePassword">
                <img src="https://static.thenounproject.com/png/4334035-200.png" alt="Show Password" id="eyeIcon" class="eye-icon" onclick="togglePasswordVisibility()">
            </span>
        </div>
    </div>
</div>
        <label>Current user type: <?php echo $account['usertype']; ?></label><br>
        <label>Change user type to:
            <select name="cmbtype" id="cmbtype" required>
                <option value="">-- Select Account type --</option>
                <option value="ADMINISTRATOR">Administrator</option>
                <option value="REGISTRAR">Registrar</option>
                <option value="STUDENT">Student</option>
            </select>
        </label><br>
        <label>Current Status:<br>
        <div class="marginleft">
            <?php
            $status = $account['userstatus'];
            if ($status == "ACTIVE") {
                echo "&nbsp;<input type='radio' name='rbstatus' value='ACTIVE' checked> Active<br>";
                echo "&nbsp;<input type='radio' name='rbstatus' value='INACTIVE'> Inactive<br>";
            } else {
                echo "<input type='radio' name='rbstatus' value='ACTIVE' > Active<br>";
                echo "<input type='radio' name='rbstatus' value='INACTIVE' checked> Inactive<br>";
            }
            ?>
            </div>
        </label><br>
        <center>
        <input type="submit" name="btnsubmit" value="Update">
        <a href="accounts-management.php">Cancel</a>
        </center
    </form>
</div>
</body>
<script>
    // Function to toggle password visibility
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("txtpassword");
        var eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.src = "https://icons.veryicon.com/png/o/miscellaneous/computer-room-integration/hide-password.png";
        } else {
            passwordInput.type = "password";
            eyeIcon.src = "https://static.thenounproject.com/png/4334035-200.png";
        }
    }
</script>

</html>
