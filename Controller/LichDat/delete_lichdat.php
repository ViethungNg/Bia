<?php
include '../../Connect/connect.php';

if (isset($_GET['id'])) {
    $maLichDat = $_GET['id'];

    // Xóa lịch đặt từ cơ sở dữ liệu
    $sql = "DELETE FROM lichdat WHERE maLichDat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maLichDat);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: manage_lichdat.php?msg=Xóa lịch đặt thành công");
    } else {
        header("Location: manage_lichdat.php?msg=Không thể xóa lịch đặt");
    }

    $stmt->close();
}

$conn->close();
?>
