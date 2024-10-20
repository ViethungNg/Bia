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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> <!-- Thêm Bootstrap -->
    <style>
        /* CSS tích hợp */
        body {
            background-color: #f8f9fa; /* Màu nền nhạt */
        }

        .login-container {
            max-width: 400px; /* Chiều rộng tối đa của form */
            margin: auto; /* Canh giữa form */
            padding: 20px; /* Padding cho form */
            border: 1px solid #ccc; /* Viền cho form */
            border-radius: 8px; /* Bo góc cho form */
            background-color: #fff; /* Màu nền trắng cho form */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Đổ bóng cho form */
        }

        h2 {
            margin-bottom: 20px; /* Khoảng cách phía dưới tiêu đề */
        }

        .alert {
            margin-top: 20px; /* Khoảng cách trên thông báo lỗi */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="login-container">
            <h2 class="text-center">Đăng Nhập</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="tenDangNhap">Tên đăng nhập:</label>
                    <input type="text" id="tenDangNhap" name="tenDangNhap" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="matKhau">Mật khẩu:</label>
                    <input type="password" id="matKhau" name="matKhau" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if ($error_message): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Thêm liên kết Quên mật khẩu và Đăng ký tài khoản vào trong form -->
                <div class="links text-center mt-3">
                    <p>
                        <a href="QuenMatKhau.php">Quên mật khẩu?</a>
                    </p>
                    <p>
                        <a href="DangKy.php">Đăng ký tài khoản</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
