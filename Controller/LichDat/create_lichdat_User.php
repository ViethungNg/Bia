<?php
include '../../Connect/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maTaiKhoan = $_POST['maTaiKhoan'];
    $maBanBia = $_POST['maBanBia'];
    $ngayDat = $_POST['ngayDat'];
    $gioBatDau = $_POST['gioBatDau'];
    $gioKetThuc = $_POST['gioKetThuc'];
    $trangThai = $_POST['trangThai'];

    $sql = "INSERT INTO lichdat (maTaiKhoan, maBanBia, ngayDat, gioBatDau, gioKetThuc, trangThai) VALUES ('$maTaiKhoan', '$maBanBia', '$ngayDat', '$gioBatDau', '$gioKetThuc', '$trangThai')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_lichdat_user.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
