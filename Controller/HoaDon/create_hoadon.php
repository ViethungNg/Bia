<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

if (isset($_POST['add'])) {
    $maLichDat = $_POST['maLichDat'];
    $ngayThanhToan = $_POST['ngayThanhToan'];
    $tongTien = $_POST['tongTien'];

    $sql = "INSERT INTO hoadon (maLichDat, ngayThanhToan, tongTien) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $maLichDat, $ngayThanhToan, $tongTien);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Thêm hóa đơn thành công!";
    } else {
        $_SESSION['message'] = "Lỗi: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: manage_hoadon.php");
    exit();
}
?>
