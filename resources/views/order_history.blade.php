@extends('app')
@section('title', 'Lịch sử đặt hàng')
@section('content')
<br><br><br><br><br>
<div class="container">
	<div class="row" style="margin-left: 160px">
		<h3>Đơn hàng của: <b>{{ Auth::user()->name }}</b></h3>
	</div>
	<div class="row" style="margin-left: 160px">
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">STT</th>
		      <th scope="col">Mã đơn hàng</th>
		      <th scope="col">Tên Khách hàng</th>
		      <th scope="col">Địa chỉ</th>
		      <th scope="col">SĐT</th>
		      <th scope="col">Tổng tiền</th>
		      <th scope="col">Hình thức thanh toán</th>
		      <th scope="col">Hình thức vận chuyển</th>
		      <th scope="col">Trạng thái đơn hàng</th>
		      <th scope="col">Ngày đặt</th>
		      <th scope="col">Xem chi tiết</th>
		    </tr>
		  </thead>
		  <tbody>
		  	@foreach($bills as $key => $bill)
			    <tr>
			      	<th scope="row">{{ $key+1 }}</th>
			      	<td><b>#</b>{{ $bill->id }}</td>
			      	<td>{{ \App\Models\Customer::find($bill->id_customer)->name }}</td>
                    <td>{{ \App\Models\Customer::find($bill->id_customer)->address }}</td>
                    <td>{{ \App\Models\Customer::find($bill->id_customer)->phone }}</td>
        			<td>{{ number_format($bill->total_price) }}</td>
                    <td>{{ $bill->payment }}</td>
                    <td>{{ $bill->type_transport }}</td>
                    @if($bill->note == 'đã giao hàng')
                    	<td><b>{{ $bill->note }} (Ngày giao: <span style="color:red">{{ $bill->updated_at }})</span></b></td>
                    @else
                    	<td><b>{{ $bill->note }}</b></td>
             		@endif                   
                    <td>{{ $bill->created_at }}</td>
                    <td><a href="{{ route('order_history_detail', $bill->id) }}">Xem chi tiết</a></td>
			    </tr>
			@endforeach
		  </tbody>
		</table>
	</div>
	<div class="row">
		<div style="display: flex; justify-content: center;">
		<ul class="pagination pagination-lg">
			{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page=1">First</a></li>
            {{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            @if($bills->currentPage() > 1)
                <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->currentPage() - 1 }}">Previous</a></li>
            @endif
                <li class="page-item active"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->currentPage() }}">{{ $bills->currentPage() }}</a></li>
            @if($bills->currentPage() < $bills->lastPage())
                <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->currentPage() + 1 }}">{{ $bills->currentPage() + 1 }}</a></li>
                @if($bills->currentPage() < $bills->lastPage() - 2)
                <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->currentPage() + 2 }}">{{ $bills->currentPage() + 2 }}</a></li>
                @endif
                @if($bills->currentPage() < $bills->lastPage() - 3)
                <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->currentPage() + 3 }}">{{ $bills->currentPage() + 3 }}</a></li>
                @endif
            @endif
            {{-- nếu page hiện tại < page cuối thì hiện nút next --}}
            @if($bills->currentPage() < $bills->lastPage())
                <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->currentPage() + 1 }}">Next</a>
            @endif
            <li class="page-item"><a class="page-link" href="lich-su-mua-hang?page={{ $bills->lastPage() }}">Last</a></li>
		</ul>
	</div>
	</div>
</div>
@stop
@section('script')
@stop