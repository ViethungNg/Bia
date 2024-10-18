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
    <title>Quản lý Bàn Bi-a</title>

    <!-- Liên kết CSS Bootstrap bằng CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../View/Home_Admin/admin.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Quản lý Bàn Bi-a</h2>

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
                ?>
            </div>
        <?php endif; ?>

        <!-- Nút Thêm Bàn -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">Thêm Bàn</button>

        <!-- Hiển thị bảng BANBIA -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Mã Bàn</th>
                    <th>Tên Bàn</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
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
                        echo "<td>
                                <button class='btn btn-warning' data-toggle='modal' data-target='#editModal' data-id='" . htmlspecialchars($row['maBanBia']) . "' data-name='" . htmlspecialchars($row['tenBanBia']) . "' data-status='" . htmlspecialchars($row['trangThai']) . "'>Sửa</button>
                                <a href='delete_banbia.php?id=" . htmlspecialchars($row['maBanBia']) . "' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa bàn này không?\");'>Xóa</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Thêm Bàn -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm Bàn Bi-a</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="create_banbia.php" method="POST">
                        <div class="form-group">
                            <label for="tenBanBia">Tên Bàn</label>
                            <input type="text" name="tenBanBia" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="trangThai">Trạng Thái</label>
                            <select name="trangThai" class="form-control">
                                <option value="Trống" selected>Trống</option>
                                <option value="Đang chơi">Đang chơi</option>
                                <option value="Đã đặt">Đã đặt</option>
                            </select>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Thêm Bàn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Bàn -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Sửa Bàn Bi-a</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="edit_banbia.php" method="POST">
                        <input type="hidden" name="maBanBia" id="maBanBia">
                        <div class="form-group">
                            <label for="tenBanBia">Tên Bàn</label>
                            <input type="text" name="tenBanBia" class="form-control" id="tenBanBia" required>
                        </div>
                        <div class="form-group">
                            <label for="trangThai">Trạng Thái</label>
                            <select name="trangThai" class="form-control" id="trangThai">
                                <option value="Trống">Trống</option>
                                <option value="Đang chơi">Đang chơi</option>
                                <option value="Đã đặt">Đã đặt</option>
                            </select>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Cập nhật Bàn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liên kết JS Jquery và Popper bằng CDN -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    
    <!-- Liên kết JS Bootstrap bằng CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <script>
        // Khi mở modal sửa, đặt giá trị cho các trường nhập
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Nút sửa
            var id = button.data('id'); // Lấy ID từ nút
            var name = button.data('name'); // Lấy tên từ nút
            var status = button.data('status'); // Lấy trạng thái từ nút

            // Đặt giá trị cho các trường nhập trong modal
            var modal = $(this);
            modal.find('#maBanBia').val(id);
            modal.find('#tenBanBia').val(name);
            modal.find('#trangThai').val(status);
        });
    </script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
