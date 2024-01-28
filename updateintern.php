<?php
include 'dbconnect.php';

$errorMessage = "";

// Function to handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricNumber = $_POST['username'];
    $password = $_POST['password'];

    // Check if the keys exist in the $_POST array before accessing them
    $companyName = isset($_POST['c_name']) ? $_POST['c_name'] : '';
    $supervisorName = isset($_POST['csupervisor_name']) ? $_POST['csupervisor_name'] : '';
    $supervisorID = isset($_POST['c_supervisor_id']) ? $_POST['c_supervisor_id'] : '';
    $supervisorEmail = isset($_POST['email_csupervisor']) ? $_POST['email_csupervisor'] : '';
    $offerLetterUrl = isset($_POST['offer_letter_url']) ? $_POST['offer_letter_url'] : '';
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

    // Validate the user against the student_internships table using prepared statements
    $query = "SELECT * FROM student_internships WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($dbconn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $matricNumber, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // User found, update student_internships table using prepared statement
            $updateQuery = "UPDATE student_internships SET 
                c_name = ?, 
                csupervisor_name = ?, 
                c_supervisor_id = ?,
                email_csupervisor = ?, 
                offer_letter_url = ?, 
                start_date = ?,
                end_date = ? 
                WHERE username = ?";
            $updateStmt = mysqli_prepare($dbconn, $updateQuery);

            if ($updateStmt) {
                mysqli_stmt_bind_param($updateStmt, "ssssssss", $companyName, $supervisorName, $supervisorID, $supervisorEmail, $offerLetterUrl, $startDate, $endDate, $matricNumber);
                mysqli_stmt_execute($updateStmt);

                // Close the update statement
                mysqli_stmt_close($updateStmt);

                // Redirect to login.php
                header("Location: login.php");
                exit();
            } else {
                $errorMessage = "Update failed. Please try again.";
            }
        } else {
            // User not found, insert into student_internships table
            $insertQuery = "INSERT INTO student_internships (username, c_name, csupervisor_name, c_supervisor_id, email_csupervisor, offer_letter_url, start_date, end_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($dbconn, $insertQuery);

            if ($insertStmt) {
                mysqli_stmt_bind_param($insertStmt, "ssssssss", $matricNumber, $companyName, $supervisorName, $supervisorID, $supervisorEmail, $offerLetterUrl, $startDate, $endDate);
                mysqli_stmt_execute($insertStmt);

                // Close the insert statement
                mysqli_stmt_close($insertStmt);

                // Redirect to login.php
                header("Location: login.php");
                exit();
            } else {
                $errorMessage = "Insert failed. Please try again.";
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $errorMessage = "Database error. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Internship Info</title>
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

        #logo-container {
            display: flex;
            align-items: center;
        }

        #logo {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: medium;
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
            font-size: 20px;
            font-weight: medium;
        }

        .nav-item a:hover {
            background-color: #DBAFA2;
            padding: 5px 10px;
            border-radius: 5px;
        }

        #update {
            font-size: 20px;
            font-weight: medium;
            color: black;
            display: flex;
            align-items: center;
        }

        #update a {
            text-decoration: none;
            color: black;
            margin-right: 5px; 
        }

        #update a:hover {
            background-color: #DBAFA2;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .content-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 140vh;
            flex-direction: column;
        }

        .update-content {
            text-align: center;
        }

        .update-title {
            font-family: 'Black Mango', sans-serif;
            font-size: 35px;
            color: black;
        }

        .update-info {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            margin: 10px 0;
        }

        .input-box {
            margin: 10px 0;
            position: relative;
        }

        .input-label {
            font-size: 20px;
        }

        .rounded-input {
            border: 1px solid #DBAFA2;
            border-radius: 5px;
            width: 300px;
            padding: 8px;
            font-size: 20px;
            box-sizing: border-box;
        }

        .date-input {
            width: 150px; /* Adjust the width for date inputs */
        }

        .update-btn {
            background-color: #241917;
            color: #9A6B5B;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 20px;
            cursor: pointer;
        }

        .logout-info {
            margin-top: 20px;
            font-size: 20px;
            color: #241917;
        }

        .go-back {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            color: black;
            text-decoration: none;
            margin-top: 20px;
            display: block;
            text-align: center; /* Center the "Go Back" link */
        }
    </style>
</head>

<body>
    <header>
        <div id="logo-container">
            <div id="logo">INTECH INTERN</div>
        </div>
        <div class="nav-links">
            <div class="nav-item"><a href="welcomestudent.php" id="home">HOME</a></div>
        </div>
        <div id="update">
            <div id="search-icon"><a href="updateintern.php">🔍UPDATE INTERNSHIP </a></div>
        <div><a href="login.php">LOG OUT</a></div>
        </div>
    </header>

    <div class="content-container">
        <div class="update-content">
            <div class="update-title">UPDATE INTERNSHIP INFO</div>
            <?php
            if (!empty($errorMessage)) {
                echo "<div style='color: red; margin: 10px 0;'>$errorMessage</div>";
            }
            ?>
            <div class="update-info">Update your internship information after you successfully receive your offer letter from your internship company.</div>
            <form method="POST" action="">
                <div class="input-box">
                    <div class="input-label">Matric Number</div>
                    <input type="text" class="rounded-input" name="username" id="username" placeholder="Insert your matric number" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Password</div>
                    <input type="password" class="rounded-input" name="password" id="password" placeholder="Insert your password" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Name of the internship company</div>
                    <input type="text" class="rounded-input" name="c_name" id="c_name" placeholder="Insert the name of the company" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Name of your supervisor</div>
                    <input type="text" class="rounded-input" name="csupervisor_name" id="csupervisor_name" placeholder="Insert the name of your supervisor" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Supervisor staff Number</div>
                    <input type="text" class="rounded-input" name="c_supervisor_id" id="c_supervisor_id" placeholder="Insert your company supervisor staff number" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Email of your supervisor</div>
                    <input type="text" class="rounded-input" name="email_csupervisor" id="email_csupervisor" placeholder="Insert the email of your supervisor" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Offer Letter</div>
                    <input type="text" class="rounded-input" name="offer_letter_url" id="offer_letter_url" placeholder="Insert the link of your offer letter" required>
                </div>
                <div class="input-box">
                    <div class="input-label">Start Date</div>
                    <input type="date" class="date-input" name="start_date" id="start_date" required>
                </div>
                <div class="input-box">
                    <div class="input-label">End Date</div>
                    <input type="date" class="date-input" name="end_date" id="end_date" required>
                </div>
                <button type="submit" class="update-btn">UPDATE INTERNSHIP</button>
            </form>
            <div class="logout-info">You will be auto-logged out and need to log in again after you receive your confirmation email.</div>
            <a href="javascript:history.go(-1)" class="go-back">Go Back</a>
        </div>
    </div>
</body>

</html>
