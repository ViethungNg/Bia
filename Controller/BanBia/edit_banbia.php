<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Xử lý cập nhật bàn
if (isset($_POST['update'])) {
    $maBanBia = $_POST['maBanBia'];
    $tenBanBia = $_POST['tenBanBia'];
    $trangThai = $_POST['trangThai'];

    $update_sql = "UPDATE banbia SET tenBanBia=?, trangThai=? WHERE maBanBia=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $tenBanBia, $trangThai, $maBanBia);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật bàn thành công!";
    } else {
        $_SESSION['message'] = "Lỗi cập nhật bàn: " . $stmt->error;
    }
    $stmt->close();
    header("Location: manage_banbia.php");
    exit();
}

// Lấy thông tin bàn để sửa
$banToEdit = null;
if (isset($_GET['edit'])) {
    $maBanBia = $_GET['edit'];
    $edit_sql = "SELECT * FROM banbia WHERE maBanBia=?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $maBanBia);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $banToEdit = $result_edit->fetch_assoc();
    $stmt->close();
} else {
    // Nếu không có mã bàn, chuyển hướng về trang quản lý
    header("Location: manage_banbia.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Bàn Bi-a</title>

    <!-- Liên kết CSS Bootstrap bằng CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sửa Bàn Bi-a</h2>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
                ?>
            </div>
        <?php endif; ?>

        <!-- Form sửa bàn -->
        <form action="" method="POST">
            <input type="hidden" name="maBanBia" value="<?php echo htmlspecialchars($banToEdit['maBanBia']); ?>">
            <div class="form-group">
                <label for="tenBanBia">Tên Bàn</label>
                <input type="text" name="tenBanBia" class="form-control" value="<?php echo htmlspecialchars($banToEdit['tenBanBia']); ?>" required>
            </div>
            <div class="form-group">
                <label for="trangThai">Trạng Thái</label>
                <input type="text" name="trangThai" class="form-control" value="<?php echo htmlspecialchars($banToEdit['trangThai']); ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Cập nhật Bàn</button>
            <a href="manage_banbia.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <!-- Liên kết JS Jquery và Popper bằng CDN -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    
    <!-- Liên kết JS Bootstrap bằng CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
