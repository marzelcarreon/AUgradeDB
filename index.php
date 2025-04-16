<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts management - Arellano University Subject Advising- AUSMS</title>
    <link rel="stylesheet" href="styless.css">
    <style>
        body {
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .fixed {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .login {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
            margin-top: 15px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        form {
            text-align: center;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc; 
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: blue; 
            color: white; 
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: darkblue; 
        }
        .error {
            color: red; 
            font-size: 16px;
        }
        .h1 {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .logo {
            display: block;
            margin: auto;
            width: 110px;
            margin-bottom: 10px;
        }
        .h3 {
            font-size: 30px;
        }
        .changepass{
            margin-left: 91%;
        }
    </style>
</head>
<body>
    
    <div class="fixed">
        <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano University Logo" class="logo">
        <h1>Arellano University - Jose Rizal Campus</h1>
    </div>
    <div class="changepass">
    <?php
                    session_start();
                        if ($_SESSION['usertype'] == "STUDENT") {
                echo "<a href='update-studentpassword.php'>Change Password</a>";
                }
        ?>
        </div>
    <div class="login">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> " method="POST" class="title__form">
            <?php 
             if (isset($_SESSION['username'])) {
                echo "<h1>Welcome, ";
                if ($_SESSION['usertype'] == "STUDENT") {
                    require_once "config.php";
                    $studentNumber = $_SESSION['username'];
                    $sql_name = "SELECT firstname FROM tblstudents WHERE studentnumber = ?";
                    if ($stmt_name = mysqli_prepare($link, $sql_name)) {
                        mysqli_stmt_bind_param($stmt_name, "s", $studentNumber);
                        if (mysqli_stmt_execute($stmt_name)) {
                            mysqli_stmt_store_result($stmt_name);
                            mysqli_stmt_bind_result($stmt_name, $firstName);
                            mysqli_stmt_fetch($stmt_name);
                            echo $firstName;
                        }
                    }
                    
                } else {
                    echo $_SESSION['username'];
                }
                echo "</h1>";
                echo "<h3>Account type: " .$_SESSION['usertype'] ."</h3>";
                if ($_SESSION['usertype'] == "ADMINISTRATOR") {
                    echo "<h3><a href='accounts-management.php'>Accounts Management</a></h3>";
                    echo "<h3><a href='students-management.php'>Students Management</a></h3>";
                    echo "<h3><a href='theses-management.php'>Thesis Management</a></h3>";
                }
                elseif ($_SESSION['usertype'] == "REGISTRAR" || $_SESSION['usertype'] == "STAFF") {
                    echo "<h3><a href='students-management.php'>Students Management</a></h3>";
                    echo "<h3><a href='theses-management.php'>Thesis Management</a></h3>";
                }
                elseif ($_SESSION['usertype'] == "STUDENT") {
                    echo "<h2><a href='grades-viewonly.php'>Grades</a></h2>";
                }
                echo "<h3><a href='#' onclick='confirmLogout();'>Logout</a></h3>";
            }
            else {
                // Redirect to the login page
                header("Location: login.php");
                exit(); // Add exit() after redirect
            }
            ?>   
        </form>
        <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                // Proceed with logout
                window.location.href = "logout.php";
            }
        }
    </script>
    </div>
</body>
</html>
