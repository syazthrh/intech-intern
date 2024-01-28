<?php
// Include dbconnect.php to establish a database connection
include 'dbconnect.php';

session_start();
$username = $_SESSION['username'];

// Fetch user information from the database
$queryUser = "SELECT full_name FROM users WHERE username = '$username'";
$resultUser = mysqli_query($dbconn, $queryUser);

if ($resultUser) {
    $rowUser = mysqli_fetch_assoc($resultUser);
    $fullName = $rowUser['full_name'];
} else {
    // Handle the error or redirect to login page
    die("Error: " . mysqli_error($dbconn));
}

// Fetch internship information from the student_internships table
$queryInternship = "SELECT c_name, c_supervisor_id, start_date, end_date FROM student_internships WHERE username = '$username'";
$resultInternship = mysqli_query($dbconn, $queryInternship);

if ($resultInternship) {
    $rowInternship = mysqli_fetch_assoc($resultInternship);
    $companyName = $rowInternship['c_name'];
    $supervisorId = $rowInternship['c_supervisor_id'];

    // Fetch supervisor name from the c_supervisor table
    $querySupervisor = "SELECT full_name FROM c_supervisor WHERE username = '$supervisorId'";
    $resultSupervisor = mysqli_query($dbconn, $querySupervisor);

    if ($resultSupervisor) {
        $rowSupervisor = mysqli_fetch_assoc($resultSupervisor);
        $supervisorName = $rowSupervisor['full_name'];
    } else {
        // Handle the error or provide a default value
        $supervisorName = "Not Available";
    }

    $startDate = $rowInternship['start_date'];
    $endDate = $rowInternship['end_date'];
} else {
    // Handle the error or redirect to login page
    die("Error: " . mysqli_error($dbconn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTECH-INTERN Welcome Page</title>
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

        .welcome-text {
            text-align: center;
            margin-top: 100px;
        }

        .welcome-text h1 {
            font-family: Black Mango, sans-serif;
            font-weight: 100;
            font-size: 70px;
            position: absolute;
            top: 50.8px;
            left: 80px;
        }

        .welcome-text h2 {
            font-family: Black Mango, sans-serif;
            font-weight: 100;
            font-size: 65px;
            position: absolute;
            top: 110.4px;
            left: 80px;
        }

        .info-container {
            display: flex;
            margin-top: 200px;
        }

        .info-box {
            flex: 1;
            margin-right: 50px;
            margin-left: 50px;
        }

        .info-box2 {
            flex: 1;
            margin-right: 200px;
        }

        .info-label {
            font-family: Poppins, sans-serif;
            font-weight: 500;
            font-size: 13.6px;
            margin-bottom: 5px;
        }

        .info-box .box {
            background-color: #DBAFA2;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 70%;
        }

        .info-box .box p {
            font-family: Poppins, sans-serif;
            font-weight: 400;
            font-size: 13.6px;
        }
        .info-box2 .box {
            background-color: #DBAFA2;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 70%;
        }

        .info-box2 .box p {
            font-family: Poppins, sans-serif;
            font-weight: 400;
            font-size: 13.6px;
        }

        .date-box {
            display: flex;
            align-items: right;
            margin-top: -10px;
            flex-direction: column;
        }

        .date-box .box {
            background-color: #DBAFA2;
            padding: 10px 15px;
            border-radius: 5px;
            width: 30%;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <header>
        <div id="logo">INTECH INTERN</div>
        <div class="nav-links">
            <div class="nav-item"><a href="welcomeconfirm.php" id="home">Home</a></div>
            <div class="nav-item"><a href="progressreport.php">Progress Report</a></div>
            <div class="nav-item"><a href="contactsupervisor.php">Contact Supervisor</a></div>
            <div class="nav-item"><a href="login.php">Log Out</a></div>
        </div>
    </header>

    <div class="welcome-text">
        <h1>Hi Welcome, </h1>
        <h2><?php echo ($fullName) ?></h2>
    </div>

    <div class="info-container">
        <div class="info-box">
            <p class="info-label">Full Name</p>
            <div class="box">
                <p><?php echo $fullName; ?></p>
            </div>
            <p class="info-label">Internship Company</p>
            <div class="box">
                <p><?php echo $companyName; ?></p>
            </div>
            <p class="info-label">Supervisor Name</p>
            <div class="box">
                <p><?php echo $supervisorName; ?></p>
            </div>
        </div>
        <div class="info-box2">
            <p class="info-label">Matric Number</p>
            <div class="box">
                <p><?php echo $username; ?></p>
            </div>
            <div class="date-box">
                <p class="info-label">Start Date</p>
                <div class="box">
                    <p><?php echo $startDate; ?></p>
                </div>
            </div>
            <div class="date-box">
                <p class="info-label">End Date</p>
                <div class="box">
                    <p><?php echo $endDate; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
