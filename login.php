<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    $dbconn = mysqli_connect('localhost', 'root', '', 'intech-intern');

    // Check connection
    if (!$dbconn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user_type'];
    $redirectPage = '';

    switch ($userType) {
        case 'users':
            $query = "SELECT * FROM student_internships WHERE username='$username'";
            $result = mysqli_query($dbconn, $query);

            if (mysqli_num_rows($result) != 0) {
                // If in the student_internships table, redirect to welcomeconfirm.php
                $redirectPage = 'welcomeconfirm.php';
            } else {
                // If not in the student_internships table, redirect to welcomestudent.php
                $redirectPage = 'welcomestudent.php';
            }
            break;
        case 'supervisor':
            $redirectPage = 'welcomesupervisor.php';
            break;
        case 'c_supervisor':
            $redirectPage = 'welcomecompanysupervisor.php';
            break;
        case 'admin':
            $redirectPage = 'admin_welcome.php';
            break;
        default:
            $error_message = "Invalid user type!";
            break;
    }

    // Check credentials in the respective table
    $query = "SELECT * FROM $userType WHERE username='$username' AND password='$password'";
    $result = mysqli_query($dbconn, $query);

    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username']; // Set the username in the session

        // Redirect based on user type and username check
        header("Location: $redirectPage");
        exit();
    } else {
        // Invalid credentials
        $error_message = "Invalid username or password!";
    }

    mysqli_close($dbconn);
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTECH INTERN KICT INTERNSHIP SYSTEM</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #fde7d8;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            max-width: 500px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Black Mango', sans-serif;
            font-size: 32px;
            margin-bottom: 30px;
            color: #dbafa2;
            text-align: center;
        }

        .input-box {
            background-color: #9a6b5b;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border-radius: 5px;
        }

        .input-box input {
            border: none;
            background: none;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
            color: #000;
            padding: 12px;
            border-radius: 5px;
            outline: none;
        }

        .icon {
            font-size: 24px;
            color: #fff;
            margin-right: 10px;
        }

        .user-type {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .user-type label {
            font-size: 16px;
            margin-right: 10px;
            color: #000;
        }

        .login-button {
            background-color: #dbafa2;
            width: 100%;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin-bottom: 30px;
            color: #fff;
            font-size: 18px;
            transition: background-color 0.3s ease-in-out;
        }

        .login-button:hover {
            background-color: #c39486;
        }

        .signup-link,
        .forgot-password-link {
            text-decoration: underline;
            color: #000;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 16px;
        }

        ::placeholder {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>INTECH INTERN KICT INTERNSHIP SYSTEM</h1>
        <form method="post" action="#" role="login">
        <div class="user-type">
            <label for="student"><input type="radio" name="user_type" id="student" value="users" required> Student</label>
            <label for="supervisor"><input type="radio" name="user_type" id="supervisor" value="supervisor" required> Supervisor</label>
            <label for="c_supervisor"><input type="radio" name="user_type" id="c_supervisor" value="c_supervisor" required> Company Supervisor</label>
            <label for="admin"><input type="radio" name="user_type" id="admin" value="admin" required> Admin</label>
        </div>
        <div class="input-box">
            <input type="text" name="username" id="username" placeholder="👤 Username" required autocomplete="username">
        </div>
        <div class="input-box">
            <input type="password" name="password" id="password" placeholder="🔒 Password" required autocomplete="current-password">
        </div>
            <?php if (isset($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <a href="signup.php" class="signup-link">Sign Up</a>
            <button type="submit" name="login" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>