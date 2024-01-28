<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Admin</title>
    <style>
        body {
            background-color: #FDE7D8;
            margin: 0;
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
        }

        header {
            background-color: #343a40;
            color: #FDE7D8;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #FDE7D8;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }

        .box {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
            text-align: center;
            width: 200px;
        }

        a {
            text-decoration: none;
            color: #343a40;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.3s;
        }

        a:hover {
            color: #DBAFA2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, Admin!</h1>
        <p>Choose an action from the navigation menu.</p>
    </header>

    <div class="container">
        <div class="box">
            <a href="update_companies.php">Update Internship Companies</a>
        </div>

        <div class="box">
            <a href="view_students.php">View Students List</a>
        </div>

        <div class="box">
            <a href="view_reports.php">View Progress Reports</a>
        </div>

        <div class="box">
            <a href="view_assessments.php">View and Edit Assessments</a>
        </div>
    </div>
</body>
</html>
