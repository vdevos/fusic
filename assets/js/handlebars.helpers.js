	
	Handlebars.registerHelper('secondsToMinutes', function(seconds) {
		seconds = Number(seconds);
		var h = Math.floor(seconds / 3600);
		var m = Math.floor(seconds % 3600 / 60);
		var s = Math.floor(seconds % 3600 % 60);
		return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s);		
	});
	
	Handlebars.registerHelper('parseViews', function(views) {
		if (views >= 1000) {
			if (views % 1000 == 0) return Math.floor(views/1000) + 'K';
			else return '>' + Math.floor(views/1000) + 'K';
		}
		return views;
	});
	
	Handlebars.registerHelper('ifequal', function(val1, val2, options) {
	    if(val1 ==  val2) {
		    return options.fn(this);
	    } else {
		    return options.inverse(this);
	    }
	});
	Handlebars.registerHelper('onlineStatus', function(status) {
		return ((status == 'yes') ? 'Active in playlist' : 'Not active in playlist');
	});
	
	Handlebars.registerHelper('positivesFromRating', function(rating) {
		return 100 * (rating - 1) / (5 - 1);
	});
	Handlebars.registerHelper('negativesFromRating', function(rating) {
		return 100 * (5 - rating) / (5 - 1);
	});
	
	Handlebars.registerHelper('counter', function(items, options) {
		return items.length;
	});	
	
	Handlebars.registerHelper('commaSeperated', function(items, options) {
		var out = "";
		for(var i = 0, l = items.length; i < l; i++) {
			if (i > 0) {
				out += ',';
			}
			out += options.fn(items[i]);
		} 
		return out;
	});
	
	Handlebars.registerHelper('fuzzySpan', function(dater) {
		
		var date = new Date(dater * 1000);
		var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
		var diff = (new Date() - date) / 1000;
				
		if (diff < -10) result = 'in the future!?';
		else if (diff < 60) result = 'moments ago';
		else if (diff < 60 * 20) result = 'a few minutes ago';
		else if (diff < 3600) result = 'less then an hour ago';
		else if (diff < 3600 * 4) result = 'a couple of hours ago';
		else if (diff < 3600 * 24) result = 'less than a day ago';
		else if (diff < 3600 * 24 * 2) result = 'about a day ago';
		else if (diff < 3600 * 24 * 4) result = 'a couple of days ago';
		else {
			result = 'on ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
		}
	
		return result;
	});
	
	Handlebars.registerHelper('readableDate', function(date) {
	
		 var a = new Date(date * 1000);
		 var year = a.getFullYear();
		 var month = (a.getMonth() < 10) ? '0'+a.getMonth() : a.getMonth();
		 var date = (a.getDate() < 10) ? '0'+a.getDate() : a.getDate();
		 var hour = (a.getHours() < 10) ? '0'+a.getHours() : a.getHours();
		 var min = (a.getMinutes() < 10) ? '0'+a.getMinutes() : a.getMinutes();
		 var sec = (a.getSeconds() < 10) ? '0'+a.getSeconds() : a.getSeconds();
		 var time = date+'/'+month+'/'+year+' '+hour+':'+min+':'+sec+'';
		 return time;		
	});