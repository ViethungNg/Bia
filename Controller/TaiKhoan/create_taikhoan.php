<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Kiểm tra nếu có dữ liệu POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $tenDangNhap = $_POST['tenDangNhap'];
    $loaiTaiKhoan = $_POST['loaiTaiKhoan'];
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
        // Mật khẩu mặc định là số điện thoại
        $matKhau = $soDienThoai;

        // Chuẩn bị câu lệnh thêm tài khoản
        $sqlInsert = "INSERT INTO taikhoan (tenDangNhap, matKhau, loaiTaiKhoan, hoTen, soDienThoai, email) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("ssssss", $tenDangNhap, $matKhau, $loaiTaiKhoan, $hoTen, $soDienThoai, $email);

        if ($stmtInsert->execute()) {
            // Thêm thành công
            echo "<script>alert('Thêm tài khoản thành công!'); window.location.href = 'index.php';</script>";
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
