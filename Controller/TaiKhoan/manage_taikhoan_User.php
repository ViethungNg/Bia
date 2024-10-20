<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['maTaiKhoan'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin tài khoản của người dùng đang đăng nhập
$maTaiKhoan = $_SESSION['maTaiKhoan'];
$sql = "SELECT * FROM taikhoan WHERE maTaiKhoan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $maTaiKhoan);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo "Không tìm thấy tài khoản.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản - Người Dùng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Quản Lý Tài Khoản</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Đăng Nhập</th>
                <th>Họ Tên</th>
                <th>Số Điện Thoại</th>
                <th>Email</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($userData['tenDangNhap']); ?></td>
                <td><?php echo htmlspecialchars($userData['hoTen']); ?></td>
                <td><?php echo htmlspecialchars($userData['soDienThoai']); ?></td>
                <td><?php echo htmlspecialchars($userData['email']); ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editAccountModal">Sửa</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal sửa tài khoản -->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountLabel">Sửa Thông Tin Tài Khoản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="edit_taikhoan_User.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="maTaiKhoan" value="<?php echo $maTaiKhoan; ?>">
                    <div class="form-group">
                        <label for="hoTen">Họ Tên</label>
                        <input type="text" name="hoTen" id="hoTen" class="form-control" value="<?php echo htmlspecialchars($userData['hoTen']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="soDienThoai">Số Điện Thoại</label>
                        <input type="text" name="soDienThoai" id="soDienThoai" class="form-control" value="<?php echo htmlspecialchars($userData['soDienThoai']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($userData['email']); ?>">
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
