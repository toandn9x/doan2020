<!DOCTYPE html>
<html>
<head>
<title>Fast food - Xác nhận</title>
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
			<h3>Đổi mật khẩu</h3>
			<div class="w3_login_module">
				<div class="module form-module">
				  <div class="form">
				  </div>
				  <div class="form">
					@if (session('err'))
					    <div class="alert alert-danger">
					        {{ session('err') }}
					    </div>
  					@endif
					<form action="{{ route('p_recover_pass') }}" method="post">
						@csrf
					  <input type="hidden" name="token" value="{{ $token }}" hidden>
					  <input type="password" name="password" placeholder="Nhập mật khẩu" required=" " minlength="5">
					  <input type="password" name="repass" placeholder="Nhập lại mật khẩu" required=" " minlength="5">
					  <input type="submit" value="Xác nhận">
					  <hr>
					  <p><a href="{{ route('g_login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Đăng nhập</a></p>
					</form>
				  </div>
				</div>
			</div>
		</div>
<!-- //login -->
	</div>
</body>
</html>