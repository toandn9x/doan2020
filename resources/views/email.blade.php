<!DOCTYPE html>
<html>
<head>
 <title>New Order</title>
</head>
<body>
 
 <h1>Đơn Hàng Mới</h1>
 <p>Bạn có một đơn hàng mới từ khách hàng: <b>{{ $name }}<b> - SĐT: <b>{{ $phone }}</b></p>
 <p>Giao đến: {{ $address }}</p>
 <p>Đơn hàng có tổng giá là: <b style="color:red">{{ $price }}</b></p>
 <p>Click vào link sau để xem chi tiết</p>
 <p><a href="http://localhost/DOAN2020/public/admin/ql-don-hang">Xem chi tiết</a></p>
</body>
</html> 