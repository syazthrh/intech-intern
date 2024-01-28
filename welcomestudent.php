<?php
session_start();
include("dbconnect.php");

$dbconn = mysqli_connect('localhost', 'root', '', 'intech-intern');

if (!$dbconn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$user_query = "SELECT full_name FROM users WHERE username = '$username'";
$user_result = mysqli_query($dbconn, $user_query);

if ($user_result && $user_row = mysqli_fetch_assoc($user_result)) {
    $full_name = $user_row['full_name'];
} else {
    header("Location: login.php");
    exit();
}

$main_company_query = "SELECT company_id, company_name, logo_url FROM company ORDER BY company_id LIMIT 1";
$main_company_result = mysqli_query($dbconn, $main_company_query);

if ($main_company_result && $main_company_row = mysqli_fetch_assoc($main_company_result)) {
    $main_company = $main_company_row;
} else {
    $main_company = [
        'company_id' => 0,
        'company_name' => 'Default Company',
        'logo_url' => 'default_logo_url.jpg'
    ];
}

$other_companies_query = "SELECT company_id, company_name, logo_url FROM company ORDER BY company_id LIMIT 1, 5";
$other_companies_result = mysqli_query($dbconn, $other_companies_query);

if (isset($_POST['find_internship'])) {
    $search = mysqli_real_escape_string($dbconn, $_POST['search'] ?? '');
    $where = mysqli_real_escape_string($dbconn, $_POST['where'] ?? '');
    $salary = mysqli_real_escape_string($dbconn, $_POST['salary'] ?? '');
    $jobType = mysqli_real_escape_string($dbconn, $_POST['job_type'] ?? '');
    $tools = mysqli_real_escape_string($dbconn, $_POST['tools_used'] ?? '');

    $sql = "SELECT * FROM company WHERE 
        (company_name LIKE '%$search%' OR job_type LIKE '%$search%')
        AND location LIKE '%$where%'";

    if (!empty($salary)) {
        $sql .= " AND salary LIKE '%$salary%'";
    }

    if (!empty($jobType)) {
        $sql .= " AND job_type = '$jobType'";
    }

    if (!empty($tools)) {
        $sql .= " AND tools_used LIKE '%$tools%'";
    }

    $result = mysqli_query($dbconn, $sql);
    
    $companies = ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
} else {
    $sql = "SELECT * FROM company";
    $result = mysqli_query($dbconn, $sql);

    $companies = ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Intech Intern</title>
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

        #logo-container {
            display: flex;
            align-items: center;
        }

        #logo {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: medium;
            color: black;
            margin-right: 20px;
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
            font-size: 20px;
            font-weight: medium;
        }

        .nav-item a:hover {
            background-color: #DBAFA2;
            padding: 5px 10px;
            border-radius: 5px;
        }

        #update {
            font-size: 20px;
            font-weight: medium;
            color: black;
            display: flex;
            align-items: center;
        }

        #update a {
            text-decoration: none;
            color: black;
            margin-right: 5px; 
        }

        #update a:hover {
            background-color: #DBAFA2;
            padding: 5px 10px;
            border-radius: 5px;
        }

        #welcome,
        #student-name,
        #main-company,
        #company-logo,
        .other-companies,
        #content {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        #welcome,
        #student-name,
        #main-company {
            font-family: 'Black Han Sans', sans-serif;
            font-size: 28px;
            color: black;
        }

        #company-logo {
            width: auto;
            height: auto;
        }

        .other-companies {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .company-item {
            margin: 0 20px 20px 0;
            text-align: center;
        }

        .company-logo {
            width: auto;
            height: auto;
        }

        #content {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
        margin-bottom: 20px;
        text-align: center; 
    }

    form label,
    form select {
        display: block;
    }

    form select {
        width: 50%; 
        padding: 8px; 
        box-sizing: border-box;
        margin: 10px auto; 
    }

    form input {
        width: 50%; 
        padding: 8px; 
        box-sizing: border-box;
        margin: 10px auto; 
    }

    button {
        background-color: #3498db;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 50%; /* Adjust the width of the button */
        margin: 10px auto; /* Center-align the button */
    }
        #results {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .company-box {
            width: calc(33.33% - 20px);
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 20px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .company-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-description {
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <header>
        <div id="logo-container">
            <div id="logo">INTECH INTERN</div>
        </div>
        <div class="nav-links">
            <div class="nav-item"><a href="welcomestudent.php" id="home">HOME</a></div>
        </div>
        <div id="update">
            <div id="search-icon"><a href="updateintern.php">🔍UPDATE INTERNSHIP </a></div>
        <div><a href="login.php">LOG OUT</a></div>
        </div>
    </header>


    <div id="welcome">HI, Welcome "<?php echo $full_name; ?>"</div> 

    <form method="post" action="">
        <label for="search">What:</label>
        <input type="text" name="search" placeholder="Type your specification">

        <label for="where">Where:</label>
        <input type="text" name="where" placeholder="Type the location">

        <label for="salary">Salary:</label>
        <select name="salary">
            <option value="" selected disabled>Select Salary</option>
            <option value="0.00">0 </option>
            <option value="500.00">RM500 </option>
            <option value="1000.00">RM1000</option>
            <option value="1500.00">RM1500</option>
        </select>

        <label for="job_type">Job Type:</label>
        <select name="job_type">
            <option value="" selected disabled>Select Job Type</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Software Developer">Software Developer</option>
            <option value="Web Designer">Web Designer</option>
        </select>

        <label for="tools_used">Tools:</label>
        <select name="tools_used">
            <option value="" selected disabled>Select Tools</option>
            <option value="HTML">Html</option>
            <option value="CSS">Css</option>
            <option value="JavaScript">JavaScript</option>
        </select>

        <button type="submit" name="find_internship">FIND INTERNSHIP</button>
    </form>

    <?php if (!empty($companies)): ?>
        <div id="results">
            <?php foreach ($companies as $row): ?>
                <div class="company-box">
                    <a href="interndetails.php?company_id=<?= $row['company_id'] ?>">
                        <div class="company-info">
                            <p class="company-name"><?= $row['company_name'] ?></p>
                            <img src="<?= $row['logo_url'] ?>" alt="<?= $row['company_name'] ?> Logo">
                            <p class="company-description"><?= $row['job_type'] ?> Internship<br><?= $row['salary'] ?> per month<br><?= $row['location'] ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>

</html>
