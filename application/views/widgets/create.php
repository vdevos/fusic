<div class="account-container register">	
	<div class="content clearfix">		
		<form action="/user/create/" method="post">
		
			<h1>Create Your Account</h1>			
			
			<div class="login-social">
				<p>Sign in using one of your social networks</p>
				
				<div class="twitter">
					<a href="#" class="btn_1">Login with Twitter</a>				
				</div>
				
				<div class="fb">
					<a href="#" class="btn_2">Login with Facebook</a>				
				</div>
			</div>
			
			<div class="login-fields">				
				<p>Create your Fusic account:</p>				
				<div class="field">
					<label for="username">Username:</label>
					<input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="Username" class="login">
				</div> <!-- /field -->
				
				
				<div class="field">
					<label for="email">Email Address:</label>
					<input type="text" id="email" name="email" autocomplete="off" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Email" class="login">
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<input type="password" id="password" name="password" value="" placeholder="Password" class="login">
				</div> <!-- /field -->
				
				<div class="field">
					<label for="confirm_password">Confirm Password:</label>
					<input type="password" id="confirm_password" name="confirm_password" value="" placeholder="Confirm Password" class="login">
				</div> <!-- /field -->
			</div> <!-- /login-fields -->
			
			<?php if(isset($error)) { ?>
				<div class="login-messages">
					<div class="field">
						<div class="alert alert-error login-error">
							<?php echo $error; ?>
						</div>					
					</div>	
				</div>
			<?php } ?>
			
			<div class="login-actions">
				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4">
					<label class="choice" for="Field">I have read and agree with the Terms of Use.</label>
				</span>
				<button type="submite" class="button btn btn-primary btn-large">Register</button>
			</div> <!-- .actions -->		
			
		</form>
	</div> <!-- /content -->
</div>