<?php
include '../../Connect/connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maTaiKhoan = $_POST['maTaiKhoan'];
    $tenDangNhap = $_POST['tenDangNhap'];
    $loaiTaiKhoan = $_POST['loaiTaiKhoan'];
    $hoTen = $_POST['hoTen'];
    $soDienThoai = $_POST['soDienThoai'];
    $email = $_POST['email'];

    // Cập nhật tài khoản trong cơ sở dữ liệu
    $sql = "UPDATE taikhoan SET tenDangNhap = ?, loaiTaiKhoan = ?, hoTen = ?, soDienThoai = ?, email = ? WHERE maTaiKhoan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $tenDangNhap, $loaiTaiKhoan, $hoTen, $soDienThoai, $email, $maTaiKhoan);

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý tài khoản
        header("Location: manage_taiKhoan.php?success=1");
    } else {
        // Xử lý lỗi
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
