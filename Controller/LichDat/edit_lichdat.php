<?php
include '../../Connect/connect.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maLichDat = $_POST['maLichDat'];
    $maBanBia = $_POST['maBanBia'];
    $maTaiKhoan = $_POST['maTaiKhoan'];
    $ngayDat = $_POST['ngayDat'];
    $gioBatDau = $_POST['gioBatDau'];
    $gioKetThuc = $_POST['gioKetThuc'];
    $trangThai = $_POST['trangThai'];

    // Câu truy vấn cập nhật
    $sql = "UPDATE LICHDAT SET maBanBia='$maBanBia', maTaiKhoan='$maTaiKhoan', ngayDat='$ngayDat', gioBatDau='$gioBatDau', gioKetThuc='$gioKetThuc', trangThai='$trangThai' WHERE maLichDat='$maLichDat'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_lichdat.php?msg=Cập nhật lịch đặt thành công");
    } else {
        header("Location: manage_lichdat.php?msg=Cập nhật lịch đặt không thành công");
    }
}

$conn->close();
?>
