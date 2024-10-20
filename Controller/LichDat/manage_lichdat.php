<?php
//s
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Khởi tạo biến tìm kiếm
$search_date = isset($_POST['search_date']) ? $_POST['search_date'] : '';
$search_start_time = isset($_POST['search_start_time']) ? $_POST['search_start_time'] : '';
$search_end_time = isset($_POST['search_end_time']) ? $_POST['search_end_time'] : '';

// Lấy tất cả lịch đặt từ cơ sở dữ liệu với điều kiện tìm kiếm
$sql = "SELECT * FROM lichdat WHERE 1=1";
if (!empty($search_date)) {
    $sql .= " AND DATE(ngayDat) = '" . $conn->real_escape_string($search_date) . "'";
}
if (!empty($search_start_time) && !empty($search_end_time)) {
    $sql .= " AND gioBatDau >= '" . $conn->real_escape_string($search_start_time) . "' AND gioKetThuc <= '" . $conn->real_escape_string($search_end_time) . "'";
}
$result = $conn->query($sql);

// Lấy danh sách Bàn Bi-a
$sql_ban_bia = "SELECT maBanBia, tenBanBia FROM banbia";
$result_ban_bia = $conn->query($sql_ban_bia);

// Lấy danh sách Tài Khoản
$sql_tai_khoan = "SELECT maTaiKhoan, tenDangNhap, hoTen FROM taikhoan";
$result_tai_khoan = $conn->query($sql_tai_khoan);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Lịch Đặt</title>
    <!-- Liên kết CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../View/Home_Admin/admin.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Quản lý Lịch Đặt</h2>

        <!-- Nút bấm Thêm Lịch Đặt -->
        <button class="btn btn-primary mb-3" onclick="showAddModal()">Thêm Lịch Đặt</button>
        <button class="btn btn-secondary mb-3" onclick="showSearchModal()">Tìm Kiếm</button>
        <button class="btn btn-danger mb-3" onclick="resetFilters()">Bỏ Lọc</button>

        <!-- Hiển thị bảng lịch đặt -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Mã Lịch Đặt</th>
                    <th>Mã Bàn Bi-a</th>
                    <th>Mã Tài Khoản</th>
                    <th>Ngày Đặt</th>
                    <th>Giờ Bắt Đầu</th>
                    <th>Giờ Kết Thúc</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kiểm tra và hiển thị dữ liệu
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['maLichDat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['maBanBia']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['maTaiKhoan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ngayDat']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gioBatDau']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gioKetThuc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['trangThai']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-warning btn-sm' onclick=\"editLichDat('" 
                             . $row['maLichDat'] . "', '" 
                             . $row['maBanBia'] . "', '" 
                             . $row['maTaiKhoan'] . "', '" 
                             . $row['ngayDat'] . "', '" 
                             . $row['gioBatDau'] . "', '" 
                             . $row['gioKetThuc'] . "', '" 
                             . $row['trangThai'] . "')\">Sửa</button> ";
                        echo "<a href='delete_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc chắn muốn xóa lịch đặt này?\")'>Xóa</a> ";
                        if ($row['trangThai'] == 'Chờ duyệt') {
                            echo "<a href='duyet_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-success btn-sm'>Duyệt</a> ";
                            echo "<a href='huy_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-danger btn-sm'>Hủy</a>"; // Nút Hủy
                        }
                        if ($row['trangThai'] == 'Đang chơi') {
                            echo "<a href='thanhtoan_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-primary btn-sm'>Thanh Toán</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Trường hợp không có lịch đặt nào
                    echo "<tr><td colspan='8' class='text-center'>Không có lịch đặt nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

      <!-- Modal Popup Thêm Lịch Đặt -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm Lịch Đặt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="create_lichdat.php" method="POST">
                    <div class="modal-body">
                        <!-- Mã Bàn Bi-a -->
                        <div class="form-group">
                            <label for="add_maBanBia">Mã Bàn Bi-a</label>
                            <select name="maBanBia" id="add_maBanBia" class="form-control" required>
                                <option value="">Chọn Bàn Bi-a</option>
                                <?php while ($row_ban_bia = $result_ban_bia->fetch_assoc()): ?>
                                    <option value="<?php echo $row_ban_bia['maBanBia']; ?>"><?php echo htmlspecialchars($row_ban_bia['maBanBia']) . ' - ' . htmlspecialchars($row_ban_bia['tenBanBia']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Mã Tài Khoản -->
                        <div class="form-group">
                            <label for="add_maTaiKhoan">Mã Tài Khoản</label>
                            <select name="maTaiKhoan" id="add_maTaiKhoan" class="form-control" required>
                                <option value="">Chọn Tài Khoản</option>
                                <?php while ($row_tai_khoan = $result_tai_khoan->fetch_assoc()): ?>
                                    <option value="<?php echo $row_tai_khoan['maTaiKhoan']; ?>"><?php echo htmlspecialchars($row_tai_khoan['maTaiKhoan']) . ' - ' . htmlspecialchars($row_tai_khoan['tenDangNhap']) . ' - ' . htmlspecialchars($row_tai_khoan['hoTen']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Ngày Đặt -->
                        <div class="form-group">
                            <label for="add_ngayDat">Ngày Đặt</label>
                            <input type="date" name="ngayDat" id="add_ngayDat" class="form-control" required>
                        </div>

                        <!-- Giờ Bắt Đầu -->
                        <div class="form-group">
                            <label for="add_gioBatDau">Giờ Bắt Đầu</label>
                            <input type="time" name="gioBatDau" id="add_gioBatDau" class="form-control" required>
                        </div>

                        <!-- Giờ Kết Thúc -->
                        <div class="form-group">
                            <label for="add_gioKetThuc">Giờ Kết Thúc</label>
                            <input type="time" name="gioKetThuc" id="add_gioKetThuc" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <!-- Modal Sửa Lịch Đặt -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Sửa Lịch Đặt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="edit_lichdat.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="maLichDat" id="edit_maLichDat">
                        <div class="form-group">
                        <label for="edit_maBanBia">Mã Bàn Bia</label>
                        <input type="text" name="maBanBia" id="edit_maBanBia" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_maTaiKhoan">Mã Tài Khoản</label>
                        <input type="text" name="maTaiKhoan" id="edit_maTaiKhoan" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit_ngayDat">Ngày Đặt</label>
                        <input type="date" name="ngayDat" id="edit_ngayDat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_gioBatDau">Giờ Bắt Đầu</label>
                        <input type="time" name="gioBatDau" id="edit_gioBatDau" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_gioKetThuc">Giờ Kết Thúc</label>
                        <input type="time" name="gioKetThuc" id="edit_gioKetThuc" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_trangThai">Trạng Thái</label>
                        <select name="trangThai" id="edit_trangThai" class="form-control" required>
                            <option value="Chờ duyệt">Chờ duyệt</option>
                            <option value="Đã đặt">Đã đặt</option>
                            <option value="Đang chơi">Đang chơi</option>
                            <option value="Hoàn thành">Hoàn thành</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Hàm chỉnh sửa lịch đặt
    function editLichDat(maLichDat, maBanBia, maTaiKhoan, ngayDat, gioBatDau, gioKetThuc, trangThai) {
        // Điền thông tin vào modal
        document.getElementById('edit_maLichDat').value = maLichDat;
        document.getElementById('edit_maBanBia').value = maBanBia;
        document.getElementById('edit_maTaiKhoan').value = maTaiKhoan;
        document.getElementById('edit_ngayDat').value = ngayDat;
        document.getElementById('edit_gioBatDau').value = gioBatDau;
        document.getElementById('edit_gioKetThuc').value = gioKetThuc;
        document.getElementById('edit_trangThai').value = trangThai;

        // Hiển thị modal
        $('#editModal').modal('show');
    }
</script>


    <!-- Modal Popup Tìm Kiếm -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Tìm Kiếm Lịch Đặt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="manage_lichdat.php">
                    <div class="modal-body">
                        <!-- Ngày Đặt -->
                        <div class="form-group">
                            <label for="search_date">Ngày Đặt</label>
                            <input type="date" name="search_date" id="search_date" class="form-control" value="<?php echo $search_date; ?>">
                        </div>

                        <!-- Giờ Bắt Đầu -->
                        <div class="form-group">
                            <label for="search_start_time">Giờ Bắt Đầu</label>
                            <input type="time" name="search_start_time" id="search_start_time" class="form-control" value="<?php echo $search_start_time; ?>">
                        </div>

                        <!-- Giờ Kết Thúc -->
                        <div class="form-group">
                            <label for="search_end_time">Giờ Kết Thúc</label>
                            <input type="time" name="search_end_time" id="search_end_time" class="form-control" value="<?php echo $search_end_time; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Thư viện JS Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Hàm hiển thị popup Thêm Lịch Đặt
        function showAddModal() {
            $('#addModal').modal('show');
        }

        // Hàm hiển thị popup Tìm Kiếm
        function showSearchModal() {
            $('#searchModal').modal('show');
        }

        // Hàm đặt lại bộ lọc
        function resetFilters() {
            document.getElementById('search_date').value = '';
            document.getElementById('search_start_time').value = '';
            document.getElementById('search_end_time').value = '';
            // Gọi lại trang để làm mới dữ liệu
            window.location.href = 'manage_lichdat.php';
        }

        // Hàm chỉnh sửa lịch đặt
        function editLichDat(maLichDat, maBanBia, maTaiKhoan, ngayDat, gioBatDau, gioKetThuc, trangThai) {
            // Đặt giá trị vào các trường trong modal sửa
            document.getElementById("edit_maLichDat").value = maLichDat;
            document.getElementById("edit_maBanBia").value = maBanBia;
            document.getElementById("edit_maTaiKhoan").value = maTaiKhoan;
            document.getElementById("edit_ngayDat").value = ngayDat;
            document.getElementById("edit_gioBatDau").value = gioBatDau;
            document.getElementById("edit_gioKetThuc").value = gioKetThuc;
            document.getElementById("edit_trangThai").value = trangThai;

        // Hiển thị modal sửa
        $('#editModal').modal('show');
        }
    </script>
</body>
</html>
