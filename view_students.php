<?php
// Include database connection file
include 'dbconnect.php';

// Fetch students who are not in the student_internships table
$nonInternshipStudentsQuery = "SELECT username, full_name, email_address FROM users WHERE username NOT IN (SELECT username FROM student_internships)";
$nonInternshipStudentsResult = mysqli_query($dbconn, $nonInternshipStudentsQuery);

// Fetch students from the users table with company details (excluding those without internships)
$studentsQuery = "SELECT u.username, u.full_name, u.email_address, s.c_name, c.full_name AS csupervisor_name, c.email_address AS cemail_address, s.usupervisor_id
                  FROM users u
                  LEFT JOIN student_internships s ON u.username = s.username
                  LEFT JOIN c_supervisor c ON s.c_supervisor_id = c.username
                  WHERE s.c_name IS NOT NULL"; // Exclude students without internships
$studentsResult = mysqli_query($dbconn, $studentsQuery);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = mysqli_real_escape_string($dbconn, $_POST['username']);
    $usupervisorId = mysqli_real_escape_string($dbconn, $_POST['usupervisor_id']);

    // Update the student_internships table with usupervisor_id
    $updateQuery = "UPDATE student_internships SET usupervisor_id = '$usupervisorId' WHERE username = '$username'";
    mysqli_query($dbconn, $updateQuery);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Students</title>
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

        .students-table {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #DBAFA2;
            color: #fff;
        }

        .usupervisor-form {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        label {
            font-size: 16px;
            color: #343a40;
            display: block;
            margin-bottom: 5px;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
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

        .go-back-btn {
            padding: 10px 20px;
            background-color: #c54b8c;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
            
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

    <div class="container">
        <!-- Students without internships table -->
        <div class="students-table">
            <h1>Students without Internships</h1>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($nonInternshipStudentsResult)) {
                        echo "<tr>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['full_name']}</td>";
                        echo "<td>{$row['email_address']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Students with internships table -->
        <div class="students-table">
            <h1>Students with Internships</h1>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Company Name</th>
                        <th>Company Supervisor</th>
                        <th>Email Supervisor</th>
                        <th>Usupervisor ID</th>
                        <th>Progress Report</th>
                        <th>Assessment</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($studentsResult)) {
                    echo "<tr>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['full_name']}</td>";
                    echo "<td>{$row['email_address']}</td>";
                    echo "<td>{$row['c_name']}</td>";
                    echo "<td>{$row['csupervisor_name']}</td>";
                    echo "<td>{$row['cemail_address']}</td>";
                    echo "<td>{$row['usupervisor_id']}</td>";
                    echo "<td><a href='view_reports.php?username={$row['username']}'>View</a></td>";
                    echo "<td><a href='view_assessment.php?username={$row['username']}'>View</a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Update Usupervisor ID form -->
        <div class="usupervisor-form">
            <h1>Update Usupervisor ID</h1>
            <form method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="usupervisor_id">Usupervisor ID</label>
                <input type="text" id="usupervisor_id" name="usupervisor_id" required>

                <button type="submit">Update</button>
                
            </form>
        </div>
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
