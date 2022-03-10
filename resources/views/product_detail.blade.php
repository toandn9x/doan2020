@extends('app')
@section('title', 'Chi tiết sản phẩm')
@section('content')
<div class="agileinfo_single">
	<h5>{{ $product->name }}</h5>
	<div class="col-md-4 agileinfo_single_left example">
		<img id="example" src="images/{{ $product->image }}" alt=" " class="img-responsive" />
	</div>
	<div class="col-md-8 agileinfo_single_right">
		<div class="rating1">
			<span class="starRating">
				<input id="rating5" type="radio" name="rating" value="5">
				<label for="rating5">5</label>
				<input id="rating4" type="radio" name="rating" value="4">
				<label for="rating4">4</label>
				<input id="rating3" type="radio" name="rating" value="3" checked>
				<label for="rating3">3</label>
				<input id="rating2" type="radio" name="rating" value="2">
				<label for="rating2">2</label>
				<input id="rating1" type="radio" name="rating" value="1">
				<label for="rating1">1</label>
			</span>
		</div>
		<div class="w3agile_description">
			<h4>Description :</h4>
			<p>{!! $product->description !!}</p>
		</div>
		<div class="snipcart-item block">
			<div class="snipcart-thumb agileinfo_single_right_snipcart">
				@if(isset($product->promotional_price) && $product->promotional_price != 0)
				<h4>
					{{ number_format($product->promotional_price) }} đ<span>{{ number_format($product->unit_price) }} đ</span>
				</h4>
				@else
				<h4>{{ number_format($product->unit_price) }} đ</h4>
				@endif
			</div>
			<div class="snipcart-details agileinfo_single_right_details">
				<fieldset>
					<table>
						<tr>
							<td>
								<input type="number" value="1" style="width: 50px; float: left;" id="qty" style="height:33px" min="1">
							</td>
							<td>
								@if($product->status == Config::get('constants.STATUS_ACTIVE'))
								<input type="button" value="Add to cart" class="button" id="add_product" style="width: 150px; left: 10px">
								@else
								<input type="button" value="Hết Hàng" class="button" id="" style="width: 150px; left: 10px; background: #ccc">
								@endif
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="clearfix"> </div>
</div>
<div class="w3ls_w3l_banner_nav_right_grid w3ls_w3l_banner_nav_right_grid_popular">
		<div class="container">
			<h3>Có thể bạn sẽ thích</h3>
				<div class="row multiple-items" style="display: flex; overflow-x: hidden; width: 100%;">

					@foreach ($other_product as $other)
					<div class="col-md-3 w3ls_w3l_banner_left">
						<div class="hover14 column">
						<div class="agile_top_brand_left_grid w3l_agile_top_brand_left_grid">
							<div class="agile_top_brand_left_grid1" >
								<figure>
									<div class="snipcart-item block">
										<div class="snipcart-thumb">
											<a href="{{ route('product_detail', $other->id) }}"><img src="images/{{ $other->image }}" width="320px" height="150px" style="display: block; max-width:320px;max-height:150px;width: auto;height: auto"></a>
											<p style="height: 35px">{{ $other->name }}</p>
											@if(isset($other->promotional_price) && $other->promotional_price != 0)
											<h4>{{ number_format($other->promotional_price) }} đ<span>{{ number_format($other->unit_price) }} đ</span></h4>
											@else
											<h4>{{ number_format($other->unit_price) }} đ</h4>
											@endif
										</div>
										<div class="snipcart-details">
											<fieldset>
												<input type="button" value="Add to cart" class="button" id="add_product" onclick="addCart({{ $other->id }}, '{{ $other->image }}', '{{ $other->unit_price }}', '{{ $other->promotional_price ? $other->promotional_price : 0 }}', 1)">
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
<script>
	$('#add_product').click(function() {
		let qty = $('#qty').val();
		addCart({{ $product->id }}, '{{ $product->image }}', '{{ $product->unit_price }}', '{{ $product->promotional_price ? $product->promotional_price : 0 }}', qty);
	})
</script>
<script type="text/javascript">
	$('.multiple-items').slick({
	  infinite: true,
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  autoplay: true,
  	  autoplaySpeed: 5000,
	  prevArrow:"<button type='button' class='slick-prev pull-left' style='width:50px; height:50px'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
      nextArrow:"<button type='button' class='slick-next pull-right' style='width:50px; height:50px'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            adaptiveHeight: true,
          },
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
	});
</script>
<script>
	$('.example').mtfpicviewer({
	  selector: 'img',
	  attrSelector: 'src',
	  
	});
</script>
@stop