<?php
// Include the database connection file
include 'dbconnect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data to prevent SQL injection
    $studentUsername = mysqli_real_escape_string($dbconn, $_POST['student_username']);
    $supervisorUsername = mysqli_real_escape_string($dbconn, $_POST['supervisor_username']);
    $appropriateness = mysqli_real_escape_string($dbconn, $_POST['appropriateness']);
    $training = mysqli_real_escape_string($dbconn, $_POST['training']);
    $difficultyLevel = mysqli_real_escape_string($dbconn, $_POST['difficulty_level']);
    $teamwork = mysqli_real_escape_string($dbconn, $_POST['teamwork']);
    $communicationSkills = mysqli_real_escape_string($dbconn, $_POST['communication_skills']);
    $adaptability = mysqli_real_escape_string($dbconn, $_POST['adaptability']);
    $problemSolvingSkills = mysqli_real_escape_string($dbconn, $_POST['problem_solving_skill']);
    $timeManagement = mysqli_real_escape_string($dbconn, $_POST['time_management']);

    // Check if an assessment already exists for the student
    $assessmentExistsQuery = "SELECT * FROM assessments WHERE user_id = '$studentUsername' AND supervisor_id = '$supervisorUsername'";
    $assessmentExistsResult = mysqli_query($dbconn, $assessmentExistsQuery);

    if ($assessmentExistsResult) {
        $assessmentExists = mysqli_num_rows($assessmentExistsResult) > 0;

        if ($assessmentExists) {
            // Update the existing assessment
            $updateAssessmentQuery = "UPDATE assessments
                                      SET appropriateness = '$appropriateness',
                                          training = '$training',
                                          difficulty_level = '$difficultyLevel',
                                          teamwork = '$teamwork',
                                          communication_skills = '$communicationSkills',
                                          adaptability = '$adaptability',
                                          problem_solving_skill = '$problemSolvingSkills',
                                          time_management = '$timeManagement',
                                          submission_date = NOW()
                                      WHERE user_id = '$studentUsername' AND supervisor_id = '$supervisorUsername'";
            
            // Execute the query
            if (mysqli_query($dbconn, $updateAssessmentQuery)) {
                echo "Assessment updated successfully!";
            } else {
                echo "Error: " . $updateAssessmentQuery . "<br>" . mysqli_error($dbconn);
            }
        } else {
            // Insert a new assessment if it doesn't exist
            $insertAssessmentQuery = "INSERT INTO assessments (user_id, supervisor_id, appropriateness, training, difficulty_level, teamwork, communication_skills, adaptability, problem_solving_skill, time_management, submission_date)
                                     VALUES ('$studentUsername', '$supervisorUsername', '$appropriateness', '$training', '$difficultyLevel', '$teamwork', '$communicationSkills', '$adaptability', '$problemSolvingSkills', '$timeManagement', NOW())";

            // Execute the query
            if (mysqli_query($dbconn, $insertAssessmentQuery)) {
                echo "Assessment submitted successfully!";
            } else {
                echo "Error: " . $insertAssessmentQuery . "<br>" . mysqli_error($dbconn);
            }
        }
    } else {
        echo "Error checking existing assessment: " . mysqli_error($dbconn);
    }

    // Fetch details for the selected student
    $studentDetailsQuery = "SELECT * FROM assessments WHERE user_id = '$studentUsername' AND supervisor_id = '$supervisorUsername'";
    $studentDetailsResult = mysqli_query($dbconn, $studentDetailsQuery);
    $studentDetails = mysqli_fetch_assoc($studentDetailsResult);

    // Close the database connection
    mysqli_close($dbconn);
} else {
    // Redirect to the assessment page if the form is not submitted
    header("Location: assessment.php");
    exit();
}
?>
