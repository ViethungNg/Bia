<?php
// Kết nối tới CSDL
include '../../Connect/connect.php'; // Đảm bảo đường dẫn tới file connect.php là chính xác
session_start(); // Bắt đầu session

$error_message = ""; // Biến để lưu thông báo lỗi
$success_message = ""; // Biến để lưu thông báo thành công

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenDangNhap = $_POST['tenDangNhap'];
    $soDienThoai = $_POST['soDienThoai'];
    $email = $_POST['email'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM taikhoan WHERE tenDangNhap = ? AND soDienThoai = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $tenDangNhap, $soDienThoai, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Người dùng tồn tại, cập nhật mật khẩu
        $row = $result->fetch_assoc();
        $maTaiKhoan = $row['maTaiKhoan'];
        
        // Cập nhật mật khẩu bằng số điện thoại
        $update_sql = "UPDATE taikhoan SET matKhau = ? WHERE maTaiKhoan = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $soDienThoai, $maTaiKhoan);
        if ($update_stmt->execute()) {
            $success_message = "Cập nhật mật khẩu thành công!"; // Thông báo thành công
        } else {
            $error_message = "Lỗi khi cập nhật mật khẩu."; // Thông báo lỗi
        }
    } else {
        $error_message = "Thông tin không đúng."; // Cập nhật thông báo lỗi
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Màu nền nhạt */
        }

        .reset-container {
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
        <div class="reset-container">
            <h2 class="text-center">Quên Mật Khẩu</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="tenDangNhap">Tên Đăng Nhập:</label>
                    <input type="text" class="form-control" id="tenDangNhap" name="tenDangNhap" required>
                </div>
                <div class="form-group">
                    <label for="soDienThoai">Số Điện Thoại:</label>
                    <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Cập Nhật Mật Khẩu</button>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
                <?php endif; ?>
            </form>
            <div class="text-center mt-3">
                <a href="login.php">Trở về trang đăng nhập</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
