<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM experience ORDER BY start_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<h1>Resume CMS Dashboard</h1>

<p>
    <a href="add_experience.php">Add Experience</a> |
    <a href="upload_avatar.php">Upload Profile Picture</a> |
    <a href="logout.php">Logout</a>
</p>

<h2>Experience Records</h2>

<?php if($result->num_rows > 0) { ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Job Title</th>
        <th>Company</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>

    <tr>

        <td><?php echo htmlspecialchars($row['job_title']); ?></td>

        <td><?php echo htmlspecialchars($row['company_name']); ?></td>

        <td><?php echo $row['start_date']; ?></td>

        <td>
            <?php
            echo $row['end_date']
                ? $row['end_date']
                : 'Present';
            ?>
        </td>

        <td>
            <?php echo htmlspecialchars($row['description']); ?>
        </td>

        <td>
            <a href="edit_experience.php?id=<?php echo $row['id']; ?>">
                Edit
            </a>

            |

            <a href="delete_experience.php?id=<?php echo $row['id']; ?>"
               onclick="return confirm('Are you sure you want to delete this record?');">
                Delete
            </a>
        </td>
    </tr>

    <?php } ?>

</table>

<?php } else { ?>

<p>No experience records found.</p>

<?php } ?>

</body>
</html>