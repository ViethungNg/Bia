<?php
include '../../Connect/connect.php';

if (isset($_GET['id'])) {
    $maLichDat = $_GET['id'];

    // Cập nhật trạng thái từ "Chờ duyệt" sang "Đã đặt"
    $sql = "UPDATE lichdat SET trangThai = 'Đã đặt' WHERE maLichDat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maLichDat);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: manage_lichdat.php?msg=Duyệt lịch đặt thành công");
    } else {
        header("Location: manage_lichdat.php?msg=Không thể duyệt lịch đặt");
    }

    $stmt->close();
}

$conn->close();
?>
