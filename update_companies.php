<?php
// Include database connection file
include 'dbconnect.php';

// Variables to store success and error messages
$message = '';
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $companyName = mysqli_real_escape_string($dbconn, $_POST['companyName']);
    $logoUrl = mysqli_real_escape_string($dbconn, $_POST['logoUrl']);
    $salary = mysqli_real_escape_string($dbconn, $_POST['salary']);
    $jobType = mysqli_real_escape_string($dbconn, $_POST['jobType']);
    $location = mysqli_real_escape_string($dbconn, $_POST['location']);
    $details = mysqli_real_escape_string($dbconn, $_POST['details']);
    $jobBrief = mysqli_real_escape_string($dbconn, $_POST['jobBrief']);
    $shiftSchedule = mysqli_real_escape_string($dbconn, $_POST['shiftSchedule']);
    $toolsUsed = mysqli_real_escape_string($dbconn, $_POST['toolsUsed']);
    $companyEmail = mysqli_real_escape_string($dbconn, $_POST['companyEmail']);
    $course = mysqli_real_escape_string($dbconn, $_POST['course']);

    // SQL query to insert data into the company table
    $insertQuery = "INSERT INTO company (company_name, logo_url, salary, job_type, location, details, job_brief, shift_schedule, tools_used, company_email, course) 
                    VALUES ('$companyName', '$logoUrl', '$salary', '$jobType', '$location', '$details', '$jobBrief', '$shiftSchedule', '$toolsUsed', '$companyEmail', '$course')";

    // Execute the query
    if (mysqli_query($dbconn, $insertQuery)) {
        $message = "Company added successfully.";
    } else {
        $error = "Error: " . mysqli_error($dbconn);
    }

    // Close the database connection
    mysqli_close($dbconn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Company</title>
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
            margin-top: 20px:
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #343a40;
        }

        input,
        textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        textarea {
            height: 150px; /* Adjust the height as needed */
            resize: vertical;
        }

        button {
            padding: 10px;
            background-color: #DBAFA2;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #FF7E5F;
        }

        .message-box {
            display: <?php echo ($message || $error) ? 'block' : 'none'; ?>;
            background-color: <?php echo $message ? '#5cb85c' : '#d9534f'; ?>;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .message-box button {
            background-color: #4cae4c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .page-title {
            text-align: center;
            margin-top: -10px;
        }

        .page-title h1 {
            font-size: 35px;
            color: #343a40;
            margin-bottom: 20px;
        }

        .go-back-btn {
            display: flex;
            justify-content: center;
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
            <div class="nav-item"><a href="admin_welcome.php" id="home">Home</a></div>
            <div class="nav-item"><a href="update_companies.php" id="updateCompany">Update Company</a></div>
            <div class="nav-item"><a href="view_students.php" id="students">Students</a></div>
            <div class="nav-item"><a href="login.php" id="logOut">Log Out</a></div>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <div class="page-title">
                <h1>Add Company</h1>
                <p>Enter the details of the new internship company.</p>
            </div>

            <form method="post">
                <label for="companyName">Company Name</label>
                <input type="text" id="companyName" name="companyName" required>

                <label for="logoUrl">Logo URL</label>
                <input type="text" id="logoUrl" name="logoUrl" required>

                <label for="salary">Salary</label>
                <input type="text" id="salary" name="salary" required>

                <label for="jobType">Job Type</label>
                <input type="text" id="jobType" name="jobType" required>

                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>

                <label for="details">Details</label>
                <textarea id="details" name="details" required></textarea>

                <label for="jobBrief">Job Brief</label>
                <textarea id="jobBrief" name="jobBrief" required></textarea>

                <label for="shiftSchedule">Shift Schedule</label>
                <input type="text" id="shiftSchedule" name="shiftSchedule" required>

                <label for="toolsUsed">Tools Used</label>
                <input type="text" id="toolsUsed" name="toolsUsed" required>

                <label for="companyEmail">Company Email</label>
                <input type="text" id="companyEmail" name="companyEmail" required>

                <label for="course">Course</label>
                <input type="text" id="course" name="course" required>

                <button type="submit">Add Company</button>
            </form>

            <div class="message-box">
                <?php
                if ($message) {
                    echo $message;
                } elseif ($error) {
                    echo $error;
                }
                ?>
                <button onclick="location.href='admin_welcome.php'">OK</button>
            </div>

            <div class="go-back-btn">
                <button onclick="location.href='admin_welcome.php'">Go Back</button>
            </div>
        </div>
    </div>
</body>

</html>
