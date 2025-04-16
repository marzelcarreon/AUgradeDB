<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Grades</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles */
        body {
            background-image: url('https://img.freepik.com/free-vector/white-abstract-background_23-2148810113.jpg');   
            background-repeat: no-repeat;
            background-attachment: fixed; 
            background-size: 100% 100%; 
        }
        .header {
            background-color: #333;
            color: white;
            padding: 40px;
            text-align: center;
            width: 100%; 
            position: fixed; 
            top: 10; 
            left: 0; 
            z-index: 1000; 
        }
        .header h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }
        .header h4 {
            font-size: 16px;
            margin-top: 0;
        }
        .menu {
            position: absolute;
            top: 20px; 
            right: 35px; 
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px; 
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
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="https://seeklogo.com/images/A/arellano-university-logo-D0C35BB9A2-seeklogo.com.png" alt="Arellano Logo" width="75" height="75" style="position: absolute; top: 20px; left: 35px;">
        <div class="menu">
            <a href="index.php" class="btn btn-primary">Menu</a>&nbsp;
            <a href="logout.php" onclick="confirmLogout(); return false;">Logout</a>
        </div>
        <?php
        session_start();
        if(isset($_SESSION['username'])) {
            echo "<h1>Grades</h1>";
        } else {
            header("Location: login.php");
            exit();
        }
        ?>
    </div>

    <div class="container">
        <?php
        session_start();
        require_once "config.php";

        if(isset($_SESSION['username'])) {
            $studentNumber = $_SESSION['username'];

            $sql = "SELECT stud.studentnumber, stud.lastname, stud.firstname, stud.middlename, stud.course, stud.yearlevel, g.grade, g.encodedby, g.dateencoded, subj.code, subj.description, subj.unit 
                    FROM tblstudents stud
                    LEFT JOIN tblgrades g ON g.studentnumber = stud.studentnumber
                    LEFT JOIN tblsubjects subj ON g.subjectcode = subj.code
                    WHERE stud.studentnumber = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $studentNumber);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if(mysqli_num_rows($result) > 0) {
                        // Fetching student information
                        $row = mysqli_fetch_array($result);
                        echo "<br><br><br>Student Number: " . $row['studentnumber'];
                        echo "<br>Name: " . $row['firstname'] ." ". $row['middlename'] . " " . $row['lastname'] ;
                        echo "<br>Course: " . $row['course'];
                        echo "<br>Year Level: " . $row['yearlevel'];
                    
        
                        // Outputting table
                        echo "<table>";
                        echo "<tr><th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Encoded by</th><th>Date Encoded</th></tr>";
        
                        do {
                            echo "<tr>";
                            echo "<td>" . $row['code'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "<td>" . $row['unit'] . "</td>";
                            echo "<td>" . $row['grade'] . "</td>";
                            echo "<td>" . $row['encodedby'] . "</td>";
                            echo "<td>" . $row['dateencoded'] . "</td>";
                            echo "</tr>";
                        } while($row = mysqli_fetch_array($result));
        
                        echo "</table>";
                    } else {
                        echo "No grades found.";
                    }
                } else {
                    echo "Error executing statement: " . mysqli_error($link);
                }
            } else {
                echo "Error preparing statement: " . mysqli_error($link);
            }
        } else {
            header("Location: login.php");
            exit();
        }
        ?>
    </div>

    <div class="footer">
        <!-- Footer content here -->
    </div>
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
</script>
</body>
</html>
