<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Kiểm tra xem có tham số maTaiKhoan trong URL không
if (isset($_GET['maTaiKhoan'])) {
    $maTaiKhoan = $_GET['maTaiKhoan'];

    // Thực hiện câu lệnh SQL để xóa tài khoản
    $sql = "DELETE FROM taikhoan WHERE maTaiKhoan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maTaiKhoan);

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý tài khoản với thông báo thành công
        header("Location: manage_taiKhoan.php?success=1");
    } else {
        // Xử lý lỗi
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Nếu không có tham số, chuyển hướng về trang quản lý tài khoản
    header("Location: manage_taiKhoan.php?error=1");
}

$conn->close();
?>
