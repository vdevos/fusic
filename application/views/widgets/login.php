<div class="account-container">
	<div class="content clearfix">
		<form action="/user/login" method="post">
				<h1>Sign in to Fusic</h1>	
				<div class="content-wrapper span3">	
				
					<div class="login-fields">
						<p>Using your account</p>						
						<div class="field">
							<label for="username">Username:</label>
							<input type="text" id="username" name="username" value="" placeholder="Username" class="login username-field">
						</div> <!-- /field -->						
						<div class="field">
							<label for="password">Password:</label>
							<input type="password" id="password" name="password" value="" placeholder="Password" class="login password-field">
						</div> <!-- /password -->				
						<?php if(isset($error)) { ?>
							<div class="field">
								<div class="login-alert alert alert-error"><?php echo $error; ?></div>
							</div>
						<?php } ?>					
					</div> <!-- /login-fields -->
					
					<div class="login-actions">	
						<!--
						<span class="login-checkbox">
							<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4">
							<label class="choice" for="Field">Keep me signed in</label>
						</span>-->					
						<button type="submit" class="button btn btn-warning btn-large">Sign In</button>
						<button id="btn-create" type="button" onclick="location.href='/user/create'" class="button btn btn-large">Create</button>
					</div> <!-- .actions -->
				</div>
				
				<div class="login-social span2">
					<p>Using your social network</p>
					<div class="fb">
						<a href="#" class="btn_2">Login with Facebook</a>				
					</div>
					<div class="twitter">
						<a href="#" class="btn_1">Login with Twitter</a>				
					</div>		
				</div>
		</form>
	</div> <!-- /content -->
</div>