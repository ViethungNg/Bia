<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Kiểm tra nếu có dữ liệu POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $tenDangNhap = $_POST['tenDangNhap'];
    $matKhau = $_POST['matKhau'];
    $loaiTaiKhoan = 'User'; // Loại tài khoản mặc định là User
    $hoTen = $_POST['hoTen'];
    $soDienThoai = $_POST['soDienThoai'];
    $email = $_POST['email'];

    // Kiểm tra tên đăng nhập đã tồn tại hay chưa
    $sqlCheck = "SELECT * FROM taikhoan WHERE tenDangNhap = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("s", $tenDangNhap);
    $stmt->execute();
    $resultCheck = $stmt->get_result();

    if ($resultCheck->num_rows > 0) {
        // Tên đăng nhập đã tồn tại, yêu cầu người dùng sửa thông tin
        echo "<script>alert('Tên đăng nhập đã tồn tại. Vui lòng sửa lại thông tin.'); window.history.back();</script>";
    } else {
        // Nếu tên đăng nhập chưa tồn tại, tiến hành thêm tài khoản
        // Chuẩn bị câu lệnh thêm tài khoản
        $sqlInsert = "INSERT INTO taikhoan (tenDangNhap, matKhau, loaiTaiKhoan, hoTen, soDienThoai, email) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("ssssss", $tenDangNhap, $matKhau, $loaiTaiKhoan, $hoTen, $soDienThoai, $email);

        if ($stmtInsert->execute()) {
            // Thêm thành công
            echo "<script>alert('Thêm tài khoản thành công!'); window.location.href = 'Login.php';</script>";
        } else {
            // Có lỗi xảy ra
            echo "<script>alert('Có lỗi xảy ra. Vui lòng thử lại.'); window.history.back();</script>";
        }
    }

    // Đóng kết nối
    $stmt->close();
    $stmtInsert->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Đăng Ký Tài Khoản</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="tenDangNhap">Tên Đăng Nhập:</label>
                <input type="text" class="form-control" id="tenDangNhap" name="tenDangNhap" required>
            </div>
            <div class="form-group">
                <label for="matKhau">Mật Khẩu:</label>
                <input type="password" class="form-control" id="matKhau" name="matKhau" required>
            </div>
            <div class="form-group">
                <label for="hoTen">Họ Tên:</label>
                <input type="text" class="form-control" id="hoTen" name="hoTen" required>
            </div>
            <div class="form-group">
                <label for="soDienThoai">Số Điện Thoại:</label>
                <input type="text" class="form-control" id="soDienThoai" name="soDienThoai" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng Ký</button>
            <a href="../Login/Login.php" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>
</body>
</html>
