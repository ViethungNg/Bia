<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['maTaiKhoan'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin từ biểu mẫu
$maTaiKhoan = $_POST['maTaiKhoan'];
$hoTen = $_POST['hoTen'];
$soDienThoai = $_POST['soDienThoai'];
$email = $_POST['email'];

// Cập nhật thông tin tài khoản
$sql = "UPDATE taikhoan SET hoTen = ?, soDienThoai = ?, email = ? WHERE maTaiKhoan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $hoTen, $soDienThoai, $email, $maTaiKhoan);

if ($stmt->execute()) {
    header("Location: manage_taiKhoan_User.php?success=1");
} else {
    echo "Có lỗi xảy ra: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
