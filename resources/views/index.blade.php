@extends('app')
@section('title', 'Trang chủ')
@section('content')
<style>
	img {
  display: block !important;
  max-width:320px !important;
  max-height:150px !important;
  width: auto !important;
  height: auto !important;
	}
</style>
    {{-- HOT product list --}}
	<div class="fresh-vegetables" id="hot">
		<h3>Hot Products</h3>
		<div class="container">
			<div class="agile_top_brands_grids">
				@foreach ($products_hot->skip(0)->take(4) as $hot)
				<div class="col-md-3 top_brand_left">
					<div class="hover14 column">
						<div class="agile_top_brand_left_grid">
							<div class="tag"><img src="lib/images/hot.png" alt=" " class="img-responsive" style="margin: -5px"></div>
							<div class="agile_top_brand_left_grid1">
								<figure>
									<div class="snipcart-item block">
										<div class="snipcart-thumb">
											<a href="{{ route('product_detail', $hot->id) }}"><img title=" " alt=" " src="images/{{ $hot->image }}" width="320px" height="150px"></a>		
											<p>{{ $hot->name }}</p>
											@if(isset($hot->promotional_price) && $hot->promotional_price != 0)
											<h4>{{ number_format($hot->promotional_price) }} đ<span>{{ number_format($hot->unit_price) }} đ</span></h4>
											@else
											<h4 style="text-align: center;">{{ number_format($hot->unit_price) }} đ</h4>
											@endif
										</div>
										<div class="snipcart-details top_brand_home_details">
											<fieldset>
												<input type="button" value="Add to cart" class="button" id="add_product2" onclick="addCart({{ $hot->id }}, '{{ $hot->image }}', '{{ $hot->unit_price }}', '{{ $hot->promotional_price ? $hot->promotional_price : 0 }}', 1)">
											</fieldset>
										</div>
									</div>
								</figure>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
			</div>
		</div>
		<div class="container">
			<div class="agile_top_brands_grids">
				@foreach ($products_hot->skip(4)->take(8) as $hot)
				<div class="col-md-3 top_brand_left">
					<div class="hover14 column">
						<div class="agile_top_brand_left_grid">
							<div class="tag"><img src="lib/images/hot.png" alt=" " class="img-responsive" style="margin: -5px"></div>
							<div class="agile_top_brand_left_grid1">
								<figure>
									<div class="snipcart-item block">
										<div class="snipcart-thumb">
											<a href="{{ route('product_detail', $hot->id) }}"><img title=" " alt=" " src="images/{{ $hot->image }}" width="320px" height="150px"></a>		
											<p>{{ $hot->name }}</p>
											@if(isset($hot->promotional_price) && $hot->promotional_price != 0)
											<h4>{{ number_format($hot->promotional_price) }} đ<span>{{ number_format($hot->unit_price) }} đ</span></h4>
											@else
											<h4 style="text-align: center;">{{ number_format($hot->unit_price) }} đ</h4>
											@endif
										</div>
										<div class="snipcart-details top_brand_home_details">
											<fieldset>
												<input type="button" value="Add to cart" class="button" id="add_product1" onclick="addCart({{ $hot->id }}, '{{ $hot->image }}', '{{ $hot->unit_price }}', '{{ $hot->promotional_price ? $hot->promotional_price : 0 }}', 1)">
											</fieldset>
										</div>
									</div>
								</figure>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	{{-- // HOT product list --}}
	<hr>
	<h3 style="text-align: center; font-size: 40px">MENU</h3>
	@foreach($categories as $category)
	{{-- get product by category id --}}
	@php
	// select * from product where id = ? AND (STATUS = ACTIVE OR STATUS = SELL_OUT) order by (id,desc) limit 4
	$products = \App\Models\Product::where(['id_category' => $category->id])
		->where(function ($query) {
			$query->where('status', Config::get('constants.STATUS_ACTIVE'))
				  ->orWhere('status', Config::get('constants.STATUS_SELL_OUT'));
		})
		->orderBy('id', 'desc')
		->limit(4)
		->get();
	@endphp
	{{-- // get product by category id --}}
	<div class="container" id = "{{ $category->id }}">
		<br>
		<hr>
		<h3 style="padding-left: 20px"><span style="color:red">✔</span><a href="san-pham/{{ $category->id }}"> {{ $category->name }}</a></h3>
		@foreach($products as $product)
			<div class="agile_top_brands_grids">
				<div class="col-md-3 top_brand_left">
					<div class="hover14 column">
						<div class="agile_top_brand_left_grid">
							<div class="agile_top_brand_left_grid1">
								<figure>
									<div class="snipcart-item block" >
										<div class="snipcart-thumb">
											<a href="{{ route('product_detail', $product->id) }}"><img title=" " alt=" " src="images/{{ $product->image }}" width="320px" height="150px"></a>		
											<p>{{ $product->name }}</p>
											@if(isset($product->promotional_price) && $product->promotional_price != 0)
											<h4>{{ number_format($product->promotional_price) }} đ <span>{{ number_format($product->unit_price) }} đ</span></h4>
											@else
											<h4 style="text-align: center;">{{ number_format($product->unit_price) }} đ</h4>
											@endif
										</div>
										<div class="snipcart-details top_brand_home_details">
											<fieldset>
												@if($product->status == Config::get('constants.STATUS_ACTIVE'))
												<input type="button" value="Add to cart" class="button" id="add_product" onclick="addCart({{ $product->id }}, '{{ $product->image }}', '{{ $product->unit_price }}', '{{ $product->promotional_price ? $product->promotional_price : 0 }}', 1)">
												@else
												<input type="button" value="Hết Hàng" class="button" id="" style="background: #ccc">
												@endif
											</fieldset>
										</div>
									</div>
								</figure>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	@endforeach
	<hr>
	<div class="newsletter">
		<div class="container">
			<div class="w3agile_newsletter_left">
				<h3>Nhận thông tin khuyến mãi: </h3>
			</div>
			<div class="w3agile_newsletter_right">
				<input type="email" id="email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="" placeholder="Nhập email...">
				<input type="submit" value="Gửi" id="send_info">
				
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
<!-- //top-brands -->
@stop
@section('script')
<script>
	function addCart(product_id, product_img, product_price, product_promotion_price, qty) {
		let price = '';
		if(product_promotion_price == 'undefined' || product_promotion_price == '' || product_promotion_price == 0) {
			price = product_price;
		}
		else {
			price = product_promotion_price;
		}
		$.ajax({
	        url : '{{ route('add_cart') }}',
	        type : 'post',
	        data : {
	              'id'		: product_id,
	              'img' 	: product_img,
	              'price' 	: price,
	              'qty' 	: qty
	              },
	        success : function (data){
	        	let obj = JSON.parse(data);
	        	//console.log(obj);
		        if(obj.status == 1) {
		          	swal("Thêm thành công!", "Sản phẩm được thêm vào giỏ hàng", "success")
		          	.then((value) => {
					  $('#cart').val(obj.qty + ' Sản phẩm 　');
					});
		        }
	          	else swal("Lỗi!", "Có lỗi, vui lòng thử lại", "error");
	        }
	    });

	}
</script>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
  FB.init({
    xfbml            : true,
    version          : 'v9.0'
  });
};

(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Your Chat Plugin code -->
<div class="fb-customerchat"
attribution=setup_tool
page_id="122548605065565"
theme_color="#fa3c4c"
logged_in_greeting="Fast Food xin chào bạn. Tôi có thể giúp gì cho bạn ?"
logged_out_greeting="Fast Food xin chào bạn. Tôi có thể giúp gì cho bạn ?">
</div>
<script>
	// chức năng đăng ký nhận tin khuyến mãi !
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
	$('#send_info').click(function() {
		if($('#email').val() == '') {
			swal("error!", "Vui lòng nhập email", "error");
			return;
		}
		if(isEmail($('#email').val()) == false) {
			swal("error!", "Email không đúng định dạng", "error");
			return;
		}
		$.ajax({
	        url : '{{ route('get_discount') }}',
	        type : 'post',
	        data : {
	              'email' : $('#email').val(),
	              },
	        success : function (data){
	        	console.log(data);
	        	if(data == 'success') {
	        		swal("Success!", "Chúng tôi sẽ gửi thông tin các chương trình khuyến mãi vào email bạn đã đăng ký. Xin cảm ơn!", "success");
	        	}
	        	else {
	        		swal("error!", "Email đã được đăng ký!", "error");
	        	}
	        }
	    });
	})
</script>
<script>
	// keep position when reload page
    $(window).scroll(function () {
        sessionStorage.scrollTop = $(this).scrollTop();
    });
    $(document).ready(function () {
        if (sessionStorage.scrollTop != "undefined") {
            $(window).scrollTop(sessionStorage.scrollTop);
        }
    });
</script>
@stop