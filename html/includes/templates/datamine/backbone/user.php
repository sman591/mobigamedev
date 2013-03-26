<script type="text/template" id="userView_template">

	<div class="page-header">
		<h1>Your Account</h1>
	</div>
	
	<form class="form-horizontal UserViewForm">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="name_first">First Name</label>
				<div class="controls">
					<input type="text" class="text" name="name_first" id="name_first" value="<%= name_first %>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="name_last">Last Name</label>
				<div class="controls">
					<input type="text" class="text" name="name_last" id="name_last" value="<%= name_last %>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="email">Email</label>
				<div class="controls">
					<input type="text" class="text" name="email" id="email" value="<%= email %>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="changePass">Change Password ** doesn't currently work</label>
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
			<div class="form-actions">
				<button type="submit" name="saveSettings" class="btn btn-primary">Save</button>
			</div>
		</fieldset>
	</form>

</script>