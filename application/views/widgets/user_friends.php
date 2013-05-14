<div class="user-social-container widget widget-box">	
	<?php if(isset($title)) { ?>				
	<div class="widget-header">	
		<i class="icon-user tag-social"></i>
		<h3><?php echo $title; ?></h3>			
	</div> <!-- /widget-header -->
	<?php } ?>

	<div class="widget-content">
		<div class="friends-box">
		<?php foreach($friends as $f)
			echo $f->get_cover_view('smaller');
		?>
		</div>
	</div> <!-- /widget-content -->		
</div>	
