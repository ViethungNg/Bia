<?php
include '../../Connect/connect.php';
session_start();

if (isset($_GET['maLichDat'])) {
    $maLichDat = $_GET['maLichDat'];

    $sql = "UPDATE lichdat SET trangThai = 'Đã hủy' WHERE maLichDat = '$maLichDat'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_taikhoan_USER.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
