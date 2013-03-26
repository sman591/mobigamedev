<form class="form-signin" action="/user/auth.php" method="post">

	<?php
	
	if ($this->get_url_query('login_error')) {
		
		echo '<div class="alert">';
	
		switch ($this->get_url_query('login_error')) {
			
			case 'login':
			
				echo '<strong>Sign In Required</strong>';
			
			break;
			
			case 'incorrect':
			
				echo '<strong>Incorrect Login!</strong><br />Your email and/or password were incorrect.';
			
			break;
			
			case 'verify_fail':
			
				echo '<strong>Authentication Failed</strong><br />Your session has either expired, or another account activity has reset your login (such as changing your password). Please Sign In again.';
			
			break;
			
			default:
			
				echo '<strong>Sign In Error</strong>';
			
			break;
	
		}
	
		echo '</div>';
		
	} ?>

	<h2 class="form-signin-heading">Sign In</h2>
	<input type="text" name="email" class="input-block-level" placeholder="Email address">
	<input type="password" name="password" class="input-block-level" placeholder="Password">
	<!--
<label class="checkbox">
		<input type="checkbox" value="remember-me"> Remember me
	</label>
-->
	<button class="btn btn-large btn-primary" type="submit">Sign in</button>
	
	<hr>
	<a href="http://<? echo URL; ?>" class="btn"><i class="icon-arrow-left"></i>&nbsp;&nbsp;Back to <? echo SITE_NAME; ?></a>
	
</form>
      
<script type="text/javascript">
function custom_pageLoad() {
	
}
</script>