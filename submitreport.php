<?php
include 'dbconnect.php';

session_start();
$username = $_SESSION['username'];

// Check if a report ID is provided
if (isset($_GET['report_id'])) {
    $selectedReportId = $_GET['report_id'];
    // You might want to perform additional validation or sanitization here
} else {
    // No report_id provided, display an error message or redirect
    echo "Error: Report ID not provided";
    exit();
}

function getReportDetailsById($reportId, $dbconn) {
    $query = "SELECT * FROM progress_reports WHERE report_id = ?";
    $stmt = mysqli_prepare($dbconn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $reportId);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            return mysqli_fetch_assoc($result);
        }
    }

    return null;
}

// Check if a report ID is provided
if (isset($_REQUEST['report_id'])) {
    $selectedReportId = $_REQUEST['report_id'];
    $reportDetails = getReportDetailsById($selectedReportId, $dbconn);

    // Check if the report has been submitted
    if (isset($reportDetails['submission_date'])) {
        // Report has been submitted, display it
        $submittedReport = true;
    } else {
        // Report hasn't been submitted, display the form
        $submittedReport = false;
    }
} else {
    // No report_id provided, display "hehe"
    echo "hehe";
    exit(); // You might want to exit the script here if no report_id is provided
}

// Extract month from the report_id
$numericMonth = substr($selectedReportId, -2); // Extract the last two characters

// Handle form submission only if the report hasn't been submitted
if (!$submittedReport && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming your form fields are named 'report_text' and 'report_pdf_url'
    $reportText = $_POST['report_text'] ?? '';
    $reportPdfUrl = $_POST['report_pdf_url'] ?? '';

    // Update the corresponding row based on the provided report_id
    $queryUpdateReport = "UPDATE progress_reports SET report_text = ?, report_pdf_url = ?, submission_date = NOW() WHERE report_id = ?";
    $stmtUpdateReport = mysqli_prepare($dbconn, $queryUpdateReport);

    if ($stmtUpdateReport) {
        mysqli_stmt_bind_param($stmtUpdateReport, "sss", $reportText, $reportPdfUrl, $selectedReportId);
        mysqli_stmt_execute($stmtUpdateReport);
    }

    // Redirect back to progressreport.php after submission
    header("Location: progressreport.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTECH-INTERN Submit Progress Report</title>
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

        .report-container {
            text-align: center;
            margin-top: 60px;
        }

        .form-container {
            width: 50%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .report_name {
            font-size: 24px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-size: 16px;
        }

        textarea,
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #DBAFA2;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .table {
        width: 80%;
        margin: auto;
        margin-top: 20px;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        font-size: 16px;
        margin-bottom: 40px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #DBAFA2;
        color: white;
    }

    .go-back-btn {
    display: block;
    margin: auto; /* Center the button */
    margin-top: 20px; /* Add some top margin for separation */
    padding: 15px 30px; /* Make the box bigger */
    background-color: #c54b8c;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
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
        <?php
        if (isset($reportDetails)) {
            echo "<div class='report_name'>" . $reportDetails['report_name'] . "</div>";
        }
        ?>
    </div>

    <div class="form-container <?php echo $submittedReport ? 'hidden' : ''; ?>">
    <?php
    if ($submittedReport) {
        // Display existing report details in a table
        echo "<table class='table comment-table'>";
        echo "<tr><th>Report</th><th>Details</th></tr>";

        // Display report_text
        echo "<tr><td>Report Text</td><td>{$reportDetails['report_text']}</td></tr>";

        // Display report_pdf_url
        echo "<tr><td>PDF Report URL</td><td>{$reportDetails['report_pdf_url']}</td></tr>";

        echo "</table>";
    } else {
        // Display the form
        echo "<form method='POST' action=''>";
        echo "<label for='report_text'>Report Text:</label>";
        echo "<textarea name='report_text' rows='8'></textarea>"; 

        echo "<label for='report_pdf_url'>PDF Report URL:</label>";
        echo "<input type='text' name='report_pdf_url'>";

        echo "<button type='submit'>Submit</button>";
        echo "</form>";
    }
    ?>
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
