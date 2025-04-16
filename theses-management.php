<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User theses</title>
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
    background-image: url('https://img.freepik.com/free-vector/whiteabstract-background_23-2148810113.jpg');
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
<img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-
seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position:
absolute; top: 20px; left: 40px;">
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
include('create-theses-modal.php');
include('process-theses-creation.php');
include('delete-theses-modal.php');
?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
method="POST">
<a href="#" data-toggle="modal" data-target="#createthesesModal">Add new theses</a>&nbsp;&nbsp;
<a href="logout.php" onclick="confirmLogout(); return false;">Logout</a>
<br>Search: <input type="text" name="txtsearch">
<input type="submit" name="btnsearch" value="Search">
</form>
<?php
require_once "config.php";
function buildtable($result) {
if(mysqli_num_rows($result) > 0) {
        // Create the table using HTML
        echo "<table>";
        // Create the header of the table
        echo "<tr>";
        echo "<th>Number ID</th><th>Title</th><th>Authors</th><th>Research Adviser</th><th>User type</th><th>File link</th><th>Created by</th><th>Date submitted</th><th>Action</th>";
        echo "</tr>";
        while($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>".$row['numID']."</td>";
        echo "<td>".$row['title']."</td>";
        echo "<td>".$row['authors']."</td>";
        echo "<td>".$row['researchadviser']."</td>";
        echo "<td>".$row['usertype']."</td>";
        echo "<td>".$row['filelink']."</td>";
        echo "<td>".$row['createdby']."</td>";
        echo "<td>".$row['datesubmitted']."</td>";
        echo "<td class='action-links'>";
        echo '<a href="#" class="delete-link" onclick="confirmDelete(\''.$row['numID'].'\')">Delete</a>';
        echo "</td>";
        echo "</tr>";
}
    echo "</table>";
} else {
    echo "No record/s found.";
}
}
if(isset($_POST['btnsearch'])) {
    $searchvalue = '%' . $_POST["txtsearch"] . '%';
    $sql = "SELECT * FROM tbltheses WHERE numID LIKE ? OR description LIKE
    ? ORDER BY numID";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
        if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        buildtable($result);
    } else {
    echo "Error executing search query";
    }
}
}   else {
    // Load the data from the thesess table if btnsearch is not set
    $sql = "SELECT * FROM tbltheses ORDER BY numID";
    if($stmt = mysqli_prepare($link, $sql)) {
    if(mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    buildtable($result);
}    else {
        echo "Error loading theses data";
    }
}
}
?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
    window.location.href = "logout.php";
    }
    }
    function confirmDelete(numID) {
    $('#deletethesesInput').val(numID);
    $('#deletethesesModal').modal('show');
  }   
</script>
</body>
</html>