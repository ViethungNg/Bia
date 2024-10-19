<?php
include '../../Connect/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maLichDat = $_POST['maLichDat'];
    $maBanBia = $_POST['maBanBia'];
    $maTaiKhoan = $_POST['maTaiKhoan'];
    $ngayDat = $_POST['ngayDat'];
    $gioBatDau = $_POST['gioBatDau'];
    $gioKetThuc = $_POST['gioKetThuc'];
    $trangThai = $_POST['trangThai'];

    // Cập nhật thông tin lịch đặt
    $sql = "UPDATE lichdat SET maBanBia = ?, maTaiKhoan = ?, ngayDat = ?, gioBatDau = ?, gioKetThuc = ?, trangThai = ? WHERE maLichDat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssi", $maBanBia, $maTaiKhoan, $ngayDat, $gioBatDau, $gioKetThuc, $trangThai, $maLichDat);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: manage_lichdat.php?msg=Cập nhật lịch đặt thành công");
    } else {
        header("Location: manage_lichdat.php?msg=Không thể cập nhật lịch đặt");
    }

    $stmt->close();
}

$conn->close();
?>
