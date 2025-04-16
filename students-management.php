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
// Include the modal content
include('create-student-modal.php');
include('delete-student-modal.php');
include('update-student-modal.php');


    // Include PHP block for handling form submission
    require_once "config.php";

    
?>

<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <a href="#" data-toggle="modal" data-target="#createStudentModal">Create Student account</a>&nbsp;&nbsp;
        <a href="logout.php" onclick="confirmLogout(); return false;">Logout</a>
        <br>Search: <input type="text" name="search">
        <input type="submit" name="btnsearch" value="Search">
    </form>
    <?php
    require_once "config.php";

    function buildtable($result) {
        if(mysqli_num_rows($result) > 0) {
            // Create the table using HTML
            echo "<br><table>";
            // Create the header of the table
            echo "<tr>";
            echo "<th>Student Number</th><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Course</th><th>Year Level</th><th>Created By</th><th>Date Created</th><th>Action</th>";
            echo "</tr>";

            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row['studentnumber']."</td>";
                echo "<td>".$row['lastname']."</td>";
                echo "<td>".$row['firstname']."</td>";
                echo "<td>".$row['middlename']."</td>";
                echo "<td>".$row['course']."</td>";
                echo "<td>".$row['yearlevel']."</td>";
                echo "<td>".$row['createdby']."</td>";
                echo "<td>".$row['datecreated']."</td>";
                echo "<td class='action-links'>";
                 echo '<button type="button" class="btn btn-primary" onclick="openUpdateModal(\''.$row['studentnumber'].'\', \''.$row['lastname'].'\', \''.$row['firstname'].'\', \''.$row['middlename'].'\', \''.$row['course'].'\', \''.$row['yearlevel'].'\')">Update</button>';
                echo '<a href="#" class="delete-link" onclick="confirmDelete(\''.$row['studentnumber'].'\')">Delete</a>';
                echo "</td>";
                echo "</tr>";
            }            
            echo "</table>";
        } else {
            echo "No record/s found.";
        }
    }

    if(isset($_POST['btnsearch'])) {
        $searchvalue = '%' . $_POST["search"] . '%';
        $sql = "SELECT * FROM tblstudents WHERE studentnumber LIKE ? OR lastname LIKE ? ORDER BY studentnumber";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                buildtable($result);
            } else {
                echo "Error executing search query";
            }
        }
    } else {
        // Load the data from the accounts table if btnsearch is not set
        $sql = "SELECT * FROM tblstudents ORDER BY studentnumber";
        if($stmt = mysqli_prepare($link, $sql)) {
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                buildtable($result);
            } else {
                echo "Error loading accounts data";
            }
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
  function confirmDelete(studentnumber) {
    $('#deletestudentnumberInput').val(studentnumber);
    $('#deletestudentModal').modal('show');
  }
  function openUpdateModal(studentnumber) {
        $('#updatestudentnumberInput').val(studentnumber);
        $('#updateAccountModal').modal('show');
    }

</script>
</body>
</html>

