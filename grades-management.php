<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #8c8c8c;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #d9d9d9;
        }
        tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ccc;
        }
        .action-links a {
            margin-right: 5px;
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
        form {
            margin-top: 20px;
            text-align: center;
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: darkblue;
        }
        body {
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');   
            background-repeat: no-repeat;
            background-attachment: fixed; 
            background-size: 100% 100%; 
        }
        .menu {
        position: absolute;
        top: 50x;
        right: 15px;
    }
    </style>
    
</head>
<body>
<div class="header">
    <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position: absolute; top: 20px; left: 35px;">
    <div class="menu">
    <a href="index.php" class="btn btn-primary">Menu</a>
    </div>
    <?php
        session_start();
        //check if there is a session
        if(isset($_SESSION['username'])) {
            echo "<h1>Welcome, " . $_SESSION['username'] . "</h1>";
            echo "<h4>Account type: " . $_SESSION['usertype'] . "</h4>";
        }
        else {
            // Redirect to the login page
            header("Location: login.php");
            exit(); // Add exit() after redirect
        }
    ?>
</div>

<?php
include('delete-grade-modal.php');


    // Include PHP block for handling form submission
    require_once "config.php";

    
?>
<?php
    // Assuming $_POST['btnsearch'] contains the desired student number
    $studentNumber = isset($_POST['btnsearch']) ? $_POST['btnsearch'] : '';
?>
<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <a href="logout.php" onclick="confirmLogout(); return false;">Logout</a><br>
        <input type="text" placeholder="Search:" name = "txtsearch"  class="login__input">
        <input type="submit" name="btnsearch" value="Search">
    </form>
    <?php
    require_once "config.php";
    function buildtable($result) {
    if(mysqli_num_rows($result) > 0) {
    $firstRow = true;
    //display data to the table
    while($row = mysqli_fetch_array($result)) {
    if($firstRow) {
    echo "Student Number: " . $row['studentnumber'] . "<br>";
    echo "Name: " . $row['firstname'] ." ". $row['middlename'] . " " . $row['lastname'] . "<br>";
    echo "Course: " . $row['course'] . "<br>";
    echo "Year Level: " . $row['yearlevel'] . "<br>";
    //table for buildtable
    echo "<a href='add-grade.php?studentnumber=".$_POST['txtsearch']."' style='float: right;'>Add Grade</a>";
    echo "<table id='buildtable'>";
    echo "<tr>";
    echo "<th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th width='10%'>Encoded by</th><th width='20%'>Date Encoded</th><th>Actions</th>";
    echo "</tr>";
    $firstRow = false;
    }
    
    echo "<tr>";
    echo "<td style='text-align: left;'>" . $row['code'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td class='unit-value'>" . $row['unit'] . "</td>";
    echo "<td class='grade-value'>" . $row['grade'] . "</td>";
    echo "<td>" . $row['encodedby'] . "</td>";
    echo "<td>" . $row['dateencoded'] . "</td>";
    echo "<td class='action-links'>";
    echo "<a class='fit-button-update' href='update-grade.php?studentnumber=" . $row['studentnumber'] . "&code=" . $row['code'] . "'>Update</a>";
    echo '<a href="#" class="delete-link" onclick="confirmDelete(\''.$row['studentnumber'].'\', \''.$row['code'].'\', \''.$row['grade'].'\')">Delete</a>';

    echo "</td>";
    echo "</tr>";
    }
    echo "</td></tr></table>";
    }
    else {
    //display empty data to the table
    echo "No student found";
    echo "</td></tr></table>";
    }
    }

//search
if(isset($_POST['btnsearch'])) {
    $sql = "SELECT stud.studentnumber, stud.lastname, stud.firstname, stud.middlename, stud.course, stud.yearlevel, g.grade, g.encodedby, g.dateencoded, subj.code, subj.description, subj.unit FROM tblstudents stud
            LEFT JOIN tblgrades g ON g.studentnumber = stud.studentnumber
    LEFT JOIN tblsubjects subj ON g.subjectcode = subj.code
            WHERE stud.studentnumber = ? ORDER by subj.code";
    if ($stmt = mysqli_prepare($link, $sql)) {
    $searchvalue = $_POST['txtsearch'];
    mysqli_stmt_bind_param($stmt, "s", $searchvalue);
    if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    buildtable($result);
    }
    }
    else {
    echo "Error on search";
    }
    }
    else {
    //load empty data from the grades table
    $sql = "SELECT * FROM tblgrades WHERE 1=0";
    if($stmt = mysqli_prepare($link, $sql)) {
    if(mysqli_stmt_execute($stmt)) {
    $student = mysqli_stmt_get_result($stmt);
    buildtable($student);
    }
    }
    else {
    echo "Error on grades load";
    }
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "logout.php";
        }
    }

  // Function to set studentnumber and show delete modal
  function confirmDelete(studentnumber, code, grade) {
    $('#deletegradeStudentNumber').val(studentnumber);
    $('#deletegradeCode').val(code);
    $('#deletegradeGrade').val(grade);
    $('#deletegradeModal').modal('show');
  }
</script>
</body>
</html>

