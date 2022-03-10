<div class="logo_products">
	<div class="container">
		<div class="w3ls_logo_products_left">
			<a href="{{ route('index') }}"><img src="images/{{ $img }}" alt="logo" width="150px"></a>
		</div>
		<div class="w3ls_logo_products_left1">
			<ul class="special_items">
				<li><a href="{{ route('get_contact') }}">About Us</a><i>/</i></li>
				<li><a href="{{ route('get_sale') }}">Best Deals</a><i>/</i></li>
				<li>contact: </li>
			</ul>
		</div>
		<div class="w3ls_logo_products_left1">
			<ul class="phone_email">
				<li><i class="fa fa-phone" aria-hidden="true"></i>(+84) {{ $post[2] }}</li>
				<li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="mailto:{{ $post[3] }}">{{ $post[3] }}</a></li>
			</ul>
		</div>
		<div class="clearfix"> </div>
	</div>
</div>