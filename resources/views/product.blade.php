@extends('app')
@section('title', 'Sản phẩm')
@section('content')
<div class="w3ls_w3l_banner_nav_right_grid w3ls_w3l_banner_nav_right_grid_sub">
	<h3>{{ $category->name }}</h3>
	<hr>
	@foreach($products as $product)
	<div class="col-md-3 w3ls_w3l_banner_left" style="margin-bottom: 20px">
		<div class="hover14 column">
		<div class="agile_top_brand_left_grid w3l_agile_top_brand_left_grid">
			<div class="agile_top_brand_left_grid_pos">
				<img src="lib/images/offer.png" alt=" " class="img-responsive" />
			</div>
			<div class="agile_top_brand_left_grid1">
				<figure>
					<div class="snipcart-item block">
						<div class="snipcart-thumb" style="height: 250px">
							<a href="{{ route('product_detail', $product->id) }}"><img src="images/{{ $product->image }}" width="320px" height="150px"></a>
							<p>{{ $product->name }}</p>
							@if(isset($product->promotional_price) && $product->promotional_price != 0)
							<h4>{{ number_format($product->promotional_price) }} đ<span>{{ number_format($product->unit_price) }} đ</span></h4>
							@else
							<h4 style="text-align: center;">{{ number_format($product->unit_price) }} đ</h4>
							@endif
						</div>
						<div class="snipcart-details top_brand_home_details">
							<fieldset>
								<input type="button" value="Add to cart" class="button" id="add_product2" onclick="addCart({{ $product->id }}, '{{ $product->image }}', '{{ $product->unit_price }}', '{{ $product->promotional_price ? $product->promotional_price : 0 }}', 1)">
							</fieldset>
						</div>
					</div>
				</figure>
			</div>
		</div>
		</div>
	</div>
	@endforeach
	<div style="clear: both;">
	<div style="display: flex; justify-content: center;">
		<ul class="pagination pagination-lg">
			{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page=1">First</a></li>
            {{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            @if($products->currentPage() > 1)
                <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->currentPage() - 1 }}">Previous</a></li>
            @endif
                <li class="page-item active"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->currentPage() }}">{{ $products->currentPage() }}</a></li>
            @if($products->currentPage() < $products->lastPage())
                <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->currentPage() + 1 }}">{{ $products->currentPage() + 1 }}</a></li>
                @if($products->currentPage() < $products->lastPage() - 2)
                <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->currentPage() + 2 }}">{{ $products->currentPage() + 2 }}</a></li>
                @endif
                @if($products->currentPage() < $products->lastPage() - 3)
                <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->currentPage() + 3 }}">{{ $products->currentPage() + 3 }}</a></li>
                @endif
            @endif
            {{-- nếu page hiện tại < page cuối thì hiện nút next --}}
            @if($products->currentPage() < $products->lastPage())
                <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->currentPage() + 1 }}">Next</a>
            @endif
            <li class="page-item"><a class="page-link" href="san-pham/{{ $category->id }}?page={{ $products->lastPage() }}">Last</a></li>
		</ul>
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
@stop