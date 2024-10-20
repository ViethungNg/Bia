<?php
// Kết nối tới cơ sở dữ liệu
include '../Connect/connect.php';

// Khởi tạo mảng để lưu dữ liệu
$dates = [];
$totalRevenueData = [];
$totalBookingsData = [];
$totalPlayingTablesData = [];

// Lấy ngày hôm nay
$today = date('Y-m-d');

// Lặp qua 7 ngày (bao gồm cả hôm nay)
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $date;

    // Tính tổng doanh thu
    $sqlRevenue = "SELECT SUM(tongTien) as totalRevenue FROM hoadon WHERE ngayThanhToan = '$date'";
    $revenueResult = $conn->query($sqlRevenue);
    $revenueRow = $revenueResult->fetch_assoc();
    $totalRevenueData[] = $revenueRow['totalRevenue'] ? $revenueRow['totalRevenue'] : 0;

    // Đếm số lượng đặt bàn
    $sqlBookings = "SELECT COUNT(*) as totalBookings FROM lichdat WHERE ngayDat = '$date' AND trangThai = 'Đã đặt'";
    $bookingsResult = $conn->query($sqlBookings);
    $bookingsRow = $bookingsResult->fetch_assoc();
    $totalBookingsData[] = $bookingsRow['totalBookings'];

    // Đếm số bàn chơi cho ngày cụ thể (nhóm điều kiện đúng cách)
    $sqlPlayingTables = "SELECT COUNT(*) as totalPlayingTables FROM lichdat WHERE (trangThai = 'Đang chơi' OR trangThai = 'Hoàn thành' OR trangThai = 'Đã đặt') AND ngayDat = '$date'";
    $playingTablesResult = $conn->query($sqlPlayingTables);
    $playingTablesRow = $playingTablesResult->fetch_assoc();
    $totalPlayingTablesData[] = $playingTablesRow['totalPlayingTables'];
}

// Xử lý ngày tìm kiếm
$searchDate = isset($_POST['searchDate']) ? $_POST['searchDate'] : $today;

// Lấy dữ liệu cho ngày tìm kiếm
$sqlSearchRevenue = "SELECT SUM(tongTien) as totalRevenue FROM hoadon WHERE ngayThanhToan = '$searchDate'";
$searchRevenueResult = $conn->query($sqlSearchRevenue);
$searchRevenueRow = $searchRevenueResult->fetch_assoc();
$totalRevenueSearch = $searchRevenueRow['totalRevenue'] ? $searchRevenueRow['totalRevenue'] : 0;

$sqlSearchBookings = "SELECT COUNT(*) as totalBookings FROM lichdat WHERE ngayDat = '$searchDate' AND trangThai = 'Đã đặt'";
$searchBookingsResult = $conn->query($sqlSearchBookings);
$searchBookingsRow = $searchBookingsResult->fetch_assoc();
$totalBookingsSearch = $searchBookingsRow['totalBookings'];

// Đếm số bàn chơi cho ngày tìm kiếm (nhóm điều kiện đúng cách)
$sqlSearchPlayingTables = "SELECT COUNT(*) as totalPlayingTables FROM lichdat WHERE (trangThai = 'Đang chơi' OR trangThai = 'Hoàn thành' OR trangThai = 'Đã đặt') AND ngayDat = '$searchDate'";
$searchPlayingTablesResult = $conn->query($sqlSearchPlayingTables);
$searchPlayingTablesRow = $searchPlayingTablesResult->fetch_assoc();
$totalPlayingTablesSearch = $searchPlayingTablesRow['totalPlayingTables'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Doanh Thu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Thống Kê Doanh Thu</h2>

        <!-- Form tìm kiếm -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="searchDate">Chọn ngày thanh toán:</label>
                <input type="date" class="form-control" id="searchDate" name="searchDate" value="<?php echo $searchDate; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
            <a href="thongke.php" class="btn btn-secondary">Hủy Lọc</a>
        </form>

        <!-- Hiển thị kết quả thống kê -->
        <h3 class="mt-4">Kết Quả Thống Kê cho Ngày <?php echo $searchDate; ?></h3>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Tổng Doanh Thu</th>
                    <th>Tổng Số Lượng Đặt Bàn</th>
                    <th>Số Bàn chơi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $totalRevenueSearch; ?></td>
                    <td><?php echo $totalBookingsSearch; ?></td>
                    <td><?php echo $totalPlayingTablesSearch; ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Biểu Đồ Doanh Thu -->
        <h3 class="mt-4">Biểu Đồ Doanh Thu (7 Ngày Gần Đây)</h3>
        <canvas id="revenueChart" width="400" height="200"></canvas>

        <!-- Biểu Đồ Số Lượng Đặt Bàn -->
        <h3 class="mt-4">Biểu Đồ Số Lượng Đặt Bàn (7 Ngày Gần Đây)</h3>
        <canvas id="bookingChart" width="400" height="200"></canvas>

        <!-- Biểu Đồ Số Bàn Đang Chơi -->
        <h3 class="mt-4">Biểu Đồ Số Bàn chơi (7 Ngày Gần Đây)</h3>
        <canvas id="playingTablesChart" width="400" height="200"></canvas>
    </div>

    <script>
        // Dữ liệu cho các biểu đồ
        var dates = <?php echo json_encode($dates); ?>;
        var totalRevenueData = <?php echo json_encode($totalRevenueData); ?>;
        var totalBookingsData = <?php echo json_encode($totalBookingsData); ?>;
        var totalPlayingTablesData = <?php echo json_encode($totalPlayingTablesData); ?>;

        // Biểu đồ doanh thu
        var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Tổng Doanh Thu',
                    data: totalRevenueData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ số lượng đặt bàn
        var ctxBooking = document.getElementById('bookingChart').getContext('2d');
        var bookingChart = new Chart(ctxBooking, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Tổng Số Lượng Đặt Bàn',
                    data: totalBookingsData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ số bàn chơi
        var ctxPlayingTables = document.getElementById('playingTablesChart').getContext('2d');
        var playingTablesChart = new Chart(ctxPlayingTables, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Số Bàn Chơi',
                    data: totalPlayingTablesData,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
