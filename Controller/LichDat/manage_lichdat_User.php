<?php
include '../../Connect/connect.php';
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['maTaiKhoan'])) {
    header("Location: login.php");
    exit;
}

// Lấy mã tài khoản của người dùng đang đăng nhập
$maTaiKhoan = $_SESSION['maTaiKhoan'];

// Khởi tạo biến tìm kiếm
$searchDate = '';

// Kiểm tra nếu có ngày tìm kiếm được gửi từ form
if (isset($_POST['searchDate'])) {
    $searchDate = $_POST['searchDate'];
    $sql = "SELECT * FROM lichdat WHERE maTaiKhoan = '$maTaiKhoan' AND ngayDat = '$searchDate'";
} else {
    // Lấy danh sách lịch đặt của tài khoản đang đăng nhập
    $sql = "SELECT * FROM lichdat WHERE maTaiKhoan = '$maTaiKhoan'";
}

$result = $conn->query($sql);

// Lấy danh sách bàn bia (bao gồm mã và tên)
$sqlBanhia = "SELECT maBanBia, tenBanBia FROM banbia"; // Thay 'banbia' thành tên bảng nếu khác
$resultBanBia = $conn->query($sqlBanhia);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Quản Lý Lịch Đặt</title>
    <style>
        #formThemLich {
            display: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Quản Lý Lịch Đặt</h2>

    <!-- Nút thêm lịch đặt -->
    <button id="btnThemLich" class="btn btn-primary mb-4" onclick="toggleForm()">Thêm Lịch Đặt</button>

    <!-- Form tìm kiếm theo ngày -->
    <form method="POST" class="mb-4">
        <div class="form-row align-items-end">
            <div class="col-auto">
                <label for="searchDate">Tìm Kiếm Theo Ngày:</label>
                <input type="date" class="form-control" name="searchDate" value="<?php echo $searchDate; ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-info">Tìm Kiếm</button>
                <a href="manage_lichdat_user.php" class="btn btn-secondary">Bỏ Lọc</a> <!-- Nút Bỏ Lọc -->
            </div>
        </div>
    </form>

    <!-- Form thêm lịch đặt -->
    <form id="formThemLich" action="create_lichdat_user.php" method="POST">
        <h4>Nhập Thông Tin Lịch Đặt</h4>
        <div class="form-group">
            <label for="maBanBia">Mã Bàn Bia:</label>
            <select class="form-control" name="maBanBia" required>
                <option value="">Chọn Mã Bàn Bia</option>
                <?php while ($rowBanBia = $resultBanBia->fetch_assoc()): ?>
                    <option value="<?php echo $rowBanBia['maBanBia']; ?>"><?php echo $rowBanBia['maBanBia'] . " - " . $rowBanBia['tenBanBia']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="ngayDat">Ngày Đặt:</label>
            <input type="date" class="form-control" name="ngayDat" required>
        </div>
        <div class="form-group">
            <label for="gioBatDau">Giờ Bắt Đầu:</label>
            <input type="time" class="form-control" name="gioBatDau" required>
        </div>
        <div class="form-group">
            <label for="gioKetThuc">Giờ Kết Thúc:</label>
            <input type="time" class="form-control" name="gioKetThuc" required>
        </div>
        <input type="hidden" name="maTaiKhoan" value="<?php echo $maTaiKhoan; ?>">
        <input type="hidden" name="trangThai" value="Chờ duyệt"> <!-- Trạng thái mặc định -->
        <button type="submit" class="btn btn-success">Thêm Lịch Đặt</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm()">Hủy</button>
    </form>

    <!-- Bảng danh sách lịch đặt -->
    <h4>Danh Sách Lịch Đặt Của Bạn</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Lịch Đặt</th>
                <th>Mã Bàn Bia</th>
                <th>Tên Bàn Bia</th>
                <th>Ngày Đặt</th>
                <th>Giờ Bắt Đầu</th>
                <th>Giờ Kết Thúc</th>
                <th>Trạng Thái</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['maLichDat']; ?></td>
                    <td><?php echo $row['maBanBia']; ?></td>
                    <td><?php 
                        // Lấy tên bàn bia từ bảng banbia dựa trên maBanBia
                        $maBanBia = $row['maBanBia'];
                        $tenBanBiaSql = "SELECT tenBanBia FROM banbia WHERE maBanBia = '$maBanBia'";
                        $tenBanBiaResult = $conn->query($tenBanBiaSql);
                        $tenBanBiaRow = $tenBanBiaResult->fetch_assoc();
                        echo $tenBanBiaRow['tenBanBia']; // Hiển thị tên bàn bia
                    ?></td>
                    <td><?php echo $row['ngayDat']; ?></td>
                    <td><?php echo $row['gioBatDau']; ?></td>
                    <td><?php echo $row['gioKetThuc']; ?></td>
                    <td><?php echo $row['trangThai']; ?></td>
                    <td>
                        <?php if ($row['trangThai'] == "Chờ duyệt"): ?>
                            <a href="edit_lichdat_user.php?maLichDat=<?php echo $row['maLichDat']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="delete_lichdat_user.php?maLichDat=<?php echo $row['maLichDat']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function toggleForm() {
        const form = document.getElementById('formThemLich');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
</body>
</html>

<?php
$conn->close();
?>
