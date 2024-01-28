<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $full_name = $_POST['full_name'];
    $email_address = $_POST['email_address'];
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $birthdate = $_POST['birthdate'];
    $password = $_POST['password'];
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';

    // Check if username is not empty
    if (empty($username)) {
        echo "Username cannot be empty!";
        exit;
    }

    // Determine the corresponding table based on user type
    switch ($user_type) {
        case 'student':
            $tableName = 'users';
            break;
        case 'supervisor':
            $tableName = 'supervisor';
            break;
        case 'csupervisor':
            $tableName = 'c_supervisor';
            break;
        case 'admin':
            $tableName = 'admin';
            break;
        default:
            echo "Invalid user type!";
            exit;
    }

    // Insert user data into the respective table
    $sql = "INSERT INTO $tableName (full_name, email_address, username, birthdate, password) 
            VALUES ('$full_name', '$email_address', '$username', '$birthdate', '$password')";

    if ($dbconn->query($sql) === TRUE) {
        // Redirect to login.php after successful registration
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $dbconn->error;
    }
    }

    $dbconn->close();
    ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <style>
        body {
            background-color: #fde7d8;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .left-section {
            flex: 1;
            padding: 20px;
        }

        .right-section {
            flex: 1;
            background-image: url('https://news.iium.edu.my/wp-content/uploads/2016/05/uia.jpg');
            background-size: cover;
        }

        .title {
            font-family: 'Black Han Sans', sans-serif;
            font-size: 28px;
            color: #000;
        }

        .subtitle {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            color: #000;
        }

        .input-box {
            background-color: #dbafa2;
            padding: 15px;
            margin: 10px 0;
        }

        label {
            font-size: 20px;
            display: block;
            margin-bottom: 5px;
        }

        .input-field {
            font-size: 20px;
            width: 100%;
            padding: 5px;
        }

        #terms {
            margin-top: 20px;
        }

        #signup-btn {
            background-color: #241917;
            color: #b68474;
            padding: 20px;
            cursor: pointer;
            border: none;
            font-size: 20px;
        }

        #login-link {
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="title">Create your Account Here</div>
            <div class="subtitle">Register your account</div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Process the form data

                $full_name = $_POST["full_name"];
                $email_address = $_POST["email_address"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                $birthdate = $_POST["birthdate"];

                // Print the collected information
                echo "<div class='output-box'>";
                echo "<h2>User Information:</h2>";
                echo "<p><strong>Full Name:</strong> $full_name</p>";
                echo "<p><strong>Email:</strong> $email_address</p>";
                echo "<p><strong>Matric Number or Staff Number:</strong> $username</p>";
                echo "<p><strong>Password:</strong> $password</p>";
                echo "<p><strong>Birthdate:</strong> $birthdate</p>";
                echo "</div>";
            } else {
                ?>
                <form method="post" action="signup.php">
                    <div class="input-box">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="fullname" class="input-field" placeholder="Insert your full name" required>
                    </div>
                    <div class="input-box">
                        <label for="email_address">Email Address</label>
                        <input type="email_address" name="email_address" id="email" class="input-field" placeholder="Insert your email address" required>
                    </div>
                    <div class="input-box">
                        <label for="username">Matric or Staff Number</label>
                        <input type="text" name="username" id="username" class="input-field" placeholder="Insert your matric or staff number" required>
                    </div>
                    <div class="input-box">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="input-field" placeholder="Insert your password" required>
                    </div>
                    <div class="input-box">
                        <label for="birthdate">Birthday</label>
                        <input type="date" name="birthdate" id="birthday" class="input-field" required>
                    </div>
                    <div class="input-box">
                        <label for="user_type">Select User Type</label>
                        <select name="user_type" id="user_type" class="input-field" required>
                            <option value="student">Student</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="csupervisor">Company Supervisor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" id="signup-btn">Sign Up</button>
                </form>
            <?php
            }
            ?>
            <div id="login-link">Already have an account? <a href="login.php">Click here to sign in</a></div>
        </div>
        <div class="right-section"></div>
    </div>
</body>

</html>