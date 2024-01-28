<?php
session_start();
include 'dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in supervisor's username
$loggedInUsername = $_SESSION['username'];

// Retrieve student information supervised by the company supervisor
$query = "SELECT users.username, users.full_name, users.email_address, student_internships.start_date, student_internships.end_date
          FROM student_internships
          INNER JOIN users ON student_internships.username = users.username
          WHERE student_internships.c_supervisor_id = ?";
$stmt = mysqli_prepare($dbconn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $loggedInUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    // Handle database error
    die("Database error. Please try again.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Company Supervisor</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        body {
            background-color: #fde7d8;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #FDE7D8;
            color: black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #logo-container {
            display: flex;
            align-items: center;
        }

        #logo {
            font-size: 24px;
            font-weight: 700;
            color: black;
            margin-right: 20px;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-item {
            margin-right: 20px;
        }

        .nav-item a {
            text-decoration: none;
            color: black;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-item a:hover {
            color: #DBAFA2;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .content-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .welcome-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .welcome-title {
            font-size: 35px;
            color: #343a40;
            margin-bottom: 30px;
            margin-top: 20px;
        }

        .student-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .student-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            box-sizing: border-box;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
            transition: transform 0.3s;
        }

        .student-item:hover {
            transform: translateY(-5px);
        }

        .student-info {
            font-size: 16px;
            margin-bottom: 10px;
            color: #343a40;
        }

        .go-back {
            font-size: 18px;
            color: #343a40;
            text-decoration: none;
            margin-top: 20px;
            display: block;
            text-align: center;
            transition: color 0.3s;
        }

        .go-back:hover {
            color: #e44d26;
        }
    </style>
</head>

<body>
    <header>
        <div id="logo-container">
            <div id="logo">INTECH INTERN</div>
        </div>
        <div class="nav-links">
            <div class="nav-item"><a href="welcomecompanysupervisor.php" id="home">HOME</a></div>
            <div class="nav-item"><a href="login.php" id="home">LOG OUT</a></div>
        </div>

    </header>

    <div class="content-container">
        <div class="welcome-content">
            <div class="welcome-title">Welcome Company Supervisor</div>
            <div class="student-list">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<a href="reportmonthlycompany.php?username=' . $row['username'] . '" class="student-item">';
                echo '<div class="student-info"><strong>Full Name:</strong> ' . $row['full_name'] . '</div>';
                echo '<div class="student-info"><strong>Email Address:</strong> ' . $row['email_address'] . '</div>';
                echo '<div class="student-info"><strong>Start Date:</strong> ' . $row['start_date'] . '</div>';
                echo '<div class="student-info"><strong>End Date:</strong> ' . $row['end_date'] . '</div>';
                echo '</a>';
            }
            ?>
            </div>
        </div>
    </div>
</body>

</html>
