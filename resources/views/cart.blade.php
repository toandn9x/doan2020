@extends('app')
@section('title', 'Giỏ hàng')
@section('content')
<style>
	td {
		color: black!important;
	}
	/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 9999999!important;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
<div id="loading">Loading&#8230;</div>
<div class="w3ls_w3l_banner_nav_right_grid w3ls_w3l_banner_nav_right_grid_sub">
	<h3>Giỏ hàng của bạn</h3>
	<hr>
	@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
	@if(count(Cart::content()) > 0)
	<table class="table" style="color: red">
	  <thead>
	    <tr>
	      <th scope="col">#</th>
	      <th scope="col">Ảnh</th>
	      <th scope="col">Tên</th>
	      <th scope="col">Đơn giá</th>
	      <th scope="col">Số lượng</th>
	      <th scope="col">Thành tiền</th>
	      <th scope="col">Thao tác</th>
	    </tr>
	  </thead>
	  <tbody>
	  	@php
	  		$key = 1;
	  		$total = 0;
	  	@endphp
	  	@foreach(Cart::content() as $cart)
	    <tr>
	      <th scope="row">{{ $key }}</th>
	      <td><img src="images/{{ $cart->options['img'] }}" width="200px" height="150px"></td>
	      <td>{{ $cart->name }}</td>
	      <td>{{ number_format($cart->price) }}</td>
	      <td><input type="number" value="{{ $cart->qty }}" style="width: 50px" min="1" data-rowid = "{{ $cart->rowId }}" class="qty"></td>
	      <td>{{ number_format($cart->price *  $cart->qty) }}</td>
	      <td><a href="{{ route('delete_cart', $cart->rowId) }}">Xóa</a></td>
	    </tr>
	    @php
	  		$key ++;
	  		$total += $cart->price *  $cart->qty;
	  	@endphp
	    @endforeach
	    <tr>
	      <th scope="row"></th>
	      <td></td>
	      <td></td>
	      <td></td>
	      <td></td>
	      <td>Tổng: {!! '<b style=color:red>'.number_format($total).'</b>' !!} đ</td>
	      <td></td>
	    </tr>
	  </tbody>
	</table>
	<div style="clear: both;"></div>
	<hr>
	<div>
		<a href="{{ route('delete_all') }}" class="btn btn-info">Xóa</a>&emsp;
		@if(Auth::check())
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" id="">Đặt hàng</button>
		@else
			<a href="{{ route('g_login') }}" class="btn btn-success" id="order">Đặt hàng</a>
		@endif
	</div>
	@else
	<h2><a href="#" style="display: block; text-align: center;">Giỏ hàng của bạn đang trống !</a></h2>
	@endif

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel" style="font-size: 25px">Nhập thông tin khách hàng</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form method="post" action="order">
		        	@csrf
		        	<div class="col-md-6">
		        		<div class="form-group">
				            <label for="recipient-name" class="col-form-label">Họ tên(<span style="color:red">*</span>)</label>
				            <input type="text" class="form-control" id="recipient-name" required name="name">
				        </div>
				        <div class="form-group">
				            <label for="recipient-name" class="col-form-label">Email(<span style="color:red">*</span>)</label>
				            <input type="email" class="form-control" id="recipient-email" required name="email">
				        </div>
				        <div class="form-group">
				            <label for="recipient-name" class="col-form-label">Địa chỉ (vui lòng ghi rõ địa chỉ để đơn hàng đến đúng nơi)(<span style="color:red">*</span>)</label>
				            <textarea class="form-control" rows="3" id="address" required name="address"></textarea>
				        </div>
				        <div class="form-group">
				            <label for="recipient-name" class="col-form-label">Số điện thoại(<span style="color:red">*</span>)</label>
				            <input type="number" class="form-control" id="phone" required name="phone">
				        </div>
				        <div class="form-group">
				            <label for="recipient-name" class="col-form-label">Ghi chú</label>
				            <textarea class="form-control" rows="5" id="note" name="note"></textarea>
				        </div>
		        	</div>
		        	<div class="col-md-6">
		        		<div class="form-group">
				            <label for="recipient-name" class="col-form-label">Hình thức vận chuyển</label>
				            <hr>
				            <input class="form-check-input" type="radio" name="type_transport" id="exampleRadios2" value="giao hàng nhanh" checked>
							  <label class="form-check-label" for="exampleRadios2">
							    Giao hàng nhanh - 15,000
							  </label><br>
							  <p>-	Áp dụng cho khu vực bán kính cách 10km</p><br>
							  {{-- <input class="form-check-input" type="radio" name="type_transport" id="exampleRadios3" value="miễn phí"> --}}
							  {{-- <label class="form-check-label" for="exampleRadios3">
							    Giao hàng miễn phí
							  </label> --}}
				        </div>
				        <div class="form-group">
				            <label for="recipient-name" class="col-form-label">Phương thức thanh toán</label>
				            <hr>
				            <input class="form-check-input" type="radio" name="payment_type" id="exampleRadios4" value="tiền mặt" checked>
							  <label class="form-check-label" for="exampleRadios4">
							    Thanh toán bằng tiền mặt
							  </label><br><br>
							  <input class="form-check-input" type="radio" name="payment_type" id="exampleRadios5" value="Online">
							  <label class="form-check-label" for="exampleRadios5">
							    Thanh toán bằng thẻ
							  </label>
				        </div>
				        <div class="form-group" style="display: none" id="bank">
                        <label for="bank_code">Ngân hàng</label>
                        <select name="bank_code" id="bank_code" class="form-control">
                            <option value="">Không chọn</option>
                            <option value="NCB"> Ngan hang NCB</option>
                            <option value="AGRIBANK"> Ngan hang Agribank</option>
                            <option value="SCB"> Ngan hang SCB</option>
                            <option value="SACOMBANK">Ngan hang SacomBank</option>
                            <option value="EXIMBANK"> Ngan hang EximBank</option>
                            <option value="MSBANK"> Ngan hang MSBANK</option>
                            <option value="NAMABANK"> Ngan hang NamABank</option>
                            <option value="VNMART"> Vi dien tu VnMart</option>
                            <option value="VIETINBANK">Ngan hang Vietinbank</option>
                            <option value="VIETCOMBANK"> Ngan hang VCB</option>
                            <option value="HDBANK">Ngan hang HDBank</option>
                            <option value="DONGABANK"> Ngan hang Dong A</option>
                            <option value="TPBANK"> Ngân hàng TPBank</option>
                            <option value="OJB"> Ngân hàng OceanBank</option>
                            <option value="BIDV"> Ngân hàng BIDV</option>
                            <option value="TECHCOMBANK"> Ngân hàng Techcombank</option>
                            <option value="VPBANK"> Ngan hang VPBank</option>
                            <option value="MBBANK"> Ngan hang MBBank</option>
                            <option value="ACB"> Ngan hang ACB</option>
                            <option value="OCB"> Ngan hang OCB</option>
                            <option value="IVB"> Ngan hang IVB</option>
                            <option value="VISA"> Thanh toan qua VISA/MASTER</option>
                        </select>
                    </div>
		        	</div>
		        	<div style="clear: both;"></div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary" id="order_now">Đặt hàng</button>
		      </div>
		    </form>
		    </div>
		  </div>
	</div>
</div>
@stop
@section('script')
<script>
	$('.qty').on('change', function(){
		let qty = $(this).val();
		let rowId = $(this).data('rowid');
		if(qty == '' || qty < 1) qty = 1;
		$.ajax({
	        url : './gio-hang/update/'+rowId,
	        type : 'post',
	        data : {
	        	   'rowId' : rowId,
	              'qty' : qty,
	              },
	        success : function (data){
	        	location.reload();
	        }
	    });
		
	})
</script>
<script>
	$('#exampleRadios5').on('change', function() {
		$('#bank').css('display', 'block');
	})
	$('#exampleRadios4').on('change', function() {
		$('#bank').css('display', 'none');
	})
</script>
<script>
	//add class bg loading
	$('#order_now').on('click', function() {
		if($('#recipient-name').val() != '' && $('#recipient-email').val() != '' && $('#address').val() != '' && $('#phone').val() != '') {
			$('#loading').addClass("loading");
		}
		// alert($('#recipient-name').val());
		// alert($('#recipient-email').val());
		// alert($('#address').val());
		// alert($('#phone').val());
	})
	
</script>
@stop