
$(document).ready(function() {
	
	/* SETUP JQUERY PLUGINS */
	
	(function($){
		
		$.fn.SetEnabled = function() { this.removeClass('disabled'); };		
		$.fn.SetDisabled = function() { this.addClass('disabled'); };		
		$.fn.SetTitle = function(tip) { this.attr('title',tip); };	
		
		/* LOCKING BUTTONS */
		$.fn.btnSetLocked = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.removeClass('btn-info').addClass('btn-success');
					this.children('span:first').html('Public');
					this.children('i:first').removeClass('icon-lock').addClass('icon-ok-circle');
				}
				else if(type == 'out') {
					this.removeClass('btn-success').addClass('btn-info');
					this.children('span:first').html('Private');
					this.children('i:first').removeClass('icon-ok-circle').addClass('icon-lock');					
				}
			}
			else {
				this.attr('data-locked','yes');
				this.removeClass('btn-success').addClass('btn-info');
				this.children('span:first').html('Private');
				this.children('i:first').removeClass('icon-ok-circle').addClass('icon-lock');
			}
			return this;
		};
		$.fn.btnSetUnlocked = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.removeClass('btn-success').addClass('btn-info');
					this.children('span:first').html('Private');
					this.children('i:first').removeClass('icon-ok-circle').addClass('icon-lock');
				}
				else if(type == 'out') {
					this.removeClass('btn-info').addClass('btn-success');
					this.children('span:first').html('Public');
					this.children('i:first').removeClass('icon-lock').addClass('icon-ok-circle');
				}
			}
			else {
				this.attr('data-locked','no');
				this.removeClass('btn-info').addClass('btn-success');
				this.children('span:first').html('Public');
				this.children('i:first').removeClass('icon-lock').addClass('icon-ok-circle');
			}
			return this;
		};	
		
		/* FOLLOWING BUTTONS */
		$.fn.btnSetFollow = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.addClass('btn-success');
					this.children('i:first').addClass('icon-white');
				}
				else if(type == 'out') {
					this.removeClass('btn-success');
					this.children('i:first').removeClass('icon-white');
				}
			}
			else {
				this.attr('data-following','no');
				this.removeClass('btn-danger').removeClass('btn-success');
				this.children('span:first').html('Follow');
				this.children('i:first').removeClass().addClass('icon-ok');
			}
			return this;
		}
		$.fn.btnSetUnfollow = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.removeClass('btn-success').addClass('btn-danger');
					this.children('span:first').html('Unfollow');
					this.children('i:first').removeClass('icon-ok-circle').addClass('icon-minus-sign');
				}
				else if(type == 'out') {
					this.removeClass('btn-danger').addClass('btn-success');
					this.children('span:first').html('Following');
					this.children('i:first').removeClass('icon-minus-sign').addClass('icon-ok-circle');
				}
			}
			else {
				this.removeClass('btn-success').addClass('btn-danger');
				this.children('span:first').html('Following');
				this.children('i:first').removeClass().addClass('icon-minus-sign').addClass('icon-white');
			}
			return this;
		};				
		$.fn.btnSetFollowing = function() {
			this.attr('data-following','yes');
			this.removeClass('btn-danger').addClass('btn-success');
			this.children('span:first').html('Following');
	    	this.children('i:first').removeClass().addClass('icon-ok-circle').addClass('icon-white');
			return this;
		};		
		
		/* PRIVILEGE BUTTONS */
		$.fn.btnSetViewer = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.removeClass('btn-warning').addClass('btn-primary');
					this.children('span:first').html('Editor');
					this.children('i:first').removeClass().addClass('icon-pencil').addClass('icon-white');
				}
				else if(type == 'out') {
					this.removeClass('btn-primary').addClass('btn-warning');
					this.children('span:first').html('Viewer');
					this.children('i:first').removeClass().addClass('icon-eye-open').addClass('icon-white');
				}
			}
			else {
				this.attr('data-privilege','viewer');
				this.removeClass('btn-primary').addClass('btn-warning');
				this.children('span:first').html('Viewer');
				this.children('i:first').removeClass().addClass('icon-eye-open').addClass('icon-white');
			}
			return this;
		};	
		$.fn.btnSetEditor = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.removeClass('btn-primary').addClass('btn-warning');
					this.children('span:first').html('Viewer');
					this.children('i:first').removeClass().addClass('icon-eye-open').addClass('icon-white');
				}
				else if(type == 'out') {
					this.removeClass('btn-warning').addClass('btn-primary');
					this.children('span:first').html('Editor');
					this.children('i:first').removeClass().addClass('icon-pencil').addClass('icon-white');
				}
			}
			else {
				this.attr('data-privilege','editor');
				this.removeClass('btn-warning').addClass('btn-primary');
				this.children('span:first').html('Editor');
				this.children('i:first').removeClass().addClass('icon-pencil').addClass('icon-white');
			}
			return this;
		};	
		
		/* [ BTN - USER:STYLE ] */
		$.fn.btnLoadUserFriend = function(status) 
		{
			var self = $(this);
			var fid = self.attr('data-user-id');
			var fstatus = self.attr('data-friend');
			status = status || fstatus;
			
			self.removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger');
			self.children('i:first').removeClass().addClass('icon-white');
			
			if(status == 'yes') 
			{
				self.addClass('btn-success');
				self.children('span:first').html('Friend');
				self.children('i:first').addClass('icon-ok-circle');
			}
			else if(status == 'no') 
			{
				self.children('span:first').html('Add friend');
				self.children('i:first').addClass('icon-ok');	
			}	
			else if(status == 'pending') 
			{
				self.addClass('btn-warning');
				self.children('span:first').html('Pending');
				self.children('i:first').addClass('icon-time');	
			}
			else if(status == 'hover') 
			{
				if(fstatus == 'yes') 
				{
					self.addClass('btn-danger');
					self.children('span:first').html('Remove friend');
					self.children('i:first').addClass('icon-remove');
					self.attr('title','Remove friend');
				}
				else if(fstatus == 'no') 
				{
					self.addClass('btn-success');
					self.children('span:first').html('Add friend');
					self.children('i:first').addClass('icon-ok');
					self.attr('title','Add as friend');
				}
				else if(fstatus == 'pending') 
				{
					self.addClass('btn-danger');
					self.children('span:first').html('Undo request');
					self.children('i:first').addClass('icon-remove');
					self.attr('title','Undo your friend request');
				}
			}			
			return self;
		};
		
		$.fn.btnSetIsFriend = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.removeClass('btn-success').addClass('btn-danger');
					this.children('span:first').html('Remove friend');
					this.children('i:first').removeClass().addClass('icon-remove').addClass('icon-white');
				}
				else if(type == 'out') {
					this.addClass('btn-success');
					this.children('span:first').html('Friend');
					this.children('i:first').removeClass().addClass('icon-ok-circle').addClass('icon-white');
				}
			}
			else {
				this.attr('data-friend','yes');
				this.addClass('btn-success');
				this.children('span:first').html('Friend');
				this.children('i:first').removeClass().addClass('icon-ok-circle').addClass('icon-white');
			}
			return this;
		};	
		$.fn.btnSetNoFriend = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.addClass('btn-success');
					this.children('span:first').html('Add Friend');
				}
				else if(type == 'out') {
					this.removeClass('btn-success');
					this.children('span:first').html('Add Friend');
				}
			}
			else {
				this.attr('data-friend','yes');
				this.removeClass('btn-success');
				this.children('span:first').html('Add friend');
				this.children('i:first').removeClass().addClass('icon-add').addClass('icon-white');
			}
			return this;
		};	
		$.fn.btnSetPendingFriend = function(type) {
			this.attr('data-friend','yes');
				this.removeClass('btn-success');
				this.children('span:first').html('Add friend');
				this.children('i:first').removeClass().addClass('icon-add').addClass('icon-white');
		};
		
		
		$.fn.btnSetPlay = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.children('i:first').removeClass().addClass('icon-pause').addClass('icon-white');						
				}
				else if(type == 'out') {
					this.children('i:first').removeClass().addClass('icon-play').addClass('icon-white');
				}
			}
			else {
				this.attr('data-status','playing');
				this.children('i:first').removeClass().addClass('icon-play').addClass('icon-white');
			}						
		};
		
		$.fn.btnSetPause = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.children('i:first').removeClass().addClass('icon-play').addClass('icon-white');
				}
				else if(type == 'out') {
					this.children('i:first').removeClass().addClass('icon-pause').addClass('icon-white');
				}
			}
			else {
				this.attr('data-status','stopped');
				this.children('i:first').removeClass().addClass('icon-pause').addClass('icon-white');			
			}				
		};
		
		$.fn.btnSetStop = function(type) {
			type = type || false;
			if(type) {
				if(type == 'in') {
					this.children('i:first').removeClass().addClass('icon-play').addClass('icon-white');
				}
				else if(type == 'out') {
					this.children('i:first').removeClass().addClass('icon-stop').addClass('icon-white');
				}
			}
			else {
				this.attr('data-status','stopped');
				this.children('i:first').removeClass().addClass('icon-stop').addClass('icon-white');			
			}				
		};
		
	})( jQuery );
	
	/* [ BTN - FRIEND:HOVER ] */
	$('.user-friend').hover(	  
		function () { $(this).btnLoadUserFriend('hover'); }, 
		function () { $(this).btnLoadUserFriend(); }
	);
	
	/* [ BTN - FRIEND:CLICK ] */
	$('.user-friend').click(function(e) 
	{
		var self = $(this);
		var fid = self.attr('data-user-id');
		var fstatus = self.attr('data-friend');
		
		if(fstatus == 'yes') {
			$.getJSON('/ajax/user_remove_friend', {fid:fid}, function(data) { 
				self.attr('data-friend','no');
				self.btnLoadUserFriend('no');						
			});
		}
		else if(fstatus == 'no') {
			$.getJSON('/ajax/user_add_friend', { fid:fid }, function(data) {	
				self.attr('data-friend','pending');
				self.btnLoadUserFriend('pending');						
			});
		}	
		else if(fstatus == 'pending') {
			$.getJSON('/ajax/user_undo_request', { fid:fid }, function(data) { 
				self.attr('data-friend','no');
				self.btnLoadUserFriend('no');						
			});
		}
	});		
		

	/* LOCKING PLAYLIST ACTIONS AND ANIMATIONS */
	$('.playlist-lock').click(function() {
		var item = $(this);
		var pid = item.attr('data-playlist-id');
		var islocked = (item.attr('data-locked') == 'yes');
		
		if(islocked) {
			$.getJSON('/ajax/playlist_unlock', { pid:pid}, function(data) {	
				item.btnSetUnlocked();
			});
		}
		else {
			$.getJSON('/ajax/playlist_lock', { pid:pid }, function(data) {	
				item.btnSetLocked();
			});			
		}		
	});		
	$(".playlist-lock").hover(	  
		function () {
			var item = $(this);
			var islocked = (item.attr('data-locked') == 'yes');

			if(islocked) {
				item.btnSetLocked('in');
			}
			else {
				item.btnSetUnlocked('in');
			}		
		}, 
		function () {
			var item = $(this);
			var islocked = (item.attr('data-locked') == 'yes');

			if(islocked) {
				item.btnSetLocked('out');
			}
			else {
				item.btnSetUnlocked('out');
			}		
		}
	);
		
	/* FOLLOWING PLAYLIST ACTIONS AND ANIMATIONS */
	$('.follow-playlist').live('click', function() 
	{
		var item = $(this);	
		var pid = item.attr('data-playlist-id');
		var isfollowing = (item.attr('data-following') == 'yes');
	
		if(isfollowing) {
			$.getJSON('/ajax/playlist_unfollow/', { pid:pid }, function(data) {	
				item.btnSetFollow();
			});
		}
		else {
			$.getJSON('/ajax/playlist_follow/', { pid:pid }, function(data) {	
				item.btnSetFollowing();
			});
		}	
	});
	$(".follow-playlist").hover(	  
		function () {
			var item = $(this);
			var isfollowing = (item.attr('data-following') == 'yes');
			
			if(isfollowing) {
				item.btnSetUnfollow('in');
			}
			else {
				item.btnSetFollow('in');
			}
		}, 
		function () {
			var item = $(this);
			var isfollowing = (item.attr('data-following') == 'yes');
			
			if(isfollowing) {
				item.btnSetUnfollow('out');
			}
			else {
				item.btnSetFollow('out');
			}
		}
	);
	
	/* LOCKING PLAYLIST ACTIONS AND ANIMATIONS - Elements are generated with handlebars.js - so binding it to the body with .on events*/
	$('body').on('click','.playlist-privilege', function() {
        console.log('henk');
		var item = $(this);
		var pid = item.attr('data-playlist-id');
		var uid = item.attr('data-user-id');
		var isviewer = (item.attr('data-privilege') == 'viewer');
		var iseditor = (item.attr('data-privilege') == 'editor');
		
		if(isviewer) {
			$.getJSON('/ajax/set_playlist_editor', { uid:uid, pid:pid}, function(data) {	
				item.btnSetEditor();
			});
		}
		else if(iseditor) {
			$.getJSON('/ajax/set_playlist_viewer', { uid:uid, pid:pid }, function(data) {	
				item.btnSetViewer();
			});			
		}		
	});		
	
	$('body').on('hover','.playlist-privilege', function(e) {
		if(e.type == 'mouseenter') {
			var item = $(this);
			var isviewer = (item.attr('data-privilege') == 'viewer');
			var iseditor = (item.attr('data-privilege') == 'editor');
			if(isviewer) {
				item.btnSetViewer('in');
			}
			else if(iseditor) {
				item.btnSetEditor('in');
			}		
		}
		else if(e.type == 'mouseleave') {
			var item = $(this);
			var isviewer = (item.attr('data-privilege') == 'viewer');
			var iseditor = (item.attr('data-privilege') == 'editor');
			if(isviewer) {
				item.btnSetViewer('out');
			}
			else if(iseditor) {
				item.btnSetEditor('out');
			}		
		}
	});

});




