<div class="row">
	
	<div class="span6">
		<div class="widget stacked">
						
			<div class="widget-header">
				<i class="icon-bar-chart"></i>
				<h3>Donut Chart</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content">
			
				<div id="donut-chart" class="chart-holder" style="padding: 0px; position: relative;">
				</div> 
			
			</div> <!-- /widget-content -->
		
		</div>
	</div>
	
	<div class="span6">
		<div class="widget stacked">
					
			<div class="widget-header">
				<i class="icon-bar-chart"></i>
				<h3>Area Chart</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content">
			
				<div id="area-chart" class="chart-holder" style="padding: 0px; position: relative;"></div>
			
			</div> <!-- /widget-content -->
		
		</div>

	</div>
	
</div>

<style>
	
</style>

<script>
	$(function () {		
		
		var data = [];
		var series = 3;
		for( var i = 0; i<series; i++)
		{
			data[i] = { label: "Series "+(i+1), data: Math.floor(Math.random()*100)+1 }
		}

		$.plot($("#donut-chart"), data,
		{
			colors: ["#4099ff", "#F76700", "#6C3"],
				series: {
					pie: { 
						innerRadius: 0.5,
						show: true
					}
				}
		});	
	});
	
	$.getJSON('/ajax/get_playlist_stats', { pid:19 }, function(data) 
    {		
		glob = data;
		// we use an inline data source in the example, usually data would
		// be fetched from a server
		var pdata = [];
		var totalPoints = data.count;		
		
		for(var key in data.stats) {			
			pdata.push([(new Date(key)).getTime(), data.stats[key]]);
			console.log(data.stats[key]);
		}		

		// setup plot
		var options = {
			yaxis: { min: 0, max: data.ymax },
			xaxis: { min: (data.xmin * 1000), 
					 max: (data.xmax * 1000), 
					 mode: "time", 
					 timeformat:"%d/%m/%y",
					 minTickSize: [1, "day"]					 
			},
			colors: ["#F90", "#222", "#666", "#BBB"],
			series: {
					   lines: { 
							lineWidth: 2, 
							fill: true,
							fillColor: { colors: [ { opacity: 0.6 }, { opacity: 0.2 } ] },
							steps: false

						}
				   }
		};
		
		var plot = $.plot($("#area-chart"), [ pdata ], options);
			
			
			
    });			
	
</script>