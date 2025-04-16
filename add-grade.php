<?php
$error="";
require_once "config.php";
include("session-checker.php");
if(isset($_POST['btnsubmit'])){
         //create account
         $sql = "SELECT * FROM tblgrades WHERE subjectcode = ? AND studentnumber = ?";
         if($stmt = mysqli_prepare($link, $sql)){
             mysqli_stmt_bind_param($stmt, "ss", $_POST['cmbsubjectcode'], $_GET['studentnumber']);
             if(mysqli_stmt_execute($stmt)){
                 $result = mysqli_stmt_get_result($stmt);
                 if(mysqli_num_rows($result) == 0){
         $sql = "INSERT INTO tblgrades (studentnumber, subjectcode, grade, encodedby, dateencoded) VALUES (?, ?, ?,?,?)";
         if($stmt = mysqli_prepare($link, $sql)){
            $date = date ("m/d/Y");
            mysqli_stmt_bind_param($stmt, "sssss", $_GET['studentnumber'], $_POST['cmbsubjectcode'], $_POST['cmbgrade'], $_SESSION['username'], $date);
            if(mysqli_stmt_execute($stmt))
            {
                $sql = "INSERT INTO tbllogs (date, time, module, ID, action,  performedby) VALUES (?, ?, ?, ?, ?, ?)";
           
           if($stmt = mysqli_prepare($link, $sql)){
            $date = date ("m/d/Y");
            $time = date ("h:i:sa");
            $module = "Grade Management";
            $action = "Create";
            $ID = $_GET['studentnumber'];
            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module,$ID, $action, $_SESSION['username']);
            if(mysqli_stmt_execute($stmt)){
                echo '<script>
                alert ("Grade Created");
                window.location.href="grades-management.php";
                </script>';
            }
            else{
                echo "<font color = 'red'> Error on insert log.</font>";
            }
  
            }}
            else{
                echo "<font color = 'red' > Error on inserting account.</font>";
            }
        }
    }
    else {
        echo '<script>
        alert ("Grade already exists for this student and subject code");
        window.location.href="grades-management.php";
        </script>';
    }
         }
    }
}
   
    else {//loading the current values of the account
        if(isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))){
            $sql = "SELECT * FROM tblstudents WHERE studentnumber = ?";
            if($stmt = mysqli_prepare($link,$sql)){
                mysqli_stmt_bind_param($stmt, "s", $_GET['studentnumber']);
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $query = "SELECT * FROM tblsubjects WHERE course ='$account[course]'";
                $resulta = mysqli_query($link,$query);
            }

            }
        }
            $sql = "SELECT * FROM tblsubjects";
            if($stmt = mysqli_prepare($link,$sql)){
                
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                $subject = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }
            
            }
     }
      
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grade - Arellano University Subject Advising - AUSMS</title>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <!-- CSS Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin: 50px auto; /* Center the container and add space at the top */
        }
        h2 {
            margin-top: 0;
            font-size: 18px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }
        select,
        input[type="text"] {
            width: calc(100% - 10px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }
        select {
            background-color: #fff;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            display: block;
            text-align: center;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
        .display-info {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 40px;
            padding-bottom: 30px; /* Reduced padding */
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 9999;
        }
        .header h2 {
            color: white;
            font-size: 20px; /* Reduced font size */
            padding-right: 80px;
        }
        .header h4 {
            font-size: 14px;
            margin-top: 0;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 30px;
            width: 75px;
            height: 75px;
        }

    </style>
</head>
<body>
    <div class="header">
        <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" class="logo">
        <h2>Create Student Grade</h2>
    </div>
    <div class="container">
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">
            <h2>Add Grade</h2>
            <div class="display-info">
                <label for="studentNumber">Student Number:</label>
                <span><?php echo $account['studentnumber']; ?></span>
            </div>
            <div class="display-info">
                <label for="studentName">Name:</label>
                <span><?php echo $account['firstname'] . " " . $account['middlename'] . " " . $account['lastname']; ?></span>
            </div>
            <div class="display-info">
                <label for="course">Course:</label>
                <span><?php echo $account['course']; ?></span>
            </div>
            <div class="display-info">
                <label for="yearLevel">Year Level:</label>
                <span><?php echo $account['yearlevel']; ?></span>
            </div>
            <div class="display-info">
                <label for="subjectDescription">Description:</label>
                <span><?php echo $subject['description']; ?></span>
            </div>
            <div class="display-info">
                <label for="subjectUnit">Unit:</label>
                <span><?php echo $subject['unit']; ?></span>
            </div>
            <label for="subjectCode">Subject Code:</label>
            <select name="cmbsubjectcode" id="cmbsubjectcode" required>
                <?php while ($row1 = mysqli_fetch_array($resulta)): ?>
                    <option value="<?php echo $row1[0]; ?>"><?php echo $row1[0]; ?></option>
                <?php endwhile; ?>
            </select>
            <label for="grade">Grade:</label>
            <select name="cmbgrade" id="cmbgrade" required>
                <option value="">--Select grade--</option>
                <option value="1.00">1.00</option>
                <option value="1.25">1.25</option>
                <option value="1.50">1.50</option>
                <option value="1.75">1.75</option>
                <option value="2.00">2.00</option>
                <option value="2.25">2.25</option>
                <option value="2.50">2.50</option>
                <option value="2.75">2.75</option>
                <option value="3.00">3.00</option>
            </select>
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="grades-management.php">Cancel</a>
            <span class="error"><?php echo $error; ?></span>
        </form>
    </div>
</body>
</html>
