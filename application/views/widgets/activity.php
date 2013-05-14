
  <div id="widget-activity" class="widget widget-table action-table">
    <div class="widget-header">
	  <i class="icon-play tag-music"></i>
      <h3>Latest activity</h3>
    </div><!-- /widget-header -->

    <div class="widget-content">
      <table class="table table-striped table-bordered">
        <tbody id="widget-activity-container"></tbody>
      </table>
    </div><!-- /widget-content -->
  </div>
  
  <script id="widget-activity-template" type="text/x-handlebars-template">
    	{{#each songs}}                         
		<tr>
			<td width="80%">
				<a href="/song/play/{{song.id}}" class="tag-music" title="Song: {{song.title}}">{{song.title}}</a><br />    
			</td>
			<td>
				<i class="icon-user"></i> <a href="/user/show/{{user.id}}" class="tag-socialx" title="User: {{user.name}}">{{user.name}}</a><br />              
				<i class="icon-align-justify"></i> <a href="/playlist/show/{{playlist.id}}" class="tag-collabx" title="Playlist: {{playlist.name}}">{{playlist.name}}</a>   
			</td>
		</tr>
	   {{/each}}
  </script>
  
  <script type="text/javascript">
  
        var widget_activity_id = setInterval(function() { reload_widget_activity(); }, <?php echo Kohana::$config->load('settings.refresh.activity'); ?> );            
		
		$(document).ready(function() { 
			reload_widget_activity(); 
		}); 
		
		function reload_widget_activity() {             
			$.getJSON('api/widget_activity', {'limit': <?php echo $limit; ?>}, function(data) {    
				var results = $('#widget-activity-container');                  
				var source = $("#widget-activity-template").html();             
				var template = Handlebars.compile(source);                     
				results.html(template(data));                
			});     
		}               
  
  </script>


