<?php
include '../../Connect/connect.php';

if (isset($_GET['id'])) {
    $maLichDat = $_GET['id'];

    // Cập nhật trạng thái từ "Đang chơi" sang "Hoàn thành"
    $sql = "UPDATE lichdat SET trangThai = 'Hoàn thành' WHERE maLichDat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maLichDat);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: manage_lichdat.php?msg=Thanh toán thành công");
    } else {
        header("Location: manage_lichdat.php?msg=Không thể thanh toán lịch đặt");
    }

    $stmt->close();
}

$conn->close();
?>
