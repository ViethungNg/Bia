<?php
include '../../Connect/connect.php';
session_start();

if (isset($_GET['maLichDat'])) {
    $maLichDat = $_GET['maLichDat'];

    $sql = "DELETE FROM lichdat WHERE maLichDat='$maLichDat'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_lichdat_user.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
