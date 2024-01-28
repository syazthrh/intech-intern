<?php
include 'dbconnect.php';

// Function to handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricNumber = $_POST['username'];
    $password = $_POST['password'];
    $companyName = $_POST['c_name'];
    $supervisorName = $_POST['csupervisor_name'];
    $supervisorEmail = $_POST['email_csupervisor'];
    $offerLetterUrl = $_POST['offer_letter_url'];

    // Validate the user against the database using prepared statements
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($dbconn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $matricNumber, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Get user registration date
            $registrationDateQuery = "SELECT registration_date FROM users WHERE username = ?";
            $registrationDateStmt = mysqli_prepare($dbconn, $registrationDateQuery);

            if ($registrationDateStmt) {
                mysqli_stmt_bind_param($registrationDateStmt, "s", $matricNumber);
                mysqli_stmt_execute($registrationDateStmt);
                mysqli_stmt_store_result($registrationDateStmt);

                if (mysqli_stmt_num_rows($registrationDateStmt) > 0) {
                    mysqli_stmt_bind_result($registrationDateStmt, $registrationDate);
                    mysqli_stmt_fetch($registrationDateStmt);

                    // Check if the user registered in 2024
                    if (date('Y', strtotime($registrationDate)) == 2024) {
                        // User registered in 2024, set start and end dates accordingly
                        $startDate = '2024-03-04';
                        $endDate = '2024-09-09';
                    } else {
                        // User registered in a different year, set start and end dates to NULL
                        $startDate = null;
                        $endDate = null;
                    }

                    // Insert into the student_internships table using prepared statements
                    $insertQuery = "INSERT INTO student_internships (username, password, c_name, csupervisor_name, email_csupervisor, offer_letter_url, start_date, end_date)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $insertStmt = mysqli_prepare($dbconn, $insertQuery);

                    if ($insertStmt) {
                        mysqli_stmt_bind_param($insertStmt, "ssssssss", $matricNumber, $password, $companyName, $supervisorName, $supervisorEmail, $offerLetterUrl, $startDate, $endDate);
                        mysqli_stmt_execute($insertStmt);

                        // Close the insert statement
                        mysqli_stmt_close($insertStmt);

                        // Manually destroy the session
                        session_start();
                        session_destroy();

                        // Redirect to the login page
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "Insertion failed. Please try again.";
                    }
                } else {
                    echo "Error fetching registration date.";
                }

                // Close the registration date statement
                mysqli_stmt_close($registrationDateStmt);
            } else {
                echo "Database error. Please try again.";
            }
        } else {
            echo "Invalid credentials";
        }

        // Close the user validation statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Database error. Please try again.";
    }
}
?>
