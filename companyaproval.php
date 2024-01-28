<?php
include 'dbconnect.php';

$reportId = $_GET['report_id'];

$reportDetailsQuery = "SELECT pr.report_id, u.username, u.full_name, si.c_name, pr.report_text, pr.report_pdf_url, pr.submission_date, pr.month, pr.comment_supervisor
                        FROM progress_reports pr 
                        INNER JOIN users u ON pr.user_id = u.username 
                        INNER JOIN student_internships si ON pr.user_id = si.username
                        WHERE pr.report_id = $reportId";
$reportDetailsResult = mysqli_query($dbconn, $reportDetailsQuery);
$reportDetails = mysqli_fetch_assoc($reportDetailsResult);

$alreadyApproved = $reportDetails['comment_supervisor'] !== null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commentSupervisor = mysqli_real_escape_string($dbconn, $_POST['comment_supervisor']);

    if ($alreadyApproved) {
        // Comment is already approved, no need to update
        $commentSupervisor = $reportDetails['comment_supervisor'];
    } else {
        // Update the progress_report table
        $updateQuery = "UPDATE progress_reports SET comment_supervisor = '$commentSupervisor' WHERE report_id = $reportId";
        mysqli_query($dbconn, $updateQuery);
    }

    // Refresh the page to display the updated comment and pass the username
    header("Location: reportmonthlycompany.php?report_id=$reportId&username=" . urlencode($reportDetails['username']));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Approval - Progress Report <?php echo date("F", mktime(0, 0, 0, $reportDetails['month'], 1)); ?></title>
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

        #logo {
            font-size: 24px;
            font-weight: 700;
            color: black;
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

        .page-title {
            text-align: center;
            margin-top: -10px;
        }

        .page-title h1 {
            font-size: 35px;
            color: #343a40;
            margin-bottom: 20px;
        }

        .tables-container {
            text-align: center; 
            margin-top: 30px;
        }

        .table, .border-table, .comment-table {
            margin-top: 20px;
            width: 849.1px;
            margin: auto;
        }

        .table th, .table td, .border-table th, .border-table td, .comment-table th, .comment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th, .comment-table th {
            background-color: #FF9999;
            height: 55.7px;
        }

        .table td, .comment-table td {
            background-color: #FFCDCD;
        }

        .report-box {
            width: 340.1px;
            height: 280.8px;
            overflow: auto;
            padding: 10px;
            background-color: #DBAFA2;
            color: #888;
        }

        .border-table th, .border-table td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }

        .form-container {
            text-align: center;
            margin-top: 30px;
        }

        .comment-table {
            margin-top: 20px;
            width: 60%;
            margin: auto;
        }

        .comment-table th,
        .comment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .comment-table th {
            background-color: #FF9999;
            height: 50px;
        }

        .comment-table td {
            background-color: #FFCDCD;
        }

        .comment-input {
            width: 80%;
            padding: 8px;
            margin: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }

        .comment-btn {
            padding: 10px 20px;
            background-color: #DBAFA2;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .comment-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
            <div class="nav-item"><a href="welcomecompanysupervisor.php" id="home">Home</a></div>
            <div class="nav-item"><a href="login.php" id="home">LOG OUT</a></div>
        </div>
    </header>

    <div class="page-title">
    <h1>Progress Report <?php echo date("F", mktime(0, 0, 0, $reportDetails['month'], 1)); ?></h1>
</div>

<div class="tables-container">
    <table class="border-table">
        <tr>
            <th>MATRIC NUMBER</th>
            <th>STUDENT NAME</th>
            <th>COMPANY</th>
        </tr>
        <tr>
            <td><?php echo $reportDetails['username']; ?></td>
            <td><?php echo $reportDetails['full_name']; ?></td>
            <td><?php echo $reportDetails['c_name']; ?></td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <th>Task Description</th>
            <th>Upload PDF</th>
            <th>Submission Date</th>
        </tr>
        <tr>
            <td class="report-box"><?php echo $reportDetails['report_text']; ?></td>
            <td class="report-box"><?php echo $reportDetails['report_pdf_url']; ?></td>
            <td class="report-box"><?php echo $reportDetails['submission_date']; ?></td>
        </tr>
    </table>
</div>


<div class="form-container">
    <form method="POST">
        <table class="comment-table">
            <tr>
                <th>Comment Supervisor</th>
            </tr>
            <tr>
                <td>
                    <textarea class="comment-input" name="comment_supervisor" rows="4" cols="50" <?php echo $alreadyApproved ? 'readonly' : ''; ?>><?php echo $reportDetails['comment_supervisor']; ?></textarea>
                </td>
            </tr>
        </table>

        <button class="comment-btn" type="submit" <?php echo $alreadyApproved ? 'disabled' : ''; ?>>Approve Report</button>

        <button class="go-back-btn" onclick="goBack()">Go Back</button>
    </div>

    <script>
        // JavaScript function to go back
        function goBack() {
            window.history.back();
        }
    </script>
    </form>
</div>

</body>

</html>
