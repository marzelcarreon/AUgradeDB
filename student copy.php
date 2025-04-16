<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts-management Arellano Subject Advising System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightgray;
            padding: 20px;
        }
        h1 {
            color: #000;
            text-align: center;
        }
        h4 {
            color: #666;
            text-align: center;
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        input[type="text"], input[type="submit"] {
            padding: 8px;
            margin-right: 10px;
            border-radius: 5px;
            border: none;
        }
        input[type="submit"] {
            background-color: lightblue;
            cursor: pointer;
        }
        a {
            text-decoration: none;
            color: blue;
            margin-right: 10px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        th {
            background-color: lightblue;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
 session_start();
 // Check if there is a session
 if(isset($_SESSION['username'])) {
     echo "<h1>Welcome, " . $_SESSION['username'] . "</h1>";
     echo "<h4>Account type: " . $_SESSION['usertype'] . "</h4>";
 }
 else {
     // Redirect to the login page
     header("Location: login.php");
 }

require_once "config.php";

// Function to build HTML table from query result
function buildTable($result) {
    if (mysqli_num_rows($result) > 0) {
        // Create the table using HTML
        echo "<table>";
        // Create the header of the table
        echo "<tr>";
        echo "<th>Student Number</th><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Course</th><th>Year Level</th><th>Created By</th><th>Date Created</th><th>Action</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['studentnumber']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['middlename']."</td>";
            echo "<td>".$row['course']."</td>";
            echo "<td>".$row['yearlevel']."</td>";
            echo "<td>".$row['createdby']."</td>";
            echo "<td>".$row['datecreated']."</td>";
            echo "<td>";
            echo "<a href='update-student.php?studentnumber=".$row['studentnumber']."'>Update</a> | "; 
            echo "<a href='delete-student.php?studentnumber=".$row['studentnumber']."'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <a href="create-account.php">Create new account</a>
    <a href="logout.php">Logout</a>
    <br>Search: <input type="text" name="txtsearch">
    <input type="submit" name="btnsearch" value="Search">
</form>

<?php
if (isset($_POST['btnsearch'])) {
    $searchvalue = '%' . $_POST["txtsearch"] . '%';
    $sql = "SELECT * FROM tblstudents WHERE Course LIKE ? OR studentnumber LIKE ? OR lastname LIKE ? OR middlename LIKE ? OR firstname LIKE ? OR yearlevel LIKE ? ORDER BY lastname";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssss", $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue, $searchvalue);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            buildtable($result);
        } else {
            echo "Error executing search query";
        }
    }
} else {
    // Load the data from the accounts table if btnsearch is not set
    $sql = "SELECT * FROM tblstudents ORDER BY lastname";
    if ($stmt = mysqli_prepare($link, $sql)) {
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            buildtable($result);
        } else {
            echo "Error loading accounts data";
        }
    }
}
?>

</body>
</html>