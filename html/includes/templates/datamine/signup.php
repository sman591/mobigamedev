<form class="form-signin" action="/user/signup.php" method="post">

	<?php
	
	if ($this->get_url_query('signup_error')) {
		
		echo '<div class="alert">'.base64_decode($this->get_url_query('signup_error')).'</div>';
		
	} ?>

	<h2 class="form-signin-heading">Sign Up</h2>
	
	<? $signup_details = json_decode(base64_decode($this->get_url_query('signup_details')), true); ?>
	
	<input type="text" name="name_first" class="input-block-level" value="<? echo $signup_details['name_first']; ?>" placeholder="First Name">
	<input type="text" name="name_last" class="input-block-level" value="<? echo $signup_details['name_last']; ?>" placeholder="Last Name">
	<input type="text" name="email" class="input-block-level" value="<? echo $signup_details['email']; ?>" placeholder="Email">
	<input type="password" name="password" class="input-block-level" placeholder="Password">

	<button class="btn btn-large btn-primary" type="submit">Sign Up</button>
	
	<hr>
	<a href="http://<? echo URL; ?>" class="btn"><i class="icon-arrow-left"></i>&nbsp;&nbsp;Back to <? echo SITE_NAME; ?></a>
	
</form>
      
<script type="text/javascript">
function custom_pageLoad() {
	
}
</script>