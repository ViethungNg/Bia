<?php
include '../../Connect/connect.php';

if (isset($_GET['id'])) {
    $maLichDat = $_GET['id'];

    // Cập nhật trạng thái từ "Chờ duyệt" sang "Đã hủy"
    $sql = "UPDATE lichdat SET trangThai = 'Đã hủy' WHERE maLichDat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maLichDat);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: manage_lichdat.php?msg=Duyệt lịch hủy thành công");
    } else {
        header("Location: manage_lichdat.php?msg=Không thể duyệt lịch hủy");
    }

    $stmt->close();
}

$conn->close();
?>
