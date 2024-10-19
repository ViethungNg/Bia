<?php
// Kết nối tới cơ sở dữ liệu
include '../../Connect/connect.php';
session_start();

// Lấy tất cả tài khoản từ cơ sở dữ liệu
$sql = "SELECT * FROM taikhoan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Quản Lý Tài Khoản</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addAccountModal">Thêm Tài Khoản</button>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Đăng Nhập</th>
                <th>Loại Tài Khoản</th>
                <th>Họ Tên</th>
                <th>Số Điện Thoại</th>
                <th>Email</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $stt = 1; ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><?php echo htmlspecialchars($row['tenDangNhap']); ?></td>
                        <td><?php echo htmlspecialchars($row['loaiTaiKhoan']); ?></td>
                        <td><?php echo htmlspecialchars($row['hoTen']); ?></td>
                        <td><?php echo htmlspecialchars($row['soDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editAccountModal" data-id="<?php echo $row['maTaiKhoan']; ?>" data-ten="<?php echo htmlspecialchars($row['tenDangNhap']); ?>" data-loai="<?php echo htmlspecialchars($row['loaiTaiKhoan']); ?>" data-ho="<?php echo htmlspecialchars($row['hoTen']); ?>" data-so="<?php echo htmlspecialchars($row['soDienThoai']); ?>" data-email="<?php echo htmlspecialchars($row['email']); ?>">Sửa</button>
                            <a href="delete_taikhoan.php?maTaiKhoan=<?php echo $row['maTaiKhoan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Không có tài khoản nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal thêm tài khoản -->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountLabel">Thêm Tài Khoản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="create_taikhoan.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tenDangNhap">Tên Đăng Nhập</label>
                        <input type="text" name="tenDangNhap" id="tenDangNhap" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="loaiTaiKhoan">Loại Tài Khoản</label>
                        <select name="loaiTaiKhoan" id="loaiTaiKhoan" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hoTen">Họ Tên</label>
                        <input type="text" name="hoTen" id="hoTen" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="soDienThoai">Số Điện Thoại</label>
                        <input type="text" name="soDienThoai" id="soDienThoai" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal sửa tài khoản -->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountLabel">Sửa Tài Khoản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="edit_taikhoan.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="maTaiKhoan" id="editMaTaiKhoan">
                    <div class="form-group">
                        <label for="editTenDangNhap">Tên Đăng Nhập</label>
                        <input type="text" name="tenDangNhap" id="editTenDangNhap" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editLoaiTaiKhoan">Loại Tài Khoản</label>
                        <select name="loaiTaiKhoan" id="editLoaiTaiKhoan" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editHoTen">Họ Tên</label>
                        <input type="text" name="hoTen" id="editHoTen" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editSoDienThoai">Số Điện Thoại</label>
                        <input type="text" name="soDienThoai" id="editSoDienThoai" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#editAccountModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // nút kích hoạt modal
        var id = button.data('id');
        var ten = button.data('ten');
        var loai = button.data('loai');
        var ho = button.data('ho');
        var so = button.data('so');
        var email = button.data('email');

        var modal = $(this);
        modal.find('#editMaTaiKhoan').val(id);
        modal.find('#editTenDangNhap').val(ten);
        modal.find('#editLoaiTaiKhoan').val(loai);
        modal.find('#editHoTen').val(ho);
        modal.find('#editSoDienThoai').val(so);
        modal.find('#editEmail').val(email);
    });
</script>
</body>
</html>
