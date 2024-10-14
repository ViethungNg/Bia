<?php
// Bắt đầu phiên làm việc
session_start();

// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';

// Kiểm tra nếu người dùng muốn sửa tài khoản
if (isset($_GET['maTaiKhoan'])) {
    $maTaiKhoan = $_GET['maTaiKhoan'];

    // Lấy thông tin tài khoản từ cơ sở dữ liệu
    $sqlSelect = "SELECT * FROM taikhoan WHERE maTaiKhoan = '$maTaiKhoan'";
    $result = $conn->query($sqlSelect);
    $account = $result->fetch_assoc();
}

// Kiểm tra nếu người dùng submit form
if (isset($_POST['btnUpdate'])) {
    $maTaiKhoan = $_POST['maTaiKhoan'];
    $soDienThoai = $_POST['soDienThoai'];
    $email = $_POST['email'];
    $loaiTaiKhoan = $_POST['loaiTaiKhoan'];

    // Thực thi câu lệnh UPDATE chỉ cho phép cập nhật số điện thoại, email và loại tài khoản
    $sqlUpdate = "UPDATE taikhoan SET soDienThoai='$soDienThoai', email='$email', loaiTaiKhoan='$loaiTaiKhoan' WHERE maTaiKhoan='$maTaiKhoan'";

    if ($conn->query($sqlUpdate) === TRUE) {
        $_SESSION['message'] = "Cập nhật tài khoản thành công!"; // Thông báo thành công
    } else {
        $_SESSION['message'] = "Cập nhật tài khoản thất bại: " . $conn->error; // Thông báo thất bại
    }

    // Chuyển hướng về trang quản lý tài khoản
    header('Location: manage_taikhoan.php');
    exit(); // Dừng thực thi script
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tài Khoản</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sửa Tài Khoản</h2>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Form sửa tài khoản -->
        <form action="edit_taikhoan.php?maTaiKhoan=<?php echo $maTaiKhoan; ?>" method="post">
            <input type="hidden" name="maTaiKhoan" value="<?php echo $account['maTaiKhoan']; ?>">
            <div class="form-group">
                <label for="tenDangNhap">Tên Đăng Nhập</label>
                <input type="text" class="form-control" id="tenDangNhap" name="tenDangNhap" value="<?php echo $account['tenDangNhap']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="hoTen">Họ Tên</label>
                <input type="text" class="form-control" id="hoTen" name="hoTen" value="<?php echo $account['hoTen']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="soDienThoai">Số Điện Thoại</label>
                <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" value="<?php echo $account['soDienThoai']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $account['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="loaiTaiKhoan">Loại Tài Khoản</label>
                <select class="form-control" id="loaiTaiKhoan" name="loaiTaiKhoan">
                    <option value="User" <?php echo ($account['loaiTaiKhoan'] == 'User') ? 'selected' : ''; ?>>User</option>
                    <option value="Admin" <?php echo ($account['loaiTaiKhoan'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="btnUpdate">Cập nhật</button>
            <a href="manage_taikhoan.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
