<?php
// Kết nối tới CSDL
include '../../Connect/connect.php'; // Đảm bảo đường dẫn tới file connect.php là chính xác
session_start(); // Bắt đầu session

$error_message = ""; // Biến để lưu thông báo lỗi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = $_POST['tenDangNhap'];
    $matKhau = $_POST['matKhau'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM taikhoan WHERE tenDangNhap = ? AND matKhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $tenDangNhap, $matKhau);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Người dùng tồn tại
        $row = $result->fetch_assoc();
        $loaiTaiKhoan = $row['loaiTaiKhoan'];

        // Lưu tên đăng nhập vào session
        $_SESSION['tenDangNhap'] = $tenDangNhap;

        // Chuyển hướng dựa trên loại tài khoản
        if ($loaiTaiKhoan == 'admin') {
            header("Location: ../Admin/Home_Admin.html");
        } else {
            header("Location: doimatkhau.php");
        }
        exit(); // Thoát để đảm bảo không có mã nào chạy sau khi chuyển hướng
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng."; // Cập nhật thông báo lỗi
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="login.css"> <!-- Thêm link đến file CSS nếu có -->
</head>
<body>
    <h2>Đăng Nhập</h2>
    <form method="POST" action="">
        <label for="tenDangNhap">Tên đăng nhập:</label><br>
        <input type="text" id="tenDangNhap" name="tenDangNhap" required><br>
        <label for="matKhau">Mật khẩu:</label><br>
        <input type="password" id="matKhau" name="matKhau" required><br><br>
        <input type="submit" value="Đăng Nhập">

        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Thêm liên kết Quên mật khẩu và Đăng ký tài khoản vào trong form -->
        <div class="links">
            <p>
                <a href="QuenMatKhau.php">Quên mật khẩu?</a>
            </p>
            <p>
                <a href="DangKy.php">Đăng ký tài khoản</a>
            </p>
        </div>
    </form>
</body>
</html>
