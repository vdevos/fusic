<!DOCTYPE html>
<html lang="en">
  <head>
	<base href="<?php echo URL::base(TRUE); ?>">
    <meta charset="utf-8">
    <title>Fusic <?php echo isset($title) ? ' | '.$title : ''; ?></title>
    
	<!-- META/SEO -->
	<meta name="description" content="Fusic collaborative & social music playlists">
	<meta name="keywords" content="fusic,music,playlists,collaborative,social,youtube,facebook,twitter" />
    <meta name="author" content="Vincent de Vos">
	
	<!-- SCALING/VIEWING -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
	
	<!-- BOOTSTRAP -->
	<?php echo HTML::style('assets/css/bootstrap.min.css'); ?>
	<?php echo HTML::style('assets/css/bootstrap-responsive.min.css'); ?>
	
	<!-- FONTS -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
	<?php echo HTML::style('assets/css/font-awesome.css'); ?>
	
	<!-- THEMING -->
	<link rel="icon" type="image/png" href="/assets/img/favicon.ico">
	<?php echo HTML::style('assets/css/base-admin.css'); ?>
	<?php echo HTML::style('assets/css/base-admin-responsive.css'); ?>
	<?php echo HTML::style('assets/css/pages/dashboard.css'); ?>
	
	<?php echo HTML::script('assets/js/jquery.min.js'); ?>
	<?php echo HTML::script('assets/js/general.js'); ?>
	
	<?php if(isset($header_includes)) { foreach($header_includes as $include) { echo HTML::style($include);	} } ?>
	<?php if(isset($header_js_includes)) { foreach($header_js_includes as $include) { echo HTML::script($include);	} } ?>
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
  </head>
  
  <body>
	<div class="main-container">
        <div class="navbar navbar-fixed-top">	
            <div class="navbar-inner">			
                <div class="container">			
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    
                    <a class="brand" href="/">Fusic</a>
                    <?php if(isset($frontpage) || true) { ?>
                        <a class="brand slang" href="/">
                            <span class="tag-social">Social</span> 
                            <span class="tag-music">Music</span>
                            <span class="tag-collab">Collaboration</span>
                        </a>
                    <?php } ?>
                    
                    <?php if($logged_in) { ?>
                        <div class="nav-collapse">						
                            <ul class="nav pull-right">
                                <li><input id="nav-search" type="text" class="input-medium search-query" placeholder="Search for playlists, tags and users"></li>
                                <li></li>
                                <li class="dropdown">							
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-cog"></i>
                                        Settings
                                        <b class="caret"></b>
                                    </a>							
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:;">Account Settings</a></li>
                                        <li><a href="javascript:;">Privacy Settings</a></li>
                                        <li class="divider"></li>
                                        <li><a href="javascript:;">Help</a></li>
                                    </ul>							
                                </li>
                        
                                <li class="dropdown">							
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php echo $user->username; ?>
                                        <b class="caret"></b>
                                        <img class="header-cover" src="<?php echo $user->get_cover(); ?>">
                                    </a>							
                                    <ul class="dropdown-menu">
                                        <li><a href="/user/profile/">Edit profile</a></li>
                                        <li class="divider"></li>
                                        <li><a href="/user/logout/">Logout</a></li>
                                    </ul>							
                                </li>
                            </ul>
                            
                            <div id="nav-search-results">							
                            </div>
                                    
                        </div><!-- /.nav-collapse -->			
                    <?php } else { ?> 
                        <!-- NOT LOGGED IN -->
                        <div class="nav-collapse">
                            <ul class="nav pull-right">	
                                <?php if(!isset($islogin)) { ?>
                                <li>						
                                    <a href="/user/login/" class="">
                                        <i class="icon-user"></i> Sign in
                                    </a>								
                                </li>	
                                <?php } ?>
                                <?php if(!isset($iscreating)) { ?>
                                    <li>						
                                        <a href="/user/create/" class="">
                                        <i class="icon-ok"></i> Create account
                                        </a>								
                                    </li>	
                                <?php } ?>
                                <?php if(!isset($frontpage)) { ?>
                                <li class="">						
                                    <a href="/" class="">
                                        <i class="icon-chevron-left"></i> Back to Homepage
                                    </a>								
                                </li>
                                <?php } ?>
                            </ul>						
                        </div>
                    <?php } ?>	
                    
                </div> <!-- /container -->			
            </div> <!-- /navbar-inner -->		
        </div> <!-- /navbar -->	
        
        <?php if($logged_in) { ?>
            <div class="subnavbar">
                <div class="subnavbar-inner">
                    <div class="container">
                        <ul class="mainnav">					
                            <li id="item-overview" <?php echo ($section == 'overview') ? 'class="active"' : ''; ?>>
                                <a href="/overview">
                                    <i class="icon-home"></i>
                                    <span>Overview</span>
                                </a>	    				
                            </li>						
                            <li id="item-playlist" class="dropdown subnavbar-open-right <?php echo ($section == 'playlist') ? 'active' : ''; ?>">
                                <a href="javascript:;" class="dropdown-toggle" data-togle="dropdown">
                                    <i class="icon-align-justify"></i>
                                    <span>Playlists</span>
                                    <b class="caret"></b>
                                </a>			
                                <ul class="dropdown-menu">
                                    <li><a href="/playlists/yours">Yours</a></li>
                                    <li><a href="/playlists/following">Following</a></li>
                                    <li><a href="/playlists/explore">Explore</a></li>
                                    <li class="divider subnavbar-open-right"></li>
                                    <li><a href="/playlists/create">Create</a></li>
                                </ul>    				
                            </li>				
                            <li id="item-loved" <?php echo ($section == 'loved') ? 'class="active"' : ''; ?>>					
                                <a href="/playlists/loved/" class="dropdown-toggle">
                                    <i class="icon-heart"></i>
                                    <span>Loved songs</span>
                                </a>	  				
                            </li>					
                            <li id="item-friends" <?php echo ($section == 'friends') ? 'class="active"' : ''; ?>>
                                <a href="/friends">
                                    <i class="icon-user"></i>
                                    <span>Friends</span>
                                </a>	    				
                            </li>						
                        </ul>
                    </div> <!-- /container -->
                </div> <!-- /subnavbar-inner -->
            </div> <!-- /subnavbar -->
        <?php } ?>
        
        <div id="top" class="main">
            <div class="main-inner">
                <div class="container">
                    <?php echo $view; ?>
                </div> <!-- /container -->
            </div> <!-- /main-inner -->
        </div> <!-- /main -->	

        <!-- FOOTER -->
        <footer id="footer">
            <div class="content-wrap">	
                <!--<nav>
                    <ul class="content-items">
                        <li><a href="#">About</a></li>
                        <li><a href="#">News</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#" title="">Feedback</a></li>
                        <li><a href="#">Terms</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </nav>-->
                <p class="copyright">
                    <a href="mailto:info@fusic.nl" class="tag-music">Fusic</a> 
                    by <a href="http://www.vdevos.nl" class="tag-social">Vincent de Vos</a> 
                    &copy; 2012 | Hosted by <a href="http://www.circlehosting.nl/" class="tag-collab">Circle Hosting</a>
                </p>	
            </div>		
        </div>
	</footer>
	
 </body>
	
	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<?php echo HTML::script('assets/js/excanvas.min.js'); ?>
	<?php echo HTML::script('assets/js/jquery.flot.js'); ?>
	<?php echo HTML::script('assets/js/jquery.flot.pie.js'); ?>
	<?php echo HTML::script('assets/js/jquery.flot.orderBars.js'); ?>
	<?php echo HTML::script('assets/js/jquery.flot.resize.js'); ?>
	<!-- BOOTSTRAP -->
	<?php echo HTML::script('assets/js/bootstrap.js'); ?>
	<?php echo HTML::script('assets/js/bootstrap-dropdown.js'); ?>
	<?php echo HTML::script('assets/js/bootstrap-buttons.js'); ?>
	<?php echo HTML::script('assets/js/base.js'); ?>
	
	<!-- HANDLEBARS -->
	<?php echo HTML::script('assets/js/handlebars.js'); ?>
	<?php echo HTML::script('assets/js/handlebars.helpers.js'); ?>
	
	<script id="nav-search-template" type="text/x-handlebars-template">
		<ul>
			{{#if users}}
				<li class="widget-header"><div><i class="icon-user icon-white tag-social"></i> Users</div></li>
				{{#each users}}
					<li class="result-item result-user">
						<a class="cover-link" href="/user/show/{{name}}" title="Show profile">
							<img class="img cover cover-tinytiny" src="{{cover}}">
						</a>
						<div class="result-title">{{name}}</div>
					</li>
				{{/each}}							
			{{/if}}
			
			{{#if playlists}}
			<li class="widget-header"><div><i class="icon-align-justify icon-white tag-collab"></i> Playlists</div></li>
				{{#each playlists}}				
				<li class="result-item result-playlist">
					<a class="cover-link" href="/playlist/show/{{id}}" title="Show playlist">
						<img class="img cover cover-tinytiny" src="{{cover}}">
					</a>
					<div class="result-title">{{name}}</div>
					<div class="result-tags">
						{{#each tags}}
							<div class="result-tag">{{this}}</div>
						{{/each}}				
					</div>
				</li>
				{{/each}}
			{{/if}}
			
			{{#if songs}}
				<li class="widget-header"><div><i class="icon-play icon-white tag-music"></i> Songs</div></li>
				{{#each songs}}								
					<li class="result-item result-song">
						<div class="result-title">{{ArtistName}} - {{SongName}}</div>
					</li>
				{{/each}}
			{{/if}}
		</ul>	
	</script>
	
	<script>
		
		$('.dropdown-toggle').dropdown();
		
		/* [ NAV SEARCH ] */
		$(document).ready(function() {
			
			var timer;
			$('#nav-search').keyup(function() 
			{ 
				clearTimeout(timer);		
				var input = $(this);
				var results = $('#nav-search-results');
				var val = $(this).val();

				if (val.length >= 2) {
					timer = setTimeout(function() {
						results.html('');
						$.getJSON('/api/search', { q: val }, function(data) {
							var source = $("#nav-search-template").html();
							var template = Handlebars.compile(source);
							results.html(template(data));
							results.show();
						});					
					}, 300);
				}
				else {
					results.hide();
				}
			});			
		});
	
	</script>
 
</html>