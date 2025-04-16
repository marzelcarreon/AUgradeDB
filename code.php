<?php
require_once "config.php";
include("session-checker.php");

if (isset($_POST['btnsubmit'])) {
    // updating account
    $sql = "UPDATE tblgrades SET grade = ? WHERE studentnumber = ? AND code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $_POST['grade'], $_GET['studentnumber'], $_GET['code']);
        if (mysqli_stmt_execute($stmt)) {   
            $sql = "INSERT INTO tbllogs (date, time, module, ID, action, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $module = "Grades";
                $action = "Update";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $module, $_GET['studentnumber'], $action, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['updated'] = "Student Grade UPDATED!";
                    header("location: grades-management.php");
                    exit();
                }

                else{
                $_SESSION['error'] = "<font color = 'red'>Error on insert log. </font>";
                header("location: grades-management.php");
            exit();
                }
            }
        }
        else{
            $_SESSION['error'] = "<font color = 'red'>Error on grading student. </font>";
            header("location: grades-management.php");
        exit();
        }
    }

}

else{ // loading the current values of the account

    if (isset($_GET['studentnumber']) && !empty(trim($_GET['studentnumber']))) {
        $sql = "SELECT tblstudents.*, tblgrades.code, tblgrades.grade, tblsubjects.description 
                FROM tblstudents 
                LEFT JOIN tblgrades 
                ON tblgrades.studentnumber = tblstudents.studentnumber
                LEFT JOIN tblsubjects 
                    ON tblsubjects.code = tblgrades.code 
                WHERE tblstudents.studentnumber = ? AND tblgrades.grade = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $_GET['studentnumber'], $_GET['grade']);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }
            else{
                echo "<font color = 'red'> Error on loading th current account values</font>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Update Grade - Arellano Subject Advising System</title>
</head>
<style>
    form{
        font-family: times-new-roman;
    }
</style>

<body>

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST">

        <center>
        
        <p>Change the value on this form and submit to update the Grade</p> <br>

        </center>
        
        <br>

        <input type="hidden" name="txtcode" value="<?php echo trim($_GET['code']); ?>">
        <input type="hidden" name="txtstudentnum" value="<?php echo trim($_GET['studentnumber']); ?>">

Student #: <?php echo $account['studentnumber'];?> <br>

Name: <?php echo $account['lastname'] . ", " . $account['firstname'] . " " . $account['middlename'];  ?><br>

Course: <?php echo $account['course'];  ?><br>

Year level: <?php echo $account['yearlevel'];  ?><br><br>

Select Subject : <?php echo $account['code'];  ?><br>

Description : <?php echo $account['description'];  ?><br><br>

Current Grade : <?php echo $account['grade'];  ?><br> 

Select Grade: <select name="grade" id="grade" required>

        <option value="">--Select Grade--</option>
        <option value="3.00">3.00</option>
        <option value="2.75">2.75</option>
        <option value="2.50">2.50</option>
        <option value="2.25">2.25</option>
        <option value="2.00">2.00</option>
        <option value="1.75">1.75</option>
        <option value="1.50">1.50</option>
        <option value="1.25">1.25</option>
        <option value="1.00">1.00</option>

        </select><br><br>


        <br>
<center>
        <input type="submit" name="btnsubmit" class = 'btn btn-success' value="Update">
        <a href="grades-management.php" class = 'btn btn-default'>Cancel</a>
</center>
    </form>

    <script src="password-script.js"></script>
    <script src="description-script.js"></script>

</body>
</html>