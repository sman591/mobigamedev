<?php $user->admin_check('page_head'); ?>
<div class="span12">
<div class="alert alert-info"><strong><i class="icon-lock" style="margin: 2px 2px 0 0;"></i> &nbsp;Users with this lock sign cannot be deleted or demoted to non-admin status. To lock/unlock a user, <a href="mailto:solivera5@me.com" style="color:#176694">contact Stuart</a>.</strong>
</div>
</div>
</div>
<div class="row-fluid">
<div class="span12">
<form method="post" action="javascript:userSave();">
<table class="table table-striped users-admin">  
  
    <!-- Table header -->  
  
        <thead>  
            <tr>  
                <th scope="col">First Name</th>  
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
                <th scope="col" style="width: 115px;">Admin</th>
                <th scope="col" style="width: 42px;">Delete</th>
            </tr>  
        </thead>
  
    <!-- Table body -->  
  
        <tbody>  
        <? $userdb = mysql_query("SELECT * FROM users ORDER BY `name_last` ASC");
		while( $u = mysql_fetch_array( $userdb ) ){ 
		$i++;
		
		$name_safe = $u['name_first'].' '.$u['name_last'];
		
		if (trim($name_safe)=='') {
			$name_safe = $u['email'];
		}
		
		if (trim($name_safe)=='') {
			$name_safe = $u['id'];
		}
		
		$name_safe = trim(addslashes(stripslashes($name_safe)));
		
		?>
		<tr>
			<input type="hidden" name="userid<? echo $i; ?>" value="<? echo $u['id'] ?>" />
			<td><input type="text" name="name_first<? echo $i; ?>" value="<? echo stripslashes($u['name_first']); ?>" class="text" /></td>
			<td><input type="text" name="name_last<? echo $i; ?>" value="<? echo stripslashes($u['name_last']); ?>" class="text"/></td>
			<td><input type="text" name="email<? echo $i; ?>" value="<? echo stripslashes($u['email']) ?>" class="text email"/></td>
			
			<td>
				<a href="javascript:userPassword('edit', '<? echo $i;?>', '<? echo $name_safe; ?>')" class="btn btn-info"><i class="icon-pencil icon-white"></i>&nbsp; Change Password</a>
				<input type="hidden" name="userPassword<? echo $i; ?>" value="">
			</td>
			
			<td>
				<? if ($u['locked']=='0') {?>
					<div class="btn-group btn-toggle-notice">
						<label for="role<? echo $i; ?>yes" class="btn btn-toggle"><input type="radio" name="role<? echo $i; ?>" id="role<? echo $i; ?>yes" value="admin" <? if ($u['role']=='admin'){ echo 'checked="checked" class="orig_check"'; }?>/><i class="icon-ok"></i></label>
						<label for="role<? echo $i; ?>no" class="btn btn-toggle"><input type="radio" name="role<? echo $i; ?>" id="role<? echo $i; ?>no" value="user" <? if ($u['role']=='user'){ echo 'checked="checked" class="orig_check"'; }?> /><i class="icon-remove"></i></label>
					</div>
				<? } else { ?>
					<a class="btn disabled"><i class="icon-lock"></i></a>
				<? } ?>
			</td>
			
			<td><? if ($u['locked']=='0') {?><a href="javascript:modal('Delete User', 'userDelete', '<? echo $u['id']; ?>', ['<? echo $u['id'];?>', '<? echo $name_safe; ?>'])" class="btn btn-danger"><i class="icon-remove icon-white"></i></a>
				<? } else { ?>
					<a class="btn disabled"><i class="icon-lock"></i></a>
				<? } ?>
			</td>
		</tr><? } ?> 
		<? $new_id = $i+1; ?>
		<tr id="newUser" style="display: none">
			<input type="hidden" name="useridNew" value="<? echo $new_id; ?>" />
			<td><input type="text" name="name_first<? echo $new_id; ?>" value="" class="text" placeholder="First Name" /></td>
			<td><input type="text" name="name_last<? echo $new_id; ?>" value="" class="text" placeholder="Last Name" /></td>
			<td><input type="text" name="email<? echo $new_id; ?>" value="" class="text email" placeholder="Email" /></td>
			
			<td><input type="text" name="newUserPassword<? echo $new_id; ?>" value="" class="text" placeholder="Password" /></td>
			
			<td>
				<div class="btn-group btn-toggle-notice">
					<label for="role<? echo $new_id; ?>yes" class="btn btn-toggle"><input type="radio" name="role<? echo $new_id; ?>" id="role<? echo $new_id; ?>yes" value="admin" /><i class="icon-ok"></i></label>
					<label for="role<? echo $new_id; ?>no" class="btn btn-toggle active"><input type="radio" name="role<? echo $new_id; ?>" id="role<? echo $new_id; ?>no" value="user" checked="checked" /><i class="icon-remove"></i></label>
				</div>
			</td>
			
			<td>
				<a href="javascript:newUser('hide')" class="btn btn-danger"><i class="icon-remove icon-white"></i></a>
			</td>
		</tr>
        </tbody>  
  
</table> 
<input type="hidden" name="userMax" value="<? echo $i; ?>">
<input type="hidden" name="newId" value="no">
<input type="hidden" name="returnurl" value="../<? echo rtrim(ltrim($_SERVER['PHP_SELF'], " /"), ".php") ?>">

<div class="form-actions">
<input type="submit" name="userstable" value="Save" class="btn btn-primary" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:newUser('show')" class="btn btn-info" id="newUserButton"><i class="icon-plus icon-white"></i> Add New User</a>
</div>

</form>
</div><!--.span12-->

<div id="dialog-user-roles" title="User roles">
	<div class="ui-state-error ui-corner-all" style="padding: 8px;">Sorry, an error occurred (no content changed).</div>
</div>
<script>
function custom_pageLoad() {
	$( "#dialog-user-roles" ).dialog({
		show: {effect: "fade", duration: 300},
		hide: {effect: "fade", duration: 150},
		autoOpen: false,
		height: 300,
		width: 500,
		modal: true,
		buttons: {
			"Okay": function() {
				var userid = $('#dialog-user-roles input[name=userid]').val();
				userRoles(userid, 'save');
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			$(this).html('');
		}
	});
}
</script>