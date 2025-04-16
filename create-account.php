<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create new account - AU Subject Advising System</title>
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

        input[type="text"], input[type="password"], select, input[type="submit"], a {
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
        #togglePassword {
            position: relative;
            cursor: pointer;
        }

         #eyeIcon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: auto;
          }
    </style>
</head>
<body>
<div class="header">
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position: absolute; top: 10px; left: 20px;">
    <h1>Create new account</h1>
</div>

<div class="myDiv">
    <?php
    require_once "config.php";
    include ("session-checker.php");

    if(isset($_POST['btnsubmit']))
    {
        $sql = "SELECT * FROM tblaccounts WHERE username = ?"; //check if the username is already existing
        if($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
            if(mysqli_execute($stmt))
            {
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 0)
                {
                    // Check if passwords match
                    if($_POST['txtpassword'] !== $_POST['txtretypepassword']) {
                        echo "<p class='error'>Passwords do not match.</p>";
                        exit(); // Stop execution if passwords don't match
                    }
    
                    //create account
                    $sql = "INSERT INTO tblaccounts (username, password, usertype, userstatus, createdby, datecreated) VALUES (?, ?, ?, ?, ?, ?)";
                    if($stmt = mysqli_prepare($link, $sql))
                    {
                        $status = "ACTIVE";
                        $date =  date("m/d/Y");
                        mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtusername'], $_POST['txtpassword'], $_POST['cmbtype'], $status, $_SESSION['username'], $date);
                        if(mysqli_stmt_execute($stmt))
                        {
                            $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                            if($stmt = mysqli_prepare($link, $sql))
                            {
                                $date = date("m/d/Y");
                                $time = date("h:i:sa");
                                $module = "Accounts";
                                $action = "Create";
                                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtusername'], $action, $_SESSION['username']);
                                if(mysqli_stmt_execute($stmt))
                                {
                                    echo "<p>User account added!</p>";
                                    header("location: accounts-management.php");
                                    exit();
                                }
                                else
                                {
                                    echo "<p class='error'>Error on insert log.</p>";
                                }
                            }
                        }
                        else
                        {
                            echo "<p class='error'>Error in inserting account.</p>";
                        }
                    }
                }
                else
                {
                    echo "<p class='error'>Username is already in use.</p>";
                }
            }
            else
            {
                echo "<p class='error'>Error on checking username.</p>";
            }
        }
    }
    
    ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()">
    <label>Username: <input type="text" name="txtusername" required></label><br>
    <label>Password: <input type="password" name="txtpassword" id="password" required>
        <span id="togglePassword" onclick="togglePasswordVisibility()">
            <img src="https://static.thenounproject.com/png/4334035-200.png" alt="Show Password" id="eyeIcon">
        </span>
    </label><br>
    <label>Re-type Password: <input type="password" name="txtretypepassword" id="retype_password" required></label><br>
    <label>Account type: 
        <select name="cmbtype" id="cmbtype" required>
            <option value="">--Select Account type--</option>
            <option value="ADMINISTRATOR">--ADMINISTRATOR--</option>
            <option value="REGISTRAR">--REGISTRAR--</option>
            <option value="STAFF">--STAFF--</option>
        </select>
    </label><br>
        <center>
        <input type="submit" name="btnsubmit" value="Submit">
        <a href="accounts-management.php">Cancel</a>
        </center>
    </form>
    <script>
  function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.src = "https://icons.veryicon.com/png/o/miscellaneous/computer-room-integration/hide-password.png";
        } else {
            passwordInput.type = "password";
            eyeIcon.src = "https://static.thenounproject.com/png/4334035-200.png";
        }
    }

    function validateForm() {
        var password = document.getElementById("password").value;
        var retypePassword = document.getElementById("retype_password").value;

        if (password !== retypePassword) {
            alert("Passwords do not match");
            return false;
        }

        return true;
    }
</script>
</div>
</body>
</html>
