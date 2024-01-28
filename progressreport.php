<?php
include 'dbconnect.php';

session_start();
$username = $_SESSION['username'];

// Fetch internship start and end dates from the database
$queryInternshipDates = "SELECT start_date, end_date FROM student_internships WHERE username = '$username'";
$resultInternshipDates = mysqli_query($dbconn, $queryInternshipDates);

if ($resultInternshipDates) {
    $rowInternshipDates = mysqli_fetch_assoc($resultInternshipDates);
    $startDate = $rowInternshipDates['start_date'];
    $endDate = $rowInternshipDates['end_date'];
} else {
    // Handle the error or redirect to login page
    die("Error: " . mysqli_error($dbconn));
}

// Generate an array of months between start and end dates
$startMonth = date('n', strtotime($startDate));
$endMonth = date('n', strtotime($endDate));
$months = range($startMonth, $endMonth);

// Insert rows for each month
foreach ($months as $month) {
    // Calculate the report_id by concatenating username + numeric (month)
    $report_id = $username . $month;

    // Check if the report_id already exists
    $queryCheckDuplicate = "SELECT COUNT(*) FROM progress_reports WHERE report_id = '$report_id'";
    $resultCheckDuplicate = mysqli_query($dbconn, $queryCheckDuplicate);

    if (!$resultCheckDuplicate) {
        // Handle the error or redirect to an error page
        die("Error: " . mysqli_error($dbconn));
    }

    $rowCheckDuplicate = mysqli_fetch_row($resultCheckDuplicate);
    $isDuplicate = $rowCheckDuplicate[0] > 0;

    if (!$isDuplicate) {
        // Insert into the progress_reports table
        $queryInsertReport = "INSERT INTO progress_reports (report_id, user_id, month, year, report_name, report_text, report_pdf_url)
        VALUES ('$report_id', '$username', '$month', YEAR(CURDATE()), 'PROGRESS REPORT " . strtoupper(date('F', mktime(0, 0, 0, $month, 1))) . "', '', '')";

        $resultInsertReport = mysqli_query($dbconn, $queryInsertReport);

        if (!$resultInsertReport) {
            // Handle the error or redirect to an error page
            die("Error: " . mysqli_error($dbconn));
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Progress Report</title>
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
            margin-right: -15px; /* Adjusted margin to bring links closer */
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

        .report-container {
            text-align: center;
            margin-top: 40px;
        }

        .report-table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 70%;
            margin-left: 15%;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #DBAFA2;
            padding: 15px;
            text-align: center;
        }

        .report-table th {
            background-color: #FF9999;
        }

        .report-table td:first-child {
            background-color: #FFCDCD;
            text-align: left;
            cursor: pointer;
        }

        .tick-mark {
            color: #008000;
            font-size: 20px;
        }

        .x-mark {
            color: #FF0000;
            font-size: 20px;
        }

        .go-back-btn {
            padding: 10px 20px;
            background-color: #c54b8c;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            
        }

        .go-back-btn:hover {
            background-color: #c54b8c;
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

    <div class="report-container"> 
        <h1>Submit Progress Report</h1>
        <table class="report-table">
            <tr>
                <th>Documents</th>
                <th>Date Submitted</th>
                <th>Status</th>
            </tr>
            <?php
             $querySubmissionDetails = "SELECT month, submission_date FROM progress_reports WHERE user_id = '$username'";
             $resultSubmissionDetails = mysqli_query($dbconn, $querySubmissionDetails);
             
             $submittedMonths = [];
             
             if ($resultSubmissionDetails) {
                 while ($rowSubmission = mysqli_fetch_assoc($resultSubmissionDetails)) {
                     $month = $rowSubmission['month'];
                     $submissionDate = $rowSubmission['submission_date'];
             
                     // Store submission details in an array
                     $submittedMonths[$month] = $submissionDate;
                 }
             } else {
                 // Handle the error or redirect to an error page
                 die("Error: " . mysqli_error($dbconn));
             }
             
             // Loop through months and display the table rows
             foreach ($months as $month) {
                 $monthName = strtoupper(date('F', mktime(0, 0, 0, $month, 1)));
                 $reportType = ($month == date('n')) ? 'FINAL REPORT' : "PROGRESS REPORT $monthName";
                 $reportId = "{$username}{$month}";
             
                 echo "<tr>";
             
                 // Check if the month has been submitted
                 if (isset($submittedMonths[$month])) {
                     $submissionDate = $submittedMonths[$month];
             
                     // Display the submission details with a green tick
                     echo "<td style='background-color: #FFCDCD; text-align: left;'><a href='submitreport.php?report_id={$username}{$month}'>$reportType</a></td>";
                     echo "<td>$submissionDate</td>";
                     echo "<td><span class='tick-mark'>✔</span></td>";
                 } else {
                     // If the month hasn't been submitted, provide a link to submit the report
                     echo "<td style='background-color: #FFCDCD; text-align: left;'><a href='submitreport.php?report_id={$username}{$month}'>$reportType</a></td>";
                     echo "<td></td>";
                     echo "<td><span class='x-mark'>✘</span></td>";
                 }
             
                 echo "</tr>";
             }
            ?>
            </table>
            <button class="go-back-btn" onclick="goBack()">Go Back</button>

            <script>
            // JavaScript function to go back
            function goBack() {
                window.history.back();
            }
            </script>
            </div>
            </body>
            
            </html>
            