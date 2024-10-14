<?php
// Bao gồm file kết nối
include '../Connect/connect.php'; // Điều chỉnh đường dẫn nếu cần

// Lấy danh sách bàn
$sql = "SELECT maBan, tenBan, giaTheoGio FROM BanBia WHERE trangThai = 0";
$result = $conn->query($sql);

$banList = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $banList[] = $row;
    }
}

echo json_encode($banList);

$conn->close();
?>
