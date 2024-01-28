<?php
session_start();
// Include the database connection file
include 'dbconnect.php';

$supervisorUsername = $_SESSION['username'];

// Fetch supervised students and their company names
$supervisedStudentsQuery = "SELECT s.username, u.full_name, s.c_name AS c_name, s.email_csupervisor
                            FROM student_internships s
                            INNER JOIN users u ON s.username = u.username
                            WHERE s.usupervisor_id = '$supervisorUsername'";
$supervisedStudentsResult = mysqli_query($dbconn, $supervisedStudentsQuery);

// Fetch supervisor's details
$supervisorDetailsQuery = "SELECT full_name FROM supervisor WHERE username = '$supervisorUsername'";
$supervisorDetailsResult = mysqli_query($dbconn, $supervisorDetailsQuery);

if ($supervisorDetailsResult && mysqli_num_rows($supervisorDetailsResult) > 0) {
    $supervisorDetails = mysqli_fetch_assoc($supervisorDetailsResult);
    $supervisorFullName = $supervisorDetails['full_name'];
} else {
    // Handle error or redirect
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Supervisor</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Black+Mango&family=Poppins:wght@400;500;600&display=swap">
    <style>
        body {
            background-color: #FDE7D8;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #FDE7D8;
        }

        #logo {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: medium;
            color: black;
        }

        .nav-links {
            display: flex;
            align-items: center;
            margin-right: -15px;
        }

        .nav-item {
            margin-right: 20px;
        }

        .nav-item a {
            text-decoration: none;
            color: black;
            font-size: 15px;
            font-weight: medium;
        }

        .nav-item a:hover {
            background-color: #DBAFA2;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .welcome-message {
            text-align: center;
            margin-top: 50px;
            font-family: 'Black Mango', sans-serif;
            font-weight: 100;
            font-size: 60px;
        }

        .table-container {
            margin: 20px auto;
            max-width: 1000px;
            background-color: #CBA571;
            padding: 15px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #fff;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #CBA571;
            color: #fff;
        }
        .bold {
            font-weight: bold;
        }

        .assessment-link, .chat-link {
            color: #0000FF;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div id="logo">INTECH INTERN</div>
        <div class="nav-links">
            <div class="nav-item"><a href="welcomesupervisor.php" id="home">Home</a></div>
            <div class="nav-item"><a href="login.php" id="home">LOG OUT</a></div>
        </div>
    </header>

    <div class="welcome-message">
        Hi, Welcome <?php echo $supervisorFullName; ?>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>NAME</th>
                <th>COMPANY AND SUPERVISOR</th>
                <th>Matric number</th>
                <th>STATUS</th>
                <th>ASSESSMENT</th>
                <th>CHAT STUDENT</th>
            </tr>

            <?php
            // Display supervised students
            while ($row = mysqli_fetch_assoc($supervisedStudentsResult)) {
                echo '<tr>';
                echo '<td>' . $row['full_name'] . '</td>';
                echo '<td><strong class="bold">' . $row['c_name'] . '</strong><br>' . $row['email_csupervisor'] . '</td>';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td><a href="progressreportstudent.php?username=' . $row['username'] . '">VIEW PROGRESS</a></td>';
                echo '<td><a class="assessment-link" href="assessment.php?username=' . $row['username'] . '">ASSESS</a></td>';
                echo '<td><a class="chat-link" href="contactstudent.php?username=' . $row['username'] . '">CHAT</a></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</body>

</html>
