<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Kiểm tra xem có tham số maTaiKhoan trong URL không
if (isset($_GET['maTaiKhoan'])) {
    $maTaiKhoan = $_GET['maTaiKhoan'];

    // Lấy thông tin tài khoản để kiểm tra tenDangNhap
    $sqlCheck = "SELECT tenDangNhap FROM taikhoan WHERE maTaiKhoan = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $maTaiKhoan);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $row = $resultCheck->fetch_assoc();
        $tenDangNhap = $row['tenDangNhap'];

        // Kiểm tra xem tên đăng nhập có phải là 'admin' không
        if ($tenDangNhap === 'admin') {
            // Không cho phép xóa tài khoản admin
            header("Location: manage_taiKhoan.php?error=adminCannotBeDeleted");
            exit();
        }

        // Thực hiện câu lệnh SQL để xóa tài khoản
        $sql = "DELETE FROM taikhoan WHERE maTaiKhoan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $maTaiKhoan);

        if ($stmt->execute()) {
            // Chuyển hướng về trang quản lý tài khoản với thông báo thành công
            header("Location: manage_taiKhoan.php?success=1");
        } else {
            // Xử lý lỗi
            echo "Lỗi: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Nếu không tìm thấy tài khoản, chuyển hướng về trang quản lý tài khoản
        header("Location: manage_taiKhoan.php?error=accountNotFound");
    }

    $stmtCheck->close();
} else {
    // Nếu không có tham số, chuyển hướng về trang quản lý tài khoản
    header("Location: manage_taiKhoan.php?error=missingParameter");
}

$conn->close();
?>
