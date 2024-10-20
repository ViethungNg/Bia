<?php
include '../../Connect/connect.php';
session_start();

if (isset($_GET['maLichDat'])) {
    $maLichDat = $_GET['maLichDat'];
    
    // Lấy thông tin lịch đặt hiện tại
    $sql = "SELECT * FROM lichdat WHERE maLichDat = '$maLichDat'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Sửa Lịch Đặt</title>
</head>
<body>

<div class="container mt-5">
    <h2>Sửa Lịch Đặt</h2>

    <form action="edit_lichdat_user.php" method="POST">
        <input type="hidden" name="maLichDat" value="<?php echo $row['maLichDat']; ?>">
        <div class="form-group">
            <label for="ngayDat">Ngày Đặt:</label>
            <input type="date" class="form-control" name="ngayDat" value="<?php echo $row['ngayDat']; ?>" required>
        </div>
        <div class="form-group">
            <label for="gioBatDau">Giờ Bắt Đầu:</label>
            <input type="time" class="form-control" name="gioBatDau" value="<?php echo $row['gioBatDau']; ?>" required>
        </div>
        <div class="form-group">
            <label for="gioKetThuc">Giờ Kết Thúc:</label>
            <input type="time" class="form-control" name="gioKetThuc" value="<?php echo $row['gioKetThuc']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="manage_lichdat_user.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maLichDat = $_POST['maLichDat'];
    $ngayDat = $_POST['ngayDat'];
    $gioBatDau = $_POST['gioBatDau'];
    $gioKetThuc = $_POST['gioKetThuc'];

    $sql = "UPDATE lichdat SET ngayDat='$ngayDat', gioBatDau='$gioBatDau', gioKetThuc='$gioKetThuc' WHERE maLichDat='$maLichDat'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_lichdat_user.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
