-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 19, 2024 lúc 11:56 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bia_booking`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banbia`
--

CREATE TABLE `banbia` (
  `maBanBia` int(11) NOT NULL,
  `tenBanBia` varchar(100) NOT NULL,
  `trangThai` enum('trống','đang chơi','đã đặt') NOT NULL DEFAULT 'trống'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `banbia`
--

INSERT INTO `banbia` (`maBanBia`, `tenBanBia`, `trangThai`) VALUES
(1, 'Bàn 1', 'trống'),
(2, 'Bàn 2', 'trống'),
(4, '1', 'trống'),
(5, '4', 'đang chơi'),
(7, '2', 'trống'),
(8, '12', 'trống'),
(9, '2', 'trống'),
(10, 'ngoài', 'trống'),
(11, '2 hùng', 'trống'),
(12, '2', 'đang chơi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

CREATE TABLE `hoadon` (
  `maHoaDon` int(11) NOT NULL,
  `maLichDat` int(11) DEFAULT NULL,
  `ngayThanhToan` date NOT NULL,
  `tongTien` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadon`
--

INSERT INTO `hoadon` (`maHoaDon`, `maLichDat`, `ngayThanhToan`, `tongTien`) VALUES
(1, 1, '2024-10-20', 22020.00),
(2, 2, '2024-10-19', 1000000.00),
(4, 3, '2024-10-19', 60000.00),
(5, 3, '2024-10-19', 75600.00),
(6, 1, '2024-10-19', 60000.00),
(7, 3, '2024-10-19', 75600.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichdat`
--

CREATE TABLE `lichdat` (
  `maLichDat` int(11) NOT NULL,
  `maBanBia` int(11) DEFAULT NULL,
  `maTaiKhoan` int(11) DEFAULT NULL,
  `ngayDat` date NOT NULL,
  `gioBatDau` time NOT NULL,
  `gioKetThuc` time NOT NULL,
  `trangThai` enum('Chờ duyệt','Đã đặt','Đang chơi','Hoàn thành','Đã hủy') NOT NULL DEFAULT 'Chờ duyệt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lichdat`
--

INSERT INTO `lichdat` (`maLichDat`, `maBanBia`, `maTaiKhoan`, `ngayDat`, `gioBatDau`, `gioKetThuc`, `trangThai`) VALUES
(1, 1, 2, '2024-10-15', '10:00:00', '12:00:00', 'Đã đặt'),
(2, 2, 1, '2024-10-16', '14:00:00', '16:00:00', 'Đã đặt'),
(3, 2, 2, '2024-10-16', '14:00:00', '16:31:00', 'Hoàn thành'),
(4, 2, 2, '2024-10-27', '15:34:00', '18:36:00', 'Đã đặt'),
(5, 11, 1, '2024-10-20', '17:41:00', '20:42:00', 'Đã đặt'),
(7, 1, 2, '2024-10-19', '15:47:00', '19:47:00', 'Đã đặt'),
(8, 2, 1, '2024-10-27', '15:58:00', '19:58:00', 'Đã đặt'),
(9, 1, 1, '2024-10-11', '15:59:00', '18:59:00', 'Đã đặt'),
(10, 1, 1, '2024-10-19', '18:04:00', '19:04:00', 'Đã đặt');

--
-- Bẫy `lichdat`
--
DELIMITER $$
CREATE TRIGGER `update_HoaDon_after_lichDat_completed` AFTER UPDATE ON `lichdat` FOR EACH ROW BEGIN
    -- Khai báo biến
    DECLARE soGioChoi DECIMAL(10, 2);
    DECLARE tongTien DECIMAL(10, 2);

    -- Kiểm tra điều kiện chỉ khi trangThai chuyển từ "Đang chơi" sang "Hoàn thành"
    IF NEW.trangThai = 'Hoàn thành' AND OLD.trangThai = 'Đang chơi' THEN
        -- Tính thời gian chơi (giờ) giữa giờ bắt đầu và giờ kết thúc
        SET soGioChoi = TIMESTAMPDIFF(MINUTE, NEW.gioBatDau, NEW.gioKetThuc) / 60;
        SET tongTien = soGioChoi * 30000;

        -- Thực hiện câu lệnh INSERT vào bảng HOADON
        INSERT INTO HOADON (maLichDat, ngayThanhToan, tongTien)
        VALUES (NEW.maLichDat, NOW(), tongTien);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_trangThai_to_DangChoi` BEFORE UPDATE ON `lichdat` FOR EACH ROW BEGIN
    IF NEW.trangThai = 'Đã đặt' AND DATE(NOW()) = NEW.ngayDat AND TIME(NOW()) >= NEW.gioBatDau THEN
        SET NEW.trangThai = 'Đang chơi';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_trangThai_to_HoanThanh` BEFORE UPDATE ON `lichdat` FOR EACH ROW BEGIN
    IF NEW.trangThai = 'Đang chơi' AND DATE(NOW()) = NEW.ngayDat AND TIME(NOW()) >= NEW.gioKetThuc THEN
        SET NEW.trangThai = 'Hoàn thành';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `maTaiKhoan` int(11) NOT NULL,
  `tenDangNhap` varchar(100) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `loaiTaiKhoan` enum('admin','user') NOT NULL DEFAULT 'user',
  `hoTen` varchar(100) DEFAULT NULL,
  `soDienThoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`maTaiKhoan`, `tenDangNhap`, `matKhau`, `loaiTaiKhoan`, `hoTen`, `soDienThoai`, `email`) VALUES
(1, 'admin', 'admin', 'admin', 'Nguyễn Việt Hùng', '0981748805', 'boysongao2003@gmail.com'),
(2, 'user1', 'user123', 'user', NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banbia`
--
ALTER TABLE `banbia`
  ADD PRIMARY KEY (`maBanBia`);

--
-- Chỉ mục cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`maHoaDon`),
  ADD KEY `maLichDat` (`maLichDat`);

--
-- Chỉ mục cho bảng `lichdat`
--
ALTER TABLE `lichdat`
  ADD PRIMARY KEY (`maLichDat`),
  ADD KEY `maBanBia` (`maBanBia`),
  ADD KEY `maTaiKhoan` (`maTaiKhoan`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`maTaiKhoan`),
  ADD UNIQUE KEY `tenDangNhap` (`tenDangNhap`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banbia`
--
ALTER TABLE `banbia`
  MODIFY `maBanBia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `maHoaDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `lichdat`
--
ALTER TABLE `lichdat`
  MODIFY `maLichDat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `maTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`maLichDat`) REFERENCES `lichdat` (`maLichDat`);

--
-- Các ràng buộc cho bảng `lichdat`
--
ALTER TABLE `lichdat`
  ADD CONSTRAINT `lichdat_ibfk_1` FOREIGN KEY (`maBanBia`) REFERENCES `banbia` (`maBanBia`),
  ADD CONSTRAINT `lichdat_ibfk_2` FOREIGN KEY (`maTaiKhoan`) REFERENCES `taikhoan` (`maTaiKhoan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
