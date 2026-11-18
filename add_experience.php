<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['submit']))
{
    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $start_date = $_POST['start_date'];
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("
        INSERT INTO experience
        (job_title, company_name, start_date, end_date, description)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssss",
        $job_title,
        $company_name,
        $start_date,
        $end_date,
        $description
    );

    if($stmt->execute())
    {
        $message = "Experience added successfully.";
    }
    else
    {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Experience</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<h1>Add Experience</h1>

<p>
    <a href="admin.php">Back to Dashboard</a>
</p>

<form method="POST">

    <label>Job Title</label><br>
    <input type="text" name="job_title" required>
    <br><br>

    <label>Company Name</label><br>
    <input type="text" name="company_name" required>
    <br><br>

    <label>Start Date</label><br>
    <input type="date" name="start_date" required>
    <br><br>

    <label>End Date (leave empty if current job)</label><br>
    <input type="date" name="end_date">
    <br><br>

    <label>Description</label><br>
    <textarea name="description" rows="5" cols="50" required></textarea>
    <br><br>

    <button type="submit" name="submit">
        Add Experience
    </button>

</form>

<p><?php echo $message; ?></p>

</body>
</html>