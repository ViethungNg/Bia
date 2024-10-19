<?php
// Kết nối tới cơ sở dữ liệu
include '../Connect/connect.php';

// Khởi tạo biến để lưu kết quả thống kê
$totalRevenue = 0;
$totalBookings = 0;
$totalPlayingTables = 0; // Biến để lưu số bàn đang chơi

// Kiểm tra nếu có tìm kiếm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu từ form
    $searchDate = $_POST['searchDate'];

    // Truy vấn tìm kiếm theo ngày thanh toán
    if (!empty($searchDate)) {
        // Tính tổng doanh thu trong ngày
        $sqlRevenue = "SELECT SUM(tongTien) as totalRevenue FROM hoadon WHERE ngayThanhToan = '$searchDate'";
        $revenueResult = $conn->query($sqlRevenue);
        $revenueRow = $revenueResult->fetch_assoc();
        $totalRevenue = $revenueRow['totalRevenue'] ? $revenueRow['totalRevenue'] : 0;

        // Đếm số lượng đặt bàn trong ngày
        $sqlBookings = "SELECT COUNT(*) as totalBookings FROM lichdat WHERE ngayDat = '$searchDate' AND trangThai = 'Đã đặt'";
        $bookingsResult = $conn->query($sqlBookings);
        $bookingsRow = $bookingsResult->fetch_assoc();
        $totalBookings = $bookingsRow['totalBookings'];

        // Đếm số bàn đang chơi
        $sqlPlayingTables = "SELECT COUNT(*) as totalPlayingTables FROM banbia WHERE trangThai = 'Đang chơi'";
        $playingTablesResult = $conn->query($sqlPlayingTables);
        $playingTablesRow = $playingTablesResult->fetch_assoc();
        $totalPlayingTables = $playingTablesRow['totalPlayingTables'];
    }
} else {
    // Nếu không tìm kiếm, lấy tổng doanh thu và số lượng đặt bàn cho tất cả
    $sqlTotalRevenue = "SELECT SUM(tongTien) as totalRevenue FROM hoadon";
    $totalRevenueResult = $conn->query($sqlTotalRevenue);
    $totalRevenueRow = $totalRevenueResult->fetch_assoc();
    $totalRevenue = $totalRevenueRow['totalRevenue'] ? $totalRevenueRow['totalRevenue'] : 0;

    $sqlTotalBookings = "SELECT COUNT(*) as totalBookings FROM lichdat WHERE trangThai = 'Đã đặt'";
    $totalBookingsResult = $conn->query($sqlTotalBookings);
    $totalBookingsRow = $totalBookingsResult->fetch_assoc();
    $totalBookings = $totalBookingsRow['totalBookings'];

    // Đếm số bàn đang chơi
    $sqlPlayingTables = "SELECT COUNT(*) as totalPlayingTables FROM Lichdat WHERE trangThai = 'Đang chơi'";
    $playingTablesResult = $conn->query($sqlPlayingTables);
    $playingTablesRow = $playingTablesResult->fetch_assoc();
    $totalPlayingTables = $playingTablesRow['totalPlayingTables'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Doanh Thu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Thống Kê Doanh Thu</h2>

        <!-- Form tìm kiếm -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="searchDate">Chọn ngày thanh toán:</label>
                <input type="date" class="form-control" id="searchDate" name="searchDate">
            </div>
            <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
            <a href="thongke.php" class="btn btn-secondary">Hủy Lọc</a>
        </form>

        <!-- Hiển thị kết quả thống kê -->
        <h3 class="mt-4">Kết Quả Thống Kê</h3>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Tổng Doanh Thu</th>
                    <th>Tổng Số Lượng Đặt Bàn</th>
                    <th>Số Bàn Đang Chơi</th> <!-- Cột mới -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo number_format($totalRevenue, 0, ',', '.') . ' VNĐ'; ?></td>
                    <td><?php echo $totalBookings; ?></td>
                    <td><?php echo $totalPlayingTables; ?></td> <!-- Hiển thị số bàn đang chơi -->
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
