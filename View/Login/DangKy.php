<?php
// Kết nối tới CSDL
include '../../Connect/connect.php'; // Đảm bảo đường dẫn tới file connect.php là chính xác
session_start(); // Bắt đầu session

$error_message = ""; // Biến để lưu thông báo lỗi
$success_message = ""; // Biến để lưu thông báo thành công

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = $_POST['tenDangNhap'];
    $matKhau = $_POST['matKhau'];
    $soDienThoai = $_POST['soDienThoai'];
    $email = $_POST['email'];
    $hoTen = $_POST['hoTen']; // Thêm trường họ tên

    // Kiểm tra nếu tên đăng nhập đã tồn tại
    $sql = "SELECT * FROM taikhoan WHERE tenDangNhap = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tenDangNhap);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Tên đăng nhập đã tồn tại."; // Thông báo lỗi
    } else {
        // Thêm tài khoản mới vào CSDL
        $insert_sql = "INSERT INTO taikhoan (tenDangNhap, matKhau, soDienThoai, email, hoTen) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssss", $tenDangNhap, $matKhau, $soDienThoai, $email, $hoTen); // Thêm hoTen vào câu lệnh
        
        if ($insert_stmt->execute()) {
            $success_message = "Đăng ký thành công!"; // Thông báo thành công
        } else {
            $error_message = "Lỗi khi đăng ký tài khoản."; // Thông báo lỗi
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Màu nền nhạt */
        }

        .register-container {
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
        <div class="register-container">
            <h2 class="text-center">Đăng Ký Tài Khoản</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="hoTen">Họ Tên:</label> <!-- Thêm trường họ tên -->
                    <input type="text" class="form-control" id="hoTen" name="hoTen" required>
                </div>
                <div class="form-group">
                    <label for="tenDangNhap">Tên Đăng Nhập:</label>
                    <input type="text" class="form-control" id="tenDangNhap" name="tenDangNhap" required>
                </div>
                <div class="form-group">
                    <label for="matKhau">Mật Khẩu:</label>
                    <input type="password" class="form-control" id="matKhau" name="matKhau" required>
                </div>
                <div class="form-group">
                    <label for="soDienThoai">Số Điện Thoại:</label>
                    <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
                <?php endif; ?>
            </form>
            <div class="text-center mt-3">
                <a href="login.php">Đã có tài khoản? Đăng nhập</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
