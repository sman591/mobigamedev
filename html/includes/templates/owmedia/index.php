<div class="span12">
  <div class="hero-unit">
    <h1>Welcome to oWeb Media!</h1>
    <? if (!$user->is_logged_in(true)) { ?>
	    <h2>This is a private sharing service. Please login to continue.</h2>
	    <br />
	    <form action="/user/auth.php" class="form-inline" method="post">
			<input type="text" name="email" value="" placeholder="Email" />
			<input type="password" name="password" value="" placeholder="Password" />
			<input type="submit" name="submit" value="Sign In" class="btn" />
		</form>
	<? }
	else { ?>
		<h2>You are logged in. <a href="/user/auth.php?logout=1">Logout &raquo;</a></h2>
	<? } ?>
  </div>
</div><!--/span-->
<script type="text/javascript">
function custom_pageLoad() {
	
}
</script>