<?php
// Include the database connection file
include 'dbconnect.php';

// Assuming you have the student's username passed through the URL
$studentUsername = $_GET['username'];

// Fetch student's full name from the database
$studentFullNameQuery = "SELECT full_name FROM users WHERE username = '$studentUsername'";
$studentFullNameResult = mysqli_query($dbconn, $studentFullNameQuery);
$studentFullNameRow = mysqli_fetch_assoc($studentFullNameResult);
$studentFullName = $studentFullNameRow['full_name'];

// Fetch the start date and end date of the student
$datesQuery = "SELECT start_date, end_date FROM student_internships WHERE username = '$studentUsername'";
$datesResult = mysqli_query($dbconn, $datesQuery);
$datesRow = mysqli_fetch_assoc($datesResult);
$startDate = $datesRow['start_date'];
$endDate = $datesRow['end_date'];

// Fetch progress reports for the student
$progressReportsQuery = "SELECT month, year, report_name, report_pdf_url, submission_date, user_id
                        FROM progress_reports
                        WHERE user_id = '$studentUsername'";
$progressReportsResult = mysqli_query($dbconn, $progressReportsQuery);

// Calculate the number of months between start date and end date
$startTimestamp = strtotime($startDate);
$endTimestamp = strtotime($endDate);
$monthsDiff = (date('Y', $endTimestamp) - date('Y', $startTimestamp)) * 12 + date('n', $endTimestamp) - date('n', $startTimestamp);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .progress-container {
            text-align: center;
            margin-top: 100px;
        }

        .progress-title {
            text-align: center;
            margin-top: -50px;
            font-family: 'Black Mango', sans-serif;
            font-weight: 100;
            font-size: 60px;
        }

        .progress-table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 80%;
            margin-left: 10%;
        }

        .progress-table th,
        .progress-table td {
            border: 1px solid #DBAFA2;
            padding: 15px;
            text-align: center;
        }

        .progress-table th {
            background-color: #FF9999;
        }

        .progress-table td:first-child {
            background-color: #FFCDCD;
            text-align: left;
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
            margin-top:10px;
            
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
            <div class="nav-item"><a href="admin_welcome.php" id="home">Home</a></div>
            <div class="nav-item"><a href="update_companies.php" id="updateCompany">Update Company</a></div>
            <div class="nav-item"><a href="view_students.php" id="students">Students</a></div>
            <div class="nav-item"><a href="login.php" id="logOut">Log Out</a></div>
        </div>
    </header>

    <div class="progress-container">
        <h1 class="progress-title"><?php echo $studentFullName; ?></h1>
        <table class="progress-table">
            <tr>
                <th>Documents</th>
                <th>Date Submitted</th>
                <th>Status</th>
            </tr>

            <?php
                
            // Display progress reports for the student
            while ($row = mysqli_fetch_assoc($progressReportsResult)) {
                echo '<tr>';
                echo '<td style="background-color: #FFCDCD; text-align: left;">';
                
                if ($row['submission_date']) {
                    echo "<a href='view_monthreport.php?report_id={$row['user_id']}{$row['month']}'>{$row['report_name']}</a>";
                    echo '<td>' . ($row['submission_date'] ? $row['submission_date'] : '-') . '</td>';
                } else {
                    // Display the report name without a link for rows with a red cross
                    echo $row['report_name'];
                    echo '<td>' . ($row['submission_date'] ? $row['submission_date'] : '-') . '</td>';
                }

                echo '</td>';
                
                echo '<td>';
                if ($row['submission_date']) {
                    echo '<span class="tick-mark">✔</span>';
                } else {
                    echo '<span class="x-mark">✘</span>';
                }
                echo '</td>';
                
                echo '</tr>';
            }
            ?>

        </table>

        <button class="go-back-btn" onclick="goBack()">Go Back</button>
            </div>

            <script>
                // JavaScript function to go back
                function goBack() {
                    window.history.back();
                }
            </script>
    </div>
</body>

</html>
