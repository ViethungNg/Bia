<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';

// Khởi tạo biến tìm kiếm
$search_date = isset($_POST['search_date']) ? $_POST['search_date'] : '';
$search_start_time = isset($_POST['search_start_time']) ? $_POST['search_start_time'] : '';
$search_end_time = isset($_POST['search_end_time']) ? $_POST['search_end_time'] : '';

// Lấy tất cả lịch đặt từ cơ sở dữ liệu với điều kiện tìm kiếm
$sql = "SELECT * FROM lichdat WHERE 1=1";
if (!empty($search_date)) {
    $sql .= " AND DATE(ngayDat) = '" . $conn->real_escape_string($search_date) . "'";
}
if (!empty($search_start_time) && !empty($search_end_time)) {
    $sql .= " AND gioBatDau >= '" . $conn->real_escape_string($search_start_time) . "' AND gioKetThuc <= '" . $conn->real_escape_string($search_end_time) . "'";
}
$result = $conn->query($sql);

// Hiển thị kết quả
if ($result->num_rows > 0) {
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead class='thead-dark'>
            <tr>
                <th>Mã Lịch Đặt</th>
                <th>Mã Bàn Bi-a</th>
                <th>Mã Tài Khoản</th>
                <th>Ngày Đặt</th>
                <th>Giờ Bắt Đầu</th>
                <th>Giờ Kết Thúc</th>
                <th>Trạng Thái</th>
                <th>Thao Tác</th>
            </tr>
          </thead>
          <tbody>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['maLichDat']) . "</td>";
        echo "<td>" . htmlspecialchars($row['maBanBia']) . "</td>";
        echo "<td>" . htmlspecialchars($row['maTaiKhoan']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ngayDat']) . "</td>";
        echo "<td>" . htmlspecialchars($row['gioBatDau']) . "</td>";
        echo "<td>" . htmlspecialchars($row['gioKetThuc']) . "</td>";
        echo "<td>" . htmlspecialchars($row['trangThai']) . "</td>";
        echo "<td>";
        echo "<button class='btn btn-warning btn-sm' onclick=\"editLichDat('" 
             . $row['maLichDat'] . "', '" 
             . $row['maBanBia'] . "', '" 
             . $row['maTaiKhoan'] . "', '" 
             . $row['ngayDat'] . "', '" 
             . $row['gioBatDau'] . "', '" 
             . $row['gioKetThuc'] . "', '" 
             . $row['trangThai'] . "')\">Sửa</button> ";
        echo "<a href='delete_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc chắn muốn xóa lịch đặt này?\")'>Xóa</a> ";
        if ($row['trangThai'] == 'Chờ duyệt') {
            echo "<a href='duyet_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-success btn-sm'>Duyệt</a> ";
        }
        if ($row['trangThai'] == 'Đang chơi') {
            echo "<a href='thanhtoan_lichdat.php?id=" . $row['maLichDat'] . "' class='btn btn-primary btn-sm'>Thanh Toán</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
} else {
    // Trường hợp không có lịch đặt nào
    echo "<p class='text-center'>Không có lịch đặt nào cho điều kiện tìm kiếm này.</p>";
}

// Đóng kết nối
$conn->close();
?>
