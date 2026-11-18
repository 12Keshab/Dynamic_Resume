<?php
session_start();
include 'db.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM experience WHERE id = ?");
$stmt->bind_param("i", $id);

if($stmt->execute())
{
    echo "Deleted successfully";
}
else
{
    echo "Error: " . $stmt->error;
    echo "Affected rows: " . $stmt->affected_rows;

}
?>