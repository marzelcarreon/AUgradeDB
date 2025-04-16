<?php
require_once "config.php";
include("session-checker.php");

$grade = []; // Initialize $grade to avoid undefined variable error

if(isset($_POST['btnsubmit'])) { //update-grade
    $sql = "UPDATE tblgrades SET grade = ? WHERE studentnumber = ? AND subjectcode = ?";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $_POST['cmbgrade'], $_POST['txtstudentnumber'], $_POST['txtcode']);
        if(mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Grade Management";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_POST['txtstudentnumber'], $action, $_SESSION['username']);
                if(mysqli_stmt_execute($stmt)) {
                    echo "<font color = 'green'>Grade updated!</font>";
                    header("location: grades-management.php");
                    exit();
                } else {
                    echo "<font color = 'red'>Error on inserting log.</font>";
                }
            }
        } else {
            echo "<font color = 'red'>Error on updating grade.</font>";
        }
    }
}else { // loading the current values of the account
    if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber'])) && isset($_GET['code']) && !empty(trim($_GET['code']))) {
        $sql = "SELECT stud.studentnumber, stud.lastname, stud.firstname, stud.middlename, stud.course, stud.yearlevel, g.*, subj.code, subj.description, subj.unit 
                FROM tblstudents stud 
                LEFT JOIN tblgrades g ON g.studentnumber = stud.studentnumber 
                LEFT JOIN tblsubjects subj ON g.subjectcode = subj.code 
                WHERE g.studentnumber = ? AND g.subjectcode = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $_GET['studentnumber'], $_GET['code']);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $grade = mysqli_fetch_assoc($result);
            } else {
                echo "<font color='red'>Error on loading the current account values: " . mysqli_error($link) . "</font>";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Grade - AU system</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 40%;
            margin: auto;
            margin-top: 100px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            width: 100%;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"], a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover, a:hover {
            background-color: #0056b3;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 75px;
            height: 75px;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 30px;
            padding-bottom: 50px;
            text-align: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 9999;
        }
        .header h2 {
            margin-bottom: 0;
        }
        .header h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }
        .header h4 {
            font-size: 16px;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" class="logo">
        <h2>Update Student Grade</h2>
    </div>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group row">
                <label for="txtstudentnumber" class="col-sm-2 col-form-label">Student Number:</label>
                <div class="col-sm-10">
                    <input type="text" id="txtstudentnumber" name="txtstudentnumber" value="<?php echo isset($grade['studentnumber']) ? $grade['studentnumber'] : '';?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="txtname" class="col-sm-2 col-form-label">Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="txtname" name="txtname" value="<?php echo isset($grade['lastname']) ? $grade['lastname'] : '';?> <?php echo isset($grade['firstname']) ? $grade['firstname'] : '';?> <?php echo isset($grade['middlename']) ? $grade['middlename'] : '';?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="txtcourse" class="col-sm-2 col-form-label">Course:</label>
                <div class="col-sm-10">
                    <input type="text" id="txtcourse" name="txtcourse" value="<?php echo isset($grade['course']) ? $grade['course'] : '';?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="txtyearlevel" class="col-sm-2 col-form-label">Year:</label>
                <div class="col-sm-10">
                    <input type="text" id="txtyearlevel" name="txtyearlevel" value="<?php echo isset($grade['yearlevel']) ? $grade['yearlevel'] : '';?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="txtcode" class="col-sm-2 col-form-label">Subject:</label>
                <div class="col-sm-10">
                    <input type="text" id="txtcode" name="txtcode" value="<?php echo isset($grade['code']) ? $grade['code'] : '';?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="cmbgrade" class="col-sm-2 col-form-label">Grade:</label>
                <div class="col-sm-10">
                    <select name="cmbgrade" id="cmbgrade" oninput="realtimeDesc()" required>
                        <option value="">--Select Grade--</option>
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
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2"><center>
                    <input type="submit" name="btnsubmit" value="Submit">
                    <a href="grades-management.php">Cancel</a>
                     </center>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
