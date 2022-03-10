<!-- header -->
<style type="text/css" media="screen">
	#logout a:hover {
		background: red;
	}
	.w3l_header_right1:hover #logout { 
		display:block!important;
	}
	@media screen and (max-width: 1450px) {
		.w3l_header_right1 {
			width: 250px!important;
		}
		.w3l_header_right1 a {
			font-size: 15px!important;
		}
	}
	@media screen and (max-width: 1250px) {
		.w3l_header_right1 {
			width: 250px!important;
		}
		.w3l_offers {
			display:none!important;

		}
		.w3l_header_right1 a {
			font-size: 15px!important;
		}
	}
	@media screen and (max-width: 800px) {
		.w3l_search {
		    width: 40%!important;
		    height: 30px!important;
		    margin-left: 0px!important;
		}
		.w3l_header_right1 {
			width: 25%!important;
			padding-right: 7px!important;
		}
		.product_list_header {
			width: 5%!important;
			margin-left: 15px!important;
			width: 15px!important;
		}
	}
	@media screen and (max-width: 799px) {
		.w3l_search {
		   width: 100%!important;
		}
		.w3l_header_right1 {
			width: 100%!important;
		}
		.product_list_header {
			width: 100px!important;
			align-items: center; margin:10px 0px;
		}
		
	}
	
</style>
	<div class="agileits_header">
		<div class="w3l_offers">
			<a href="#" id = "time">08:08 ngày 23/09/2020</a>
		</div>
		<div class="w3l_search">
			<form action="{{ route('search') }}" method="get">
				<input type="text" name="key" placeholder="Search a product..." required>
				<input type="submit" value=" ">
			</form>
		</div>
		<div class="product_list_header" style="margin-left: 4em">  
            <fieldset>
            	<form method="get" action="{{ route('v_cart') }}" >
            	@if(count(Cart::content()) > 0) 
            	<input type="submit" name="submit" value="{{ count(Cart::content()). ' Sản phẩm 　' }} " class="button" id="cart">
            	@else
                <input type="submit" name="submit" value="View your cart" class="button" id="cart">
                @endif
            	</form>
            </fieldset>
		</div>
		<div class="w3l_header_right1" style="background: #84C639; width: 335px; height: 46px; text-align: center; align-items: center; transition: all .5s ease-in;">
			@if(Auth::check())
				<a href="#" style="font-family: sans-serif !important; color: #FFF; line-height: 45px; font-size: 20px; text-decoration: none;">{{ Auth::user()->name }}</a>
				<div id="logout" style="background: #84C639; display:none">
					<a style="cursor: pointer; display:block; color: #fff; font-size: 20px; border: 1px solid #ccc" href="{{ route('order_history') }}">Đơn hàng của tôi</a>
					<a style="cursor: pointer; display:block; color: #fff; font-size: 20px; border: 1px solid #ccc" href="{{ route('logout') }}">Đăng Xuất</a>
				</div>
			@else
				<a href="{{ route('g_login') }}" style="font-family: sans-serif !important; color: #FFF; line-height: 45px; font-size: 20px; text-decoration: underline;">Đăng Nhập</a>
			@endif
		</div>
		<div class="clearfix"> </div>
	</div>
<!-- script-for sticky-nav -->
	<script>
	$(document).ready(function() {
		 var navoffeset=$(".agileits_header").offset().top;
		 $(window).scroll(function(){
			var scrollpos=$(window).scrollTop(); 
			if(scrollpos >=navoffeset){
				$(".agileits_header").addClass("fixed");
			}else{
				$(".agileits_header").removeClass("fixed");
			}
		 });
		 
	});
	</script>
<!-- //script-for sticky-nav -->
<!-- clock -->
<script>
function startTime() {
  var today = new Date();
  var day 	= today.getDay();
  var date 	= today.getDate()
  var mth 	= today.getMonth();
  var year 	= today.getFullYear();
  var h 	= today.getHours();
  var m 	= today.getMinutes();
  var s 	= today.getSeconds();
  date 	= checkTime(date);
  mth 	= checkTime(mth);
  m 	= checkTime(m);
  s 	= checkTime(s);
  document.getElementById('time').innerHTML = date + "-" + mth + "-" + year + " " +
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>