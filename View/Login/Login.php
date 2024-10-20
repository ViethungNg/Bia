<?php
// Kết nối tới CSDL
include '../../Connect/connect.php'; // Đảm bảo đường dẫn tới file connect.php là chính xác
session_start(); // Bắt đầu session

$error_message = ""; // Biến để lưu thông báo lỗi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = $_POST['tenDangNhap'];
    $matKhau = $_POST['matKhau'];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM taikhoan WHERE tenDangNhap = ? AND matKhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $tenDangNhap, $matKhau);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy thông tin người dùng
        $user = $result->fetch_assoc();
        $_SESSION['tenDangNhap'] = $tenDangNhap; // Lưu tên đăng nhập vào session
        $_SESSION['loaiTaiKhoan'] = $user['loaiTaiKhoan']; // Lưu loại tài khoản vào session
        $_SESSION['maTaiKhoan'] = $user['maTaiKhoan']; // Lưu mã tài khoản vào session
    
        // Chuyển đến trang tương ứng dựa trên loại tài khoản
        if ($user['loaiTaiKhoan'] === 'admin') {
            header("Location: ../Admin/Home_Admin.html"); // Dẫn đến trang admin
        } else {
            header("Location: ../User/Home_User.html"); // Dẫn đến trang user
        }
        exit();
    }
     else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng."; // Thông báo lỗi
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
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
                    <label for="tenDangNhap">Tên Đăng Nhập:</label>
                    <input type="text" class="form-control" id="tenDangNhap" name="tenDangNhap" required>
                </div>
                <div class="form-group">
                    <label for="matKhau">Mật Khẩu:</label>
                    <input type="password" class="form-control" id="matKhau" name="matKhau" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>
            </form>
            <div class="text-center mt-3">
                <a href="quenmatkhau.php">Quên mật khẩu?</a>
            </div>
            <div class="text-center mt-3">
                <a href="dangky.php">Đăng ký tài khoản mới</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>