<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

if (isset($_GET['id'])) {
    $maHoaDon = $_GET['id'];

    $sql = "DELETE FROM hoadon WHERE maHoaDon=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maHoaDon);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Xóa hóa đơn thành công!";
    } else {
        $_SESSION['message'] = "Lỗi: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: manage_hoadon.php");
    exit();
}
?>
