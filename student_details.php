<?php
// Include database connection file
include 'dbconnect.php';

// Assuming you have a way to retrieve student details, replace the following with your logic
$studentUsername = "example_student";
$studentDetailsQuery = "SELECT * FROM users WHERE username = '$studentUsername'";
$studentDetailsResult = mysqli_query($dbconn, $studentDetailsQuery);
$studentDetails = mysqli_fetch_assoc($studentDetailsResult);

// Assuming you have a way to retrieve student internship details, replace the following with your logic
$internshipDetailsQuery = "SELECT * FROM student_internships WHERE username = '$studentUsername'";
$internshipDetailsResult = mysqli_query($dbconn, $internshipDetailsQuery);
$internshipDetails = mysqli_fetch_assoc($internshipDetailsResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        body {
            background-color: #FDE7D8;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #DBAFA2;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #logo {
            font-size: 24px;
            font-weight: 700;
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
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-item a:hover {
            color: #FDE7D8;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            margin-top: 20px;
        }

        .details-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-top: 20px;
        }

        h1 {
            font-size: 35px;
            color: #343a40;
            margin-bottom: 10px;
        }

        label {
            font-size: 16px;
            color: #343a40;
            display: block;
            margin-bottom: 5px;
        }

        .info {
            font-size: 18px;
            color: #555;
            margin-bottom: 15px;
        }

        .go-back-btn {
            margin-top: 20px;
        }

        .go-back-btn button {
            background-color: #DBAFA2;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .go-back-btn button:hover {
            background-color: #FF7E5F;
        }
    </style>
</head>

<body>
    <header>
        <div id="logo">INTECH INTERN</div>
        <div class="nav-links">
            <div class="nav-item"><a href="#" id="home">Home</a></div>
            <div class="nav-item"><a href="#" id="updateCompany">Update Company</a></div>
            <div class="nav-item"><a href="#" id="students">Students</a></div>
            <div class="nav-item"><a href="#" id="logOut">Log Out</a></div>
        </div>
    </header>

    <div class="container">
        <div class="details-container">
            <h1>Student Details</h1>

            <label for="username">Username</label>
            <div class="info"><?php echo $studentDetails['username']; ?></div>

            <label for="full_name">Full Name</label>
            <div class="info"><?php echo $studentDetails['full_name']; ?></div>

            <label for="email_address">Email Address</label>
            <div class="info"><?php echo $studentDetails['email_address']; ?></div>

            <label for="birthdate">Birthdate</label>
            <div class="info"><?php echo $studentDetails['birthdate']; ?></div>

            <label for="internship_status">Internship Status</label>
            <div class="info"><?php echo $internshipDetails ? 'Internship Granted' : 'No Internship Yet'; ?></div>

            <?php if ($internshipDetails) : ?>
                <h1>Internship Details</h1>

                <label for="c_name">Company Name</label>
                <div class="info"><?php echo $internshipDetails['c_name']; ?></div>

                <label for="start_date">Start Date</label>
                <div class="info"><?php echo $internshipDetails['start_date']; ?></div>

                <label for="end_date">End Date</label>
                <div class="info"><?php echo $internshipDetails['end_date']; ?></div>

                <label for="email_csupervisor">Company Supervisor Email</label>
                <div class="info"><?php echo $internshipDetails['email_csupervisor']; ?></div>
            <?php endif; ?>

            <div class="go-back-btn">
                <button onclick="location.href='admin_welcome.php'">Go Back</button>
            </div>
        </div>
    </div>
</body>

</html>
