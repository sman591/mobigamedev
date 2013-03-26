<?php $user->is_logged_in(); ?>
<div class="page-header">
<h1>Upload</h1>
</div>

<? $form = new jquery_file_form('upload_form');

echo $form->build_upload_form();

?>

<script type="text/javascript">
function custom_pageLoad() {
	
	<? echo $form->jquery_init(); ?>
	
}
</script>