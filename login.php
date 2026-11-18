<?php
session_start();
include 'db.php';

$error = "";

if(isset($_POST['login']))
{
    $email = $_POST['email'];//keeshaab12@gmail.com
    $password = $_POST['password'];//keshab123

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password']))
        {
            $_SESSION['user_id'] = $user['id'];
            header("Location: admin.php");
            exit();
        }
        else
        {
            $error = "Invalid Password";
        }
    }
    else
    {
        $error = "User Not Found";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

<h2>Admin Login</h2>

<form method="POST">
    Email:
    <input type="email" name="email" required><br><br>

    Password:
    <input type="password" name="password" required><br><br>

    <button type="submit" name="login">Login</button>
</form>

<p><?php echo $error; ?></p>

</body>
</html>