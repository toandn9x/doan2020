<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Chi tiết hóa đơn</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<style>
		@media print
		{    
		    .no-print, .no-print *
		    {
		        display: none !important;
		    }
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>FAST FOOD</h2>
				<table>
					<tr>
						<td><b>Địa chỉ: </b></td>
						<td>&nbsp;&nbsp;ĐHTL 175 Tây Sơn - Đống Đa - Hà Nội</td>
					<tr>
					<tr>
						<td><b>Điện thoại: </b></td>
						<td>&nbsp;&nbsp;0389262320</td>
					</tr>
					<tr>
						<td><b>Website: </b></td>
						<td>&nbsp;&nbsp;https://github.com/toandn62/DOAN2020</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h4>Thông tin đơn hàng</h4>
				<hr>
				<table>
					<tr>
						<td><b>Mã đơn hàng: </b></td>
						<td>&nbsp;&nbsp;#{{ $bill_info->id }}</td>
					<tr>
					<tr>
						<td><b>Ngày đặt: </b></td>
						<td>&nbsp;&nbsp;{{ $bill_info->created_at }}</td>
					</tr>
					<tr>
						<td><b>Hình thức thanh toán: </b></td>
						<td>&nbsp;&nbsp;{{ $bill_info->payment }}</td>
					</tr>
					<tr>
						<td><b>Hình thức vận chuyển: </b></td>
						<td>&nbsp;&nbsp;{{ $bill_info->type_transport }}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6">
				<h4>Thông tin mua hàng</h4>
				<hr>
				<table>
					<tr>
						<td><b>Tên KH: </b></td>
						<td>&nbsp;&nbsp;{{ $customer_info->name }}</td>
					<tr>
					<tr>
						<td><b>Email: </b></td>
						<td>&nbsp;&nbsp;{{ $customer_info->email }}</td>
					</tr>
					<tr>
						<td><b>SĐT: </b></td>
						<td>&nbsp;&nbsp;{{ $customer_info->phone }}</td>
					</tr>
					<tr>
						<td><b>Địa chỉ: </b></td>
						<td>&nbsp;&nbsp;{{ $customer_info->address }}</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4>Chi tiết đơn hàng</h4>
				<table class="table">
				  <thead>
				    <tr>
				      <th scope="col">#</th>
				      <th scope="col">Tên sản phẩm</th>
				      <th scope="col">Số lượng</th>
				      <th scope="col">Đơn giá</th>
				      <th scope="col">Thành tiền</th>
				    </tr>
				  </thead>
				  <tbody>
				  	@php
					  	$total = 0;
					  	$ship = 15000;
				  	@endphp
				  	@foreach($data as $key => $value)
				  	<tr>
				      <th scope="row">{{ $key + 1 }}</th>
				      <td>{{ $value[0] }}</td>
				      <td>{{ $value[1] }}</td>
				      <td>{{ number_format($value[2]) }}</td>
				      <td>{{ number_format($value[3]) }}</td>
				    </tr>
				    @php
				  		$total += $value[3];
				  	@endphp
				  	@endforeach
				  </tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 float-right" style="display: block; margin-left: auto;">
				<h5>Thông tin thanh toán</h5>
				<table>
					<tr>
						<td><b>Tổng giá: </b></td>
						<td>&nbsp;&nbsp;{{ number_format($total) }}</td>
					<tr>
					<tr>
						<td><b>Phí vận chuyển: </b></td>
						<td>&nbsp;&nbsp;{{ number_format($ship) }}</td>
					</tr>
					<tr>
						<td><b>Tổng tiền phải trả: </b></td>
						<td>&nbsp;&nbsp;<b>{{ number_format($total + $ship) }}</b></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="no-print">
    		<button class="btn btn-danger" onclick="window.print()" style="cursor: pointer;">Print</button>
    		<a href="{{ route('index') }}" class="btn btn-success" style="cursor: pointer;">Back</a>
		</div>
		
	</div>
</body>
</html>