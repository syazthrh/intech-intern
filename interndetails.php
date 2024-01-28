<?php
include 'dbconnect.php';

function getCompanyDetailsById($companyId, $dbconn) {
    $query = "SELECT * FROM company WHERE company_id = '$companyId'";
    $result = mysqli_query($dbconn, $query);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

if (isset($_REQUEST['company_id'])) {
    $selectedCompanyId = $_REQUEST['company_id'];
    $companyDetails = getCompanyDetailsById($selectedCompanyId, $dbconn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Description</title>
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

        .internship-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 auto;
            max-width: 800px;
            padding: 20px;
            background-color: #FFF;
            border: 1px solid #DBAFA2;
            border-radius: 10px;
            margin-top: auto;
            text-align: center; 
        }

        .company-name {
            font-family: 'Black Mango', sans-serif;
            font-size: 40px;
            color: black;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .company-logo {
            width: auto;
            height: auto;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border: 2px solid #B68474; 
            border-radius: 10px;
        }

        .details-section {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
            margin-left: 150px;
        }

        .company-simple-details {
            flex: 1;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            line-height: 1.5;
            text-align: left; /* Align left for details */
        }

        .details-container {
            display: flex;
            flex: 1;
        }

        .company-details,
        .job-brief {
            flex: 1;
            background-color: #B68474;
            padding: 15px;
            border-radius: 10px;
            margin-left: 15px;
            margin-top: 20px;
            text-align: left; 
        }

        .company-details-box {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: bold;
            color: black;
            margin-bottom: 10px;
            margin-right: 30px;
        }

        .company-description,
        .job-brief-description {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .contact-box {
            border: 1px solid #B68474;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            margin-top: 20px;
            width: 50%;
            margin-left: 350px;
        }
        .contact-email {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            color: black;
        }

        .email-now-box {
            background-color: #B68474;
            padding: 10px;
            cursor: pointer;
            text-decoration: none;
            color: black;
            border-radius: 5px;
            margin-left: 30px;
        }

        .go-back {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            color: black;
            text-decoration: none;
            margin-top: 20px;
            display: block;
            text-align: center; 
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
    <?php
    if (isset($companyDetails)) {
        echo "<div class='internship-page'>";
        echo "<div class='company-name'>" . $companyDetails['company_name'] . "</div>";
        
        echo "<div class='details-section'>";
        echo "<img class='company-logo' src='" . $companyDetails['logo_url'] . "' alt='" . $companyDetails['company_name'] . " Logo'>";
        echo "<div class='company-simple-details'>";
        echo "<p>Salary: " . "RM " . $companyDetails['salary'] . " per month</p>";
        echo "<p>Shift and Schedule: " . $companyDetails['shift_schedule'] . "</p>";
        echo "<p>Job Type: " . $companyDetails['job_type'] . "</p>";
        echo "<p>Location: " . $companyDetails['location'] . "</p>";
        echo "<p>Tools Used: " . $companyDetails['tools_used'] . "</p>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='details-container'>"; // Container for company details and job brief
        echo "<div class='company-details'>";
        echo "<div class='company-details-box'>Details about " . $companyDetails['company_name'] . "</div>";
        echo "<p class='company-description'>" . $companyDetails['details'] . "</p>";
        echo "</div>";
        
        echo "<div class='job-brief'>";
        echo "<div class='company-details-box'>Job Brief</div>";
        echo "<p class='job-brief-description'>" . $companyDetails['job_brief'] . "</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>"; 

        echo "<div class='contact-box'>";
        echo "<div class='contact-email'>Email: " . $companyDetails['company_email'] . "</div>";
        echo "<a href='mailto:" . $companyDetails['company_email'] . "' class='email-now-box'>Email Now</a>";
        echo "</div>";

        echo "<a href='javascript:history.go(-1)' class='go-back'>Go Back</a>"; // Go Back link using JavaScript
        echo "</div>";
    }
    ?>
</body>

</html>
