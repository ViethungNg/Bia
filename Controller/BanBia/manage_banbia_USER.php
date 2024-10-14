<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';

// Khởi tạo biến để lưu kết quả tìm kiếm
$sql = "SELECT * FROM banbia";
$result = $conn->query($sql);

// Khởi động phiên làm việc
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Bàn Bi-a - Người Dùng</title>
    
    <!-- Liên kết CSS Bootstrap bằng CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <!-- Liên kết tới file CSS tùy chỉnh (nếu có) -->
    <link rel="stylesheet" href="../../View/Home_Admin/admin.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Quản lý Bàn Bi-a - Người Dùng</h2>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
                ?>
            </div>
        <?php endif; ?>

        <!-- Hiển thị bảng BANBIA -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Mã Bàn</th>
                    <th>Tên Bàn</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kiểm tra và hiển thị dữ liệu
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['maBanBia']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tenBanBia']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['trangThai']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Liên kết JS Jquery và Popper bằng CDN -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" 
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    
    <!-- Liên kết JS Bootstrap bằng CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>    