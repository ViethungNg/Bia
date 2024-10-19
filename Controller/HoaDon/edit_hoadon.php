<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

if (isset($_POST['update'])) {
    $maHoaDon = $_POST['maHoaDon'];
    $maLichDat = $_POST['maLichDat'];
    $ngayThanhToan = $_POST['ngayThanhToan'];
    $tongTien = $_POST['tongTien'];

    // Cập nhật hóa đơn
    $sql = "UPDATE hoadon SET maLichDat=?, ngayThanhToan=?, tongTien=? WHERE maHoaDon=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdi", $maLichDat, $ngayThanhToan, $tongTien, $maHoaDon);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật hóa đơn thành công!";
    } else {
        $_SESSION['message'] = "Lỗi: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: manage_hoadon.php");
    exit();
}
?>
