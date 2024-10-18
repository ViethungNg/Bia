<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Xử lý thêm bàn
if (isset($_POST['add'])) {
    $tenBanBia = $_POST['tenBanBia'];
    $trangThai = $_POST['trangThai'];

    $insert_sql = "INSERT INTO banbia (tenBanBia, trangThai) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ss", $tenBanBia, $trangThai);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Thêm bàn thành công!";
    } else {
        $_SESSION['message'] = "Lỗi thêm bàn: " . $conn->error;
    }

    $stmt->close();
    header("Location: manage_banbia.php");
}

// Đóng kết nối
$conn->close();
?>
