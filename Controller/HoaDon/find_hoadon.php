<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';

// Lấy giá trị ngày từ form tìm kiếm
$ngayThanhToan = $_GET['ngayThanhToan'];

// Tìm kiếm hóa đơn theo ngày thanh toán
$sql = "SELECT * FROM hoadon WHERE ngayThanhToan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ngayThanhToan);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm hóa đơn</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Kết quả tìm kiếm hóa đơn cho ngày: <?php echo htmlspecialchars($ngayThanhToan); ?></h2>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã Hóa Đơn</th>
                        <th>Mã Lịch Đặt</th>
                        <th>Ngày Thanh Toán</th>
                        <th>Tổng Tiền</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['maHoaDon']; ?></td>
                            <td><?php echo $row['maLichDat']; ?></td>
                            <td><?php echo $row['ngayThanhToan']; ?></td>
                            <td><?php echo number_format($row['tongTien'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <a href="edit_hoadon.php?id=<?php echo $row['maHoaDon']; ?>" class="btn btn-warning">Sửa</a>
                                <a href="delete_hoadon.php?id=<?php echo $row['maHoaDon']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không tìm thấy hóa đơn nào cho ngày <?php echo htmlspecialchars($ngayThanhToan); ?></p>
        <?php endif; ?>

        <a href="manage_hoadon.php" class="btn btn-primary">Quay lại quản lý hóa đơn</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
