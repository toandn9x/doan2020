<!DOCTYPE html>
<html>
<head>
<title>Fast food - Đăng nhập</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="{{asset('')}}">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<link href="lib/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="lib/css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- font-awesome icons -->
<link href="lib/css/font-awesome.css" rel="stylesheet" type="text/css" media="all" /> 
<!-- //font-awesome icons -->
<!-- js -->
<script src="lib/js/jquery-1.11.1.min.js"></script>
<!-- //js -->
<link href='//fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
</head>
	
<body>
<!-- login -->
		<div class="w3_login">
			<h3>Đăng nhập & Đăng ký</h3>
			<div class="w3_login_module">
				<div class="module form-module">
				  <div class="toggle"><i class="fa fa-times fa-pencil"></i>
					<div class="tooltip">Click Me</div>
				  </div>
				  <div class="form">
					<h2>Đăng nhập</h2>
					@if ($errors->any())
					    @foreach ($errors->all() as $error)
					        <div class="alert alert-danger">{{ $error }}</div>
					    @endforeach
					@endif
					@if (session('err'))
					    <div class="alert alert-danger">
					        {{ session('err') }}
					    </div>
  					@endif
  					@if (session('success'))
					    <div class="alert alert-success">
					        {{ session('success') }}
					    </div>
  					@endif
					<form action="{{ route('p_login') }}" method="post">
						@csrf
					  <input type="email" name="email" placeholder="Nhập địa chỉ email" required=" ">
					  <input type="password" name="password" placeholder="Nhập mật khẩu" required=" ">
					  <input type="submit" value="Đăng nhập">
					</form>
				  </div>
				  <div class="form">
					<h2>Đăng ký tài khoản</h2>
					@if (session('err2'))
					    <div class="alert alert-danger">
					        {{ session('err') }}
					    </div>
					@endif
					@if (session('success'))
					    <div class="alert alert-success">
					        {{ session('success') }}
					    </div>
  					@endif
					<form action="{{ route('p_register') }}" method="post">
						@csrf
					  <input type="email" name="email" placeholder="Nhập địa chỉ email" required=" ">
					  <input type="password" name="password" placeholder="Nhập mật khẩu" required=" ">
					  <input type="password" name="repass" placeholder="Nhập lại mật khẩu" required=" ">
					  <input type="text" name="username" placeholder="Nhập tên" required=" ">
					  <input type="submit" value="Đăng ký">
					</form>
				  </div>
				  <div class="cta"><a href="{{ route('g_reset_pass') }}">Quên mật khẩu?</a></div>
				</div>
			</div>
			<script>
				$('.toggle').click(function(){
				  // Switches the Icon
				  $(this).children('i').toggleClass('fa-pencil');
				  // Switches the forms  
				  $('.form').animate({
					height: "toggle",
					'padding-top': 'toggle',
					'padding-bottom': 'toggle',
					opacity: "toggle"
				  }, "slow");
				});
			</script>
		</div>
<!-- //login -->
	</div>
<!-- //banner -->
<!-- newsletter-top-serv-btm -->
	{{-- <div class="newsletter-top-serv-btm">
		<div class="container">
			<div class="col-md-4 wthree_news_top_serv_btm_grid">
				<div class="wthree_news_top_serv_btm_grid_icon">
					<i class="fa fa-shopping-cart" aria-hidden="true"></i>
				</div>
				<h3>Nam libero tempore</h3>
				<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus 
					saepe eveniet ut et voluptates repudiandae sint et.</p>
			</div>
			<div class="col-md-4 wthree_news_top_serv_btm_grid">
				<div class="wthree_news_top_serv_btm_grid_icon">
					<i class="fa fa-bar-chart" aria-hidden="true"></i>
				</div>
				<h3>officiis debitis aut rerum</h3>
				<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus 
					saepe eveniet ut et voluptates repudiandae sint et.</p>
			</div>
			<div class="col-md-4 wthree_news_top_serv_btm_grid">
				<div class="wthree_news_top_serv_btm_grid_icon">
					<i class="fa fa-truck" aria-hidden="true"></i>
				</div>
				<h3>eveniet ut et voluptates</h3>
				<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus 
					saepe eveniet ut et voluptates repudiandae sint et.</p>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div> --}}
@include('footer')
</body>
</html>