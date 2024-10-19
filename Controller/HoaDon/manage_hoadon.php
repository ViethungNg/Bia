<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Lấy tất cả hóa đơn từ cơ sở dữ liệu
$sql = "SELECT * FROM hoadon";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hóa Đơn</title>
    <!-- Liên kết CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../View/Home_Admin/admin.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Quản lý Hóa Đơn</h2>

        <!-- Form tìm kiếm hóa đơn theo ngày -->
        <form action="find_hoadon.php" method="GET" class="form-inline mb-3">
            <div class="form-group mr-2">
                <label for="ngayThanhToan" class="mr-2">Tìm kiếm theo ngày:</label>
                <input type="date" name="ngayThanhToan" id="ngayThanhToan" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>

        <!-- Hiển thị bảng hóa đơn -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Mã Hóa Đơn</th>
                    <th>Mã Lịch Đặt</th>
                    <th>Ngày Thanh Toán</th>
                    <th>Tổng Tiền</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kiểm tra và hiển thị dữ liệu
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['maHoaDon']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['maLichDat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ngayThanhToan']) . "</td>";
                        echo "<td>" . number_format($row['tongTien'], 0, ',', '.') . " VND</td>";
                        echo "<td>";
                        echo "<button class='btn btn-warning btn-sm' onclick=\"editHoaDon('" 
                             . $row['maHoaDon'] . "', '" 
                             . $row['maLichDat'] . "', '" 
                             . $row['ngayThanhToan'] . "', '" 
                             . $row['tongTien'] . "')\">Sửa</button> ";
                        echo "<a href='delete_hoadon.php?id=" . $row['maHoaDon'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc chắn muốn xóa hóa đơn này?\")'>Xóa</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Trường hợp không có hóa đơn nào
                    echo "<tr><td colspan='5' class='text-center'>Không có hóa đơn nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Popup Sửa Hóa Đơn -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa Hóa Đơn</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="edit_hoadon.php" method="POST">
                    <div class="modal-body">
                        <!-- Mã Hóa Đơn (ẩn) -->
                        <input type="hidden" name="maHoaDon" id="edit_maHoaDon">
                        
                        <!-- Mã Lịch Đặt (readonly) -->
                        <div class="form-group">
                            <label for="edit_maLichDat">Mã Lịch Đặt</label>
                            <input type="number" name="maLichDat" id="edit_maLichDat" class="form-control" readonly>
                        </div>

                        <!-- Ngày Thanh Toán -->
                        <div class="form-group">
                            <label for="edit_ngayThanhToan">Ngày Thanh Toán</label>
                            <input type="date" name="ngayThanhToan" id="edit_ngayThanhToan" class="form-control" required>
                        </div>

                        <!-- Tổng Tiền -->
                        <div class="form-group">
                            <label for="edit_tongTien">Tổng Tiền</label>
                            <input type="number" name="tongTien" id="edit_tongTien" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" name="update" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Liên kết JS Jquery và Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>

    <!-- Liên kết JS Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Mở popup và truyền dữ liệu vào các trường khi bấm nút "Sửa"
        function editHoaDon(maHoaDon, maLichDat, ngayThanhToan, tongTien) {
            $('#edit_maHoaDon').val(maHoaDon);
            $('#edit_maLichDat').val(maLichDat);
            $('#edit_ngayThanhToan').val(ngayThanhToan);
            $('#edit_tongTien').val(tongTien);
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
