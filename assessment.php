<?php
// Include the database connection file
include 'dbconnect.php';

// Start the session
session_start();

// Assuming you have the student's username and supervisor's username passed through the session
$studentUsername = $_GET['username'];
$supervisorUsername = $_SESSION['username'];

// Fetch details for the selected student
$studentDetailsQuery = "SELECT u.username, u.full_name, si.c_name
                        FROM users u
                        LEFT JOIN student_internships si ON u.username = si.username
                        LEFT JOIN company c ON si.company_id = c.company_id
                        WHERE u.username = '$studentUsername'";
$studentDetailsResult = mysqli_query($dbconn, $studentDetailsQuery);
$studentDetails = mysqli_fetch_assoc($studentDetailsResult);

// Check if assessment exists for the student
$assessmentExistsQuery = "SELECT * FROM assessments WHERE user_id = '$studentUsername'";
$assessmentExistsResult = mysqli_query($dbconn, $assessmentExistsQuery);
$assessmentExists = mysqli_num_rows($assessmentExistsResult) > 0;

// Fetch existing assessment if it exists
$existingAssessment = [];
if ($assessmentExists) {
    $existingAssessmentQuery = "SELECT appropriateness, training, difficulty_level, teamwork, 
                                        communication_skills, adaptability, problem_solving_skill, time_management
                               FROM assessments 
                               WHERE user_id = '$studentUsername'";
    $existingAssessmentResult = mysqli_query($dbconn, $existingAssessmentQuery);
    $existingAssessment = mysqli_fetch_assoc($existingAssessmentResult);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTECH-INTERN Assessment Page</title>
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

        .page-title {
            text-align: center;
            margin-top: -40px;
        }

        .page-title h1 {
            font-family: Black Mango, sans-serif;
            font-weight: 100;
            font-size: 45px;
        }

        .tables-container {
            text-align: center; 
            margin-top: 30px;
        }

        .border-table, .assessment-table {
            margin-top: 20px;
            width: 849.1px;
            margin: auto;
        }

        .assessment-table th, .assessment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .assessment-table th {
            background-color: #FF9999;
            height: 55.7px;
        }

        .assessment-table td {
            background-color: #FFCDCD;
        }

        .input-mark {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .border-table th, .border-table td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
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

    <div class="page-title">
        <h1>Assessment Page</h1>
    </div>

    <div class="tables-container">
        <!-- Border Table -->
        <table class="border-table">
            <tr>
                <th>MATRIC NUMBER</th>
                <th>STUDENT NAME</th>
                <th>COMPANY</th>
            </tr>
            <tr>
                <td><?php echo $studentDetails['username']; ?></td>
                <td><?php echo $studentDetails['full_name']; ?></td>
                <td><?php echo $studentDetails['c_name']; ?></td>
            </tr>
        </table>

        <!-- Assessment Table -->
        <form method="post" action="submit_assessment.php">
    <!-- Hidden input fields to send student username and session username -->
    <input type="hidden" name="student_username" value="<?php echo $studentDetails['username']; ?>">
    <input type="hidden" name="supervisor_username" value="<?php echo $supervisorUsername; ?>">
    <table class="assessment-table">
        <tr>
            <th>CRITERIA</th>
            <th>POOR - 1 ADEQUATE - 2 GOOD - 3 EXCELLENT - 4</th>
            <th>SCORE</th>
        </tr>
        <tr>
            <td>Appropriateness</td>
            <td>Most of the task given related to the Computer Science or Technology Information</td>
            <td>
                <?php
                if ($assessmentExists) {
                    echo '<input type="text" name="appropriateness" class="input-mark" value="' . $existingAssessment['appropriateness'] . '" readonly>';
                } else {
                    echo '<input type="text" name="appropriateness" class="input-mark">';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Training</td>
            <td>In-house or external training is given before the task assignments</td>
            <td>
                <?php
                if ($assessmentExists) {
                    echo '<input type="text" name="training" class="input-mark" value="' . $existingAssessment['training'] . '" readonly>';
                } else {
                    echo '<input type="text" name="training" class="input-mark">';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Difficulty Level</td>
            <td>The given tasks are appropriate for undergraduate students</td>
            <td>
                <?php
                if ($assessmentExists) {
                    echo '<input type="text" name="difficulty_level" class="input-mark" value="' . $existingAssessment['difficulty_level'] . '" readonly>';
                } else {
                    echo '<input type="text" name="difficulty_level" class="input-mark">';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Teamwork</td>
            <td>The given tasks require collaboration between teammates</td>
            <td>
                <?php
                if ($assessmentExists) {
                    echo '<input type="text" name="teamwork" class="input-mark" value="' . $existingAssessment['teamwork'] . '" readonly>';
                } else {
                    echo '<input type="text" name="teamwork" class="input-mark">';
                }
                ?>
            </td>
            <tr>
                <td>Communication Skills</td>
                <td>Effectiveness in conveying information</td>
                <td>
                    <?php
                    if ($assessmentExists) {
                        echo '<input type="text" name="communication_skills" class="input-mark" value="' . $existingAssessment['communication_skills'] . '" readonly>';
                    } else {
                        echo '<input type="text" name="communication_skills" class="input-mark">';
                    }
                    ?>
                </td>
            </tr>
        </tr>
        <tr>
                <td>Adaptability</td>
                <td>Ability to adapt to changing situations</td>
                <td>
                    <?php
                    if ($assessmentExists) {
                        echo '<input type="text" name="adaptability" class="input-mark" value="' . $existingAssessment['adaptability'] . '" readonly>';
                    } else {
                        echo '<input type="text" name="adaptability" class="input-mark">';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Problem Solving Skills</td>
                <td>Ability to analyze and solve problems</td>
                <td>
                    <?php
                    if ($assessmentExists) {
                        echo '<input type="text" name="problem_solving_skill" class="input-mark" value="' . $existingAssessment['problem_solving_skill'] . '" readonly>';
                    } else {
                        echo '<input type="text" name="problem_solving_skill" class="input-mark">';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Time Management</td>
                <td>Effective use of time to complete tasks</td>
                <td>
                    <?php
                    if ($assessmentExists) {
                        echo '<input type="text" name="time_management" class="input-mark" value="' . $existingAssessment['time_management'] . '" readonly>';
                    } else {
                        echo '<input type="text" name="time_management" class="input-mark">';
                    }
                    ?>
                </td>
            </tr>
        </table>


    <?php
    if (!$assessmentExists) {
        echo '<div style="position: fixed; bottom: 20px; right: 120px;">
            <button type="submit" style="background-color: #DBAFA2; color: black; padding: 10px 20px; border: none; border-radius: 5px;">Submit</button>
        </div>';

    }
    ?>
        </form>
    </div>
</body>

</html>
