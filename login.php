<html>
<head>
    <title>Login Page - Arellano University Subject Advising - AUSMS</title>
    <style>
        body {
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            padding-top: 4vw;
            color: #333;
        }
        form {
            background-color: #f2f2f2; 
            padding: 20px;
            border-radius: 10px;
            width: 50%;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc; 
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: blue; 
            color: white; 
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: darkblue; 
        }
        .error {
            color: red; 
            font-size: 16px;
        }
        h1 {
            margin-bottom: 10px;
        }
        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 150px; /* Adjust logo width as needed */
            margin-bottom: 20px; /* Added margin for spacing */
        }
    </style>
</head>
<body>
    <center>
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano University Logo" class="logo">
    <h1>Arellano University Digital Archive System</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            Username: <input type="text" name="txtusername" required> <br> <br>
            Password: <input type="password" name="txtpassword" required> <br> <br>
            <input type="submit" name="btnlogin" value="Login">
        </form>
    </center>
</body>
</html>
<?php
if (isset($_POST['btnlogin'])){
    require_once "config.php";
    $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND userstatus = 'ACTIVE'";
    if ($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);

        if(mysqli_stmt_execute($stmt)) {

            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) > 0) {
                //fetch  the result into an array
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                //create session
                session_start();
                //record session
                $_SESSION['username'] = $_POST['txtusername'];
                $_SESSION['usertype'] = $account['usertype'];
                //redirect the accounts page
                header("Location: index.php");
                exit();
            }
            else{
                echo "<center><div class='error'>Incorrect login details or account is inactive</div></center>";
            }
        }
        else {
            echo "<center><div class='error'>Error on the login statement</div></center>";
        }
    } 
}
?>
