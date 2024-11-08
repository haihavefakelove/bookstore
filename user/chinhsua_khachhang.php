<?php
    require '../connect.php';
    require '../checker/kiemtra_login.php';
$id = $_GET['id'];
$sql = "SELECT * FROM khachhang WHERE ma_khach_hang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $ho_va_ten = $_POST['ho_va_ten'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $dia_chi = $_POST['dia_chi'];
    $dia_chi_nhan_hang = $_POST['dia_chi_nhan_hang'];
    $dang_ky_nhan_ban_tin = $_POST['dang_ky_nhan_ban_tin'];
    $vai_tro = $_POST['vai_tro'];
    // Kiểm tra trùng tên đăng nhập (ngoại trừ tài khoản hiện tại)
    $check_username = "SELECT * FROM khachhang WHERE ten_dang_nhap = ? AND ma_khach_hang != ?";
    $stmt_check = $conn->prepare($check_username);
    $stmt_check->bind_param("si", $ten_dang_nhap, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        echo "<script>alert('Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.');</script>";
    } else {
        // Cập nhật thông tin khách hàng
        $sql_update = "UPDATE khachhang SET ten_dang_nhap = ?, ho_va_ten = ?, email = ?, so_dien_thoai = ?, dia_chi = ?, dia_chi_nhan_hang = ?, dang_ky_nhan_ban_tin = ?, vai_tro = ? WHERE ma_khach_hang = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssssi", $ten_dang_nhap, $ho_va_ten, $email, $so_dien_thoai, $dia_chi, $dia_chi_nhan_hang, $dang_ky_nhan_ban_tin, $vai_tro, $id);
        if ($stmt_update->execute()) {
            echo "<script>alert('Cập nhật khách hàng thành công.'); window.location='quanlikhachhang.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra. Vui lòng thử lại.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa khách hàng</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f2f2f7;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 320px;
            margin: auto;
            transition: box-shadow 0.3s ease;
        }
        form:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1c1c1e;
            text-align: center;
            margin: 20px 0;
        }
        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 10px;
            background-color: #f9f9f9;
            font-size: 14px;
            color: #1c1c1e;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        input[type="text"]:focus, input[type="email"]:focus, select:focus {
            border-color: #007aff;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.15);
        }
        button {
            width: 100%;
            background-color: #007aff;
            color: white;
            padding: 12px 0;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #005ecb;
            transform: translateY(-2px);
        }
        button:active {
            background-color: #003eaa;
            transform: translateY(0);
        }
        label {
            font-size: 14px;
            color: #3c3c43;
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <h1>Chỉnh sửa khách hàng</h1>

    <form method="POST" action="">
        <label for="ten_dang_nhap">Tên đăng nhập:</label>
        <input type="text" name="ten_dang_nhap" value="<?php echo htmlspecialchars($row['ten_dang_nhap']); ?>" required><br>

        <label for="ho_va_ten">Họ và tên:</label>
        <input type="text" name="ho_va_ten" value="<?php echo htmlspecialchars($row['ho_va_ten']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required><br>

        <label for="so_dien_thoai">Số điện thoại:</label>
        <input type="text" name="so_dien_thoai" value="<?php echo htmlspecialchars($row['so_dien_thoai']); ?>" required><br>

        <label for="dia_chi">Địa chỉ:</label>
        <input type="text" name="dia_chi" value="<?php echo htmlspecialchars($row['dia_chi']); ?>"><br>

        <label for="dia_chi_nhan_hang">Địa chỉ nhận hàng:</label>
        <input type="text" name="dia_chi_nhan_hang" value="<?php echo htmlspecialchars($row['dia_chi_nhan_hang']); ?>"><br>

        <label for="dang_ky_nhan_ban_tin">Đăng ký nhận bản tin:</label>
        <select name="dang_ky_nhan_ban_tin">
            <option value="1" <?php if ($row['dang_ky_nhan_ban_tin'] == 1) echo 'selected'; ?>>Có</option>
            <option value="0" <?php if ($row['dang_ky_nhan_ban_tin'] == 0) echo 'selected'; ?>>Không</option>
        </select><br>

        <label for="vai_tro">Vai trò:</label>
        <select name="vai_tro">
            <option value="khachhang" <?php if ($row['vai_tro'] == 'khachhang') echo 'selected'; ?>>Khách hàng</option>
            <option value="admin" <?php if ($row['vai_tro'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select><br>

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>
