<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Xử lý xóa bàn
if (isset($_GET['delete'])) {
    $maBanBia = $_GET['delete'];
    $delete_sql = "DELETE FROM banbia WHERE maBanBia=?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $maBanBia);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Xóa bàn thành công!";
    } else {
        $_SESSION['message'] = "Lỗi xóa bàn: " . $conn->error;
    }
    $stmt->close();
    header("Location: manage_banbia.php");
    exit();
}
?>
