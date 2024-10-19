<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Lấy thông tin từ form
$maBanBia = $_POST['maBanBia'];
$maTaiKhoan = $_POST['maTaiKhoan'];
$ngayDat = $_POST['ngayDat'];
$gioBatDau = $_POST['gioBatDau'];
$gioKetThuc = $_POST['gioKetThuc'];
$trangThai = "Chờ duyệt"; // Gán trạng thái mặc định

// Thực hiện câu lệnh SQL để thêm lịch đặt vào cơ sở dữ liệu
$sql = "INSERT INTO lichdat (maBanBia, maTaiKhoan, ngayDat, gioBatDau, gioKetThuc, trangThai) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $maBanBia, $maTaiKhoan, $ngayDat, $gioBatDau, $gioKetThuc, $trangThai);

if ($stmt->execute()) {
    // Chuyển hướng về trang quản lý lịch đặt sau khi thêm thành công
    header("Location: manage_lichdat.php?success=1");
} else {
    echo "Lỗi: " . $stmt->error;
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
