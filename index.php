<?php
include 'db.php';

/* Get user profile */
$userResult = $conn->query(
    "SELECT email, avatar_path FROM users LIMIT 1"
);

$user = null;

if($userResult && $userResult->num_rows > 0)
{
    $user = $userResult->fetch_assoc();
}

/* Get experience records */
$experienceResult = $conn->query(
    "SELECT * FROM experience
     ORDER BY start_date DESC"
);
?>

<!DOCTYPE html>

<html>
<head>
    <title>My Resume</title>
    <link rel="stylesheet" href="css/index.css">
<style>
   
</style>

</head>

<body>

<div class="profile">

<?php
if($user && !empty($user['avatar_path']))
{
?>

<img
    src="<?php echo htmlspecialchars($user['avatar_path']); ?>"
    alt="Profile Picture">
<?php
}
?>

<h1>Keshab Rana Chhetri</h1>

<?php
if($user)
{
?>

<p>
    Email:
    <?php echo htmlspecialchars($user['email']); ?>
</p>
<?php
}
?>

</div>

<hr>

<h2>Professional Experience</h2>

<?php
if($experienceResult &&
   $experienceResult->num_rows > 0)
{
    while($row =
        $experienceResult->fetch_assoc())
    {
?>

<div class="experience">

<h3>
<?php
echo htmlspecialchars(
    $row['job_title']
);
?>
</h3>

<p>
<strong>Company:</strong>
<?php
echo htmlspecialchars(
    $row['company_name']
);
?>
</p>

<p>
<strong>Duration:</strong>

<?php
echo htmlspecialchars(
    $row['start_date']
);
?>

to

<?php
echo $row['end_date']
    ? htmlspecialchars(
        $row['end_date']
      )
    : "Present";
?>

</p>

<p>
<?php
echo nl2br(
    htmlspecialchars(
        $row['description']
    )
);
?>
</p>

</div>

<?php
    }
}
else
{
?>

<p>
No professional experience records found.
</p>
<?php
}
?>

</body>
</html>
