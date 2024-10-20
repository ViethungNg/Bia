<?php
// Kết nối tới CSDL
include '../../Connect/connect.php'; // Đảm bảo đường dẫn tới file connect.php là chính xác
session_start(); // Bắt đầu session

$error_message = ""; // Biến để lưu thông báo lỗi
$success_message = ""; // Biến để lưu thông báo thành công

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['tenDangNhap'])) {
    header("Location: login.php"); // Chuyển đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Lấy tên đăng nhập từ session
$tenDangNhap = $_SESSION['tenDangNhap'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matKhauCu = $_POST['matKhauCu'];
    $matKhauMoi = $_POST['matKhauMoi'];
    $matKhauXacNhan = $_POST['matKhauXacNhan'];

    // Kiểm tra mật khẩu cũ
    $sql = "SELECT * FROM taikhoan WHERE tenDangNhap = ? AND matKhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $tenDangNhap, $matKhauCu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $error_message = "Mật khẩu cũ không đúng."; // Thông báo lỗi
    } elseif ($matKhauMoi !== $matKhauXacNhan) {
        $error_message = "Mật khẩu mới và mật khẩu xác nhận không khớp."; // Thông báo lỗi
    } else {
        // Cập nhật mật khẩu mới
        $update_sql = "UPDATE taikhoan SET matKhau = ? WHERE tenDangNhap = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $matKhauMoi, $tenDangNhap);

        if ($update_stmt->execute()) {
            $success_message = "Đổi mật khẩu thành công!"; // Thông báo thành công
        } else {
            $error_message = "Lỗi khi đổi mật khẩu."; // Thông báo lỗi
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Màu nền nhạt */
        }

        .change-password-container {
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
        <div class="change-password-container">
            <h2 class="text-center">Đổi Mật Khẩu</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="matKhauCu">Mật Khẩu Cũ:</label>
                    <input type="password" class="form-control" id="matKhauCu" name="matKhauCu" required>
                </div>
                <div class="form-group">
                    <label for="matKhauMoi">Mật Khẩu Mới:</label>
                    <input type="password" class="form-control" id="matKhauMoi" name="matKhauMoi" required>
                </div>
                <div class="form-group">
                    <label for="matKhauXacNhan">Xác Nhận Mật Khẩu Mới:</label>
                    <input type="password" class="form-control" id="matKhauXacNhan" name="matKhauXacNhan" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Đổi Mật Khẩu</button>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
                <?php endif; ?>
            </form>
            
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
