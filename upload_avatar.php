<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['upload']))
{
    if(isset($_FILES['avatar']))
    {
        if($_FILES['avatar']['error'] !== UPLOAD_ERR_OK)
        {
            $message = "File upload failed.";
        }
        else
        {
            $allowedTypes = [
                'image/jpeg',
                'image/jpg',
                'image/png'
            ];

            $fileType = mime_content_type($_FILES['avatar']['tmp_name']);

            if(!in_array($fileType, $allowedTypes))
            {
                $message = "Only JPG and PNG images are allowed.";
            }
            elseif($_FILES['avatar']['size'] > 2 * 1024 * 1024)
            {
                $message = "File size must not exceed 2MB.";
            }
            else
            {
                if(!is_dir("uploads"))
                {
                    mkdir("uploads", 0777, true);
                }

                $extension = strtolower(
                    pathinfo(
                        $_FILES['avatar']['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $uniqueName = uniqid("avatar_", true);

                $originalFile =
                    "uploads/" . $uniqueName . "." . $extension;

                $thumbnailFile =
                    "uploads/" . $uniqueName . "_thumb.jpg";

                if(move_uploaded_file(
                    $_FILES['avatar']['tmp_name'],
                    $originalFile
                ))
                {
                    if($fileType == 'image/png')
                    {
                        $source =
                            imagecreatefrompng($originalFile);
                    }
                    else
                    {
                        $source =
                            imagecreatefromjpeg($originalFile);
                    }

                    if($source)
                    {
                        $thumbWidth = 150;
                        $thumbHeight = 150;

                        $thumbnail =
                            imagescale(
                                $source,
                                $thumbWidth,
                                $thumbHeight
                            );

                        imagejpeg(
                            $thumbnail,
                            $thumbnailFile,
                            90
                        );

                        imagedestroy($source);
                        imagedestroy($thumbnail);

                        $userId = $_SESSION['user_id'];

                        $stmt = $conn->prepare(
                            "UPDATE users
                             SET avatar_path = ?
                             WHERE id = ?"
                        );

                        $stmt->bind_param(
                            "si",
                            $thumbnailFile,
                            $userId
                        );

                        if($stmt->execute())
                        {
                            $message =
                                "Profile image uploaded successfully.";
                        }
                        else
                        {
                            $message =
                                "Database update failed.";
                        }
                    }
                    else
                    {
                        $message =
                            "Image processing failed.";
                    }
                }
                else
                {
                    $message =
                        "Unable to move uploaded file.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Upload Profile Picture</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<h1>Upload Profile Picture</h1>

<p>
    <a href="admin.php">Back to Dashboard</a>
</p>

<form
    method="POST"
    enctype="multipart/form-data">

<input
    type="file"
    name="avatar"
    accept=".jpg,.jpeg,.png"
    required>

<br><br>

<button
    type="submit"
    name="upload">
    Upload Image
</button>

</form>

<p><?php echo $message; ?></p>

</body>
</html>
