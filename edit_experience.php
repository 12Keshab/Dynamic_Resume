<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM experience WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0)
{
    header("Location: admin.php");
    exit();
}

$row = $result->fetch_assoc();

$message = "";

if(isset($_POST['update']))
{
    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $start_date = $_POST['start_date'];

    $end_date = !empty($_POST['end_date'])
        ? $_POST['end_date']
        : NULL;

    $description = trim($_POST['description']);

    $update = $conn->prepare("
        UPDATE experience
        SET
            job_title = ?,
            company_name = ?,
            start_date = ?,
            end_date = ?,
            description = ?
        WHERE id = ?
    ");

    $update->bind_param(
        "sssssi",
        $job_title,
        $company_name,
        $start_date,
        $end_date,
        $description,
        $id
    );

    if($update->execute())
    {
        $message = "Record updated successfully.";

        $stmt = $conn->prepare(
            "SELECT * FROM experience WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    }
    else
    {
        $message = "Update failed.";
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Edit Experience</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<h1>Edit Experience</h1>

<p>
    <a href="admin.php">Back to Dashboard</a>
</p>

<form method="POST">

<label>Job Title</label><br>
<input
    type="text"
    name="job_title"
    value="<?php echo htmlspecialchars($row['job_title']); ?>"
    required>
<br><br>

<label>Company Name</label><br>
<input
    type="text"
    name="company_name"
    value="<?php echo htmlspecialchars($row['company_name']); ?>"
    required>
<br><br>

<label>Start Date</label><br>
<input
    type="date"
    name="start_date"
    value="<?php echo $row['start_date']; ?>"
    required>
<br><br>

<label>End Date</label><br>
<input
    type="date"
    name="end_date"
    value="<?php echo $row['end_date']; ?>">
<br><br>

<label>Description</label><br>
<textarea
    name="description"
    rows="5"
    cols="50"
    required><?php echo htmlspecialchars($row['description']); ?></textarea>
<br><br>

<button type="submit" name="update">
    Update Experience
</button>

</form>

<p><?php echo $message; ?></p>

</body>
</html>
