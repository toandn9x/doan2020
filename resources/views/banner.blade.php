<div class="banner">
		<div class="w3l_banner_nav_left">
			<nav class="navbar nav_bottom">
			 <!-- Brand and toggle get grouped for better mobile display -->
			  <div class="navbar-header nav_2">
				  <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
			   </div> 
			   <!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
					<ul class="nav navbar-nav nav_1">
						<li><a href="#hot">✎  Sản phẩm hot</a></li>
						@foreach($categories as $category)
						<li><a href="#{{ $category->id }}">✎  {{ $category->name }}</a></li>
						@endforeach
					</ul>
				 </div><!-- /.navbar-collapse -->
			</nav>
		</div>
		<div class="w3l_banner_nav_right">
			<section class="slider">
				<div class="flexslider">
					<ul class="slides">
						@foreach($slides as $sl)
						<li>
							<div class="w3l_banner_nav_right_banner" style="background:url(images/{{ $sl->image }}) no-repeat 0px 0px;">
								<h3>Make your <span>food</span> with Spicy.</h3>
								<div class="more">
									<a href="{{ $sl->link }}" class="button--saqui button--round-l button--text-thick" data-text="Shop now">Shop now</a>
								</div>
							</div>
						</li>
						@endforeach
					</ul>
				</div>
			</section>
			<!-- flexSlider -->
				<link rel="stylesheet" href="lib/css/flexslider.css" type="text/css" media="screen" property="" />
				<script defer src="lib/js/jquery.flexslider.js"></script>
				<script type="text/javascript">
				$(window).load(function(){
				  $('.flexslider').flexslider({
					animation: "slide",
					start: function(slider){
					  $('body').removeClass('loading');
					}
				  });
				});
			  </script>
			<!-- //flexSlider -->
		</div>
		<div class="clearfix"></div>
	</div>