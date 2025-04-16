<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Password - AU Subject Advising System - AUSMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .myDiv {
            background-color: white;
            width: 35%;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        input[type="password"], input[type="submit"], a {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            width: calc(100% - 22px); /* Adjusted width */
            box-sizing: border-box;
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
            display: block;
            text-align: center;
        }

        a:hover {
            color: darkblue;
        }

        .error {
            color: red;
            text-align: center;
        }

        .eye-icon {
            width: 20px;
            height: auto;
            cursor: pointer;
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
        }
        .input-group {
            position: relative;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75">
    <h1>Change Password</h1>
</div>

<div class="myDiv">
    <?php
    require_once "config.php";
    include("session-checker.php");

    // Initialize $account with empty values
    $account = [
        'username' => '',
        'password' => ''
    ];

    // Check if the username is set in the session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Retrieve the account based on the session username
        $sql = "SELECT * FROM tblaccounts WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) > 0) {
                    // Fetch the account details
                    $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                } else {
                    echo "<p class='error'>User account not found.</p>";
                }
            } else {
                echo "<p class='error'>Error executing query.</p>";
            }
        } else {
            echo "<p class='error'>Error preparing statement.</p>";
        }
    } else {
        echo "<p class='error'>Username not found in session.</p>";
    }

    // Check if form is submitted
    if (isset($_POST['btnsubmit'])) {
        $newPassword = $_POST['txtpassword'];
        $retypePassword = $_POST['txtretypepassword'];

        // Check if passwords match
        if ($newPassword !== $retypePassword) {
            echo "<p class='error'>Passwords do not match.</p>";
        } else {
            // Updating account
            $sqlUpdate = "UPDATE tblaccounts SET password = ? WHERE username = ?";
            if ($stmtUpdate = mysqli_prepare($link, $sqlUpdate)) {
                mysqli_stmt_bind_param($stmtUpdate, "ss", $newPassword, $username);
                if (mysqli_stmt_execute($stmtUpdate)) {
                    // Inserting log
                    $sqlLog = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                    if ($stmtLog = mysqli_prepare($link, $sqlLog)) {
                        $date = date("m/d/Y");
                        $time = date("h:i:sa");
                        $module = "Student Password";
                        $action = "Update";
                        mysqli_stmt_bind_param($stmtLog, "ssssss", $date, $time, $module, $username, $action, $_SESSION['username']);
                        if (mysqli_stmt_execute($stmtLog)) {
                            echo '<div class="alert alert-success" role="alert">Password Changed!</div>';
                            echo '<script>showAlert("Password changed successfully!");</script>';
                            header("Location: index.php");
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
        }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST">
        <div class="form-group">
            <label for="txtpassword">Student Number: <?php echo $account['username']; ?></label>
        </div>
        <div class="form-group">
            <label for="txtpassword">New Password:</label>
            <div class="input-group">
                <input type="password" class="form-control" id="txtpassword" name="txtpassword" value="<?php echo $account['password']; ?>" required>
                <div class="input-group-append">
                    <span class="input-group-text" id="togglePassword">
                        <img src="https://static.thenounproject.com/png/4334035-200.png" alt="Show Password" id="eyeIcon" class="eye-icon" onclick="togglePasswordVisibility()">
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="txtretypepassword">Retype New Password:</label>
            <div class="input-group">
                <input type="password" class="form-control" id="txtretypepassword" name="txtretypepassword" required>
            </div>
        </div>
        <br>
        <center>
            <input type="submit" name="btnsubmit" value="Update">
            <a href="index.php">Cancel</a>
        </center>
    </form>
</div>
<script>
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

    function showAlert(message) {
        alert(message);
    }
</script>
</body>
</html>
