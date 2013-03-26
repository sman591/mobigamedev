<?php if (!$user->details('id')) {
	echo "<center><br /><h2>You must be logged in to view this page!</h2></center>";
	exit;
} ?>

<div class="page-header">
<h1>Your Account</h1>
</div>

<form method="post" action="javascript:saveSettings()" class="form-horizontal sdband_account">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="name_first">First Name</label>
			<div class="controls">
				<input type="text" class="text" name="name_first" id="name_first" value="<? echo $user->details('name_first'); ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="name_last">Last Name</label>
			<div class="controls">
				<input type="text" class="text" name="name_last" id="name_last" value="<? echo $user->details('name_last'); ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">Email</label>
			<div class="controls">
				<input type="text" class="text" name="email" id="email" value="<? echo $user->details('email'); ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="changePass">Change Password</label>
			<div class="controls">
				<input type="checkbox" name="changePass" id="changePass" value="yes" onclick="$('#changePassword').toggle()"/>
			</div>
		</div>
		<div id="changePassword" style="display: none;">
			<div class="control-group">
				<label class="control-label" for="cpass">Current Password</label>
				<div class="controls">
					<input type="password" class="text" name="cpass" id="cpass" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pass1">New Password</label>
				<div class="controls">
					<input type="password" class="text" name="pass1" id="pass1" value="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pass2">New Password (again)</label>
				<div class="controls">
					<input type="password" class="text" name="pass2" id="pass2" value="">
				</div>
			</div>
		</div>
		<input type="hidden" name="id" value="<? echo $user->details('id'); ?>" />
		<div class="form-actions">
			<input type="submit" name="saveSettings" class="btn btn-primary" value="Save" />
		</div>
	</fieldset>
</form>
<script>
function saveSettings() {

	var modalid	= '#globalModal';
    
	modal('Saving', 'save', 'save', {'modalid':modalid}, function() {
	
	    modal_handler('loader','show', modalid);
				
		var action = '/owcms/admin/user/save.php';

		ow_ajax(action, { 
			saveSettings: 'true',
			name_first: $('input[name=name_first]').val(),
			name_last: $('input[name=name_last]').val(),
			email: $('input[name=email]').val(),
			changePass: $('input:checkbox[name=changePass]:checked').val(),
			cpass: $('input[name=cpass]').val(),
			pass1: $('input[name=pass1]').val(),
			pass2: $('input[name=pass2]').val(),
			id: $('input[name=id]').val()
		},
		function(data){
				
			$(modalid).modal('hide');
			
		},function(data){}, {'modalid':modalid});
		
	});
	
}
</script>
<script type="text/javascript">
function custom_pageLoad() {
	
}
</script>