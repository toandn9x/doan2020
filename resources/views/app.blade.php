<!DOCTYPE html>
<html>
<head>
<title>Fast food - @yield('title')</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="{{asset('')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">

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
{{-- slick slider --}}
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
{{-- image view --}}
<link rel="stylesheet" href="lib/css/jquery.mtfpicviewer.css" />
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="lib/js/move-top.js"></script>
<script type="text/javascript" src="lib/js/easing.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<style>
	html {
	  scroll-behavior: smooth;
	}
	h3:hover {
	  text-decoration: underline;
	  text-decoration-color: red!important;
	  transition: 3s;
	}
	hr {
		border: 1px solid #ccc;
	}
	span.caret {
    margin-right: 50px;
	}
</style>
<!-- start-smoth-scrolling -->
</head>
	
<body onload="startTime()">
<!-- menu -->

@include('top_menu')
@include('second_menu')

<!-- banner -->
@include('banner')
<!-- end banner -->

{{-- content --}}
<div class="container">
    @yield('content')
</div>
{{-- End content --}}

<!-- footer -->
@include('footer')
<!-- end footer -->
<!-- Bootstrap Core JavaScript -->
<script src="lib/js/bootstrap.min.js"></script>
<!-- sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
{{-- slick slider --}}
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
{{-- image view --}}
<script src="lib/js/jquery.mtfpicviewer.js"></script>
<script>
$(document).ready(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideDown("fast");
            $(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
        }
    );
});
</script>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->
<script src="lib/js/minicart.min.js"></script>
<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  // $(document).ready(function () {
  //   bsCustomFileInput.init();
  // });
</script>
@yield('script')
</body>
</html>