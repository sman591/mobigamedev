<div class="row-fluid">

	<? if ($this->get_url_query('action')=='edit') { ?>
			
		<div class="span12">
		
			<div class="page-header">
				<a class="btn btn-small pull-right toggle-all" href="<? echo ADMIN_LOCATION.'#'.ADMIN_LOCATION; ?>pages/"><i class="icon-arrow-left"></i>&nbsp;&nbsp;Back to Pages</a>
				<h3>Edit Page</h3>
			</div>
			
			<? $page = new owcms_page('id:'.$this->get_url_query('id'), true);
		
			if (!$page->page_exists) { ?>
			
				<div class="alert alert-error">
					Page not found!
				</div>
			
			<? }
			else { ?>
			
				<form action="/owcms/admin/resources/save/page.php" method="post" class="form-horizontal">
				
					<input type="hidden" name="id" value="<? echo $page->details('id'); ?>">
				
					<div class="control-group">
						<label class="control-label" for="name">Title</label>
						<div class="controls">
							<input type="text" id="name" name="name" value="<? echo $page->details('name'); ?>" placeholder="My Page" class="input-large">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="subtitle">Subtitle</label>
						<div class="controls">
							<input type="text" id="subtitle" name="subtitle" value="<? echo $page->details('subtitle'); ?>" placeholder="My Page" class="input-large">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="slug">Slug</label>
						<div class="controls">
							<input type="text" id="slug" name="slug" value="<? echo $page->details('slug'); ?>" placeholder="mypage" class="input">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="order">Order</label>
						<div class="controls">
							<input type="text" id="order" name="order" value="<? echo $page->details('order'); ?>" placeholder="mypage" class="input">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="position">Parent</label>
						<div class="controls">
							<select name="position">
								<option value="0">(no parent)</option>
								<? 
								
								function print_option($item, $depth, $selected_id) {
									
									$page = new owcms_page('id:'.$item['id'], true);
								
									echo '<option value="'.$page->details('id').'"'.($page->details('id')==$selected_id ? ' selected="selected"' : '').'>';
									
									$pre_spacing = '';
										
									if ($depth > 0) {
									
										for ($i = 0; $i < $depth; $i++) {
											
											$pre_spacing .= '&nbsp;&nbsp;&nbsp;&nbsp;';
											
										}
										
										$pre_spacing .= '&#8970;&nbsp;&nbsp;';
									
									}
									
									echo $pre_spacing.$page->details('name');
									
									echo '</option>';
									
								}
								
								$this->getMenu('print_option', null, 0, -1, $page->details('position')); ?>
								
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="template">Template</label>
						<div class="controls">
							<input type="text" id="template" name="template" value="<? echo $page->details('template'); ?>" placeholder="page" class="input">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="content">Content</label>
						<div class="controls">
							<textarea id="content" name="content" class="mceEditor span12" rows="25"><? echo stripslashes($page->details('content')); ?></textarea>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="header">Custom Header</label>
						<div class="controls">
							<textarea id="header" name="header" class="span12" rows="10"><? echo $page->details('header'); ?></textarea>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="hasdropdown">Show Child Pages</label>
						<div class="controls">
							<select id="hasdropdown" name="hasdropdown">
								<option value="0">Disabled</option>
								<option value="1"<? echo ($page->details('hasdropdown')=='1' ? ' selected="selected"' : ''); ?>>Enabled</option>
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="enabled">Enabled</label>
						<div class="controls">
							<select id="enabled" name="enabled">
								<option value="0">Disabled</option>
								<option value="1"<? echo ($page->details('enabled')=='1' ? ' selected="selected"' : ''); ?>>Enabled</option>
							</select>
						</div>
					</div>
					
					<div class="form-actions">
					
						<button type="submit" class="btn btn-primary">Save Page</button>
					
					</div>
				
				</form>
			
			<? } ?>
		
		</div>
	
	<? } else if ($this->get_url_query('action')=='delete') { ?>
	
		<div class="span12">
		
			<div class="page-header">
				<a class="btn btn-small pull-right toggle-all" href="<? echo ADMIN_LOCATION.'#'.ADMIN_LOCATION; ?>pages/"><i class="icon-arrow-left"></i>&nbsp;&nbsp;Back to Pages</a>
				<h3>Delete Page</h3>
			</div>
			
			<? $page = new owcms_page('id:'.$this->get_url_query('id'), true);
		
			if (!$page->page_exists) { ?>
			
				<div class="alert alert-error">
					Page not found!
				</div>
			
			<? }
			else { ?>
			
				<form action="/owcms/admin/resources/save/page.php" method="post">
					
					<input type="hidden" name="id" value="<? echo $page->details('id'); ?>">
					<input type="hidden" name="action" value="delete">
					
					<div class="alert">Make sure all child pages are deleted or assigned to different parents before deleting this page</div>
					
					<p class="text-error">You are about to delete the following page:</p>
					
					<br>
					
					<code>ID: <? echo $page->details('id'); ?>, "<? echo $page->details('name'); ?>"</code>
					
					<br>
					
					<hr>
						
					<a href="<? echo ADMIN_LOCATION.'#'.ADMIN_LOCATION; ?>pages/" class="btn">Cancel</a>&nbsp;&nbsp;<button type="submit" class="btn btn-danger"><i class="icon-white icon-trash"></i>&nbsp; Delete</button>
						
				</form>
			
			<? } ?>
		
		</div>
	
	<? } else { ?>

	<div class="span3">
		
		<div class="page-header">
			<h3>Add Page</h3>
		</div>
		
		<form action="/owcms/admin/resources/save/page.php" method="post">
		
			<input type="hidden" name="id" value="new">
		
			<fieldset>
			
				<label>Title</label>
				<input type="text" name="name" placeholder="Example Page">
				
				<br>
				
				<label>Slug</label>
				<input type="text" name="slug" placeholder="example">
				
				<br>
				
				<label>Parent</label>
				<select name="parent">
					<option value="0">(no parent)</option>
					<? 
					
					function print_option($item, $depth) {
						
						$page = new owcms_page('id:'.$item['id'], true);
					
						echo '<option value="'.$page->details('id').'">';
						
						$pre_spacing = '';
							
						if ($depth > 0) {
						
							for ($i = 0; $i < $depth; $i++) {
								
								$pre_spacing .= '&nbsp;&nbsp;&nbsp;&nbsp;';
								
							}
							
							$pre_spacing .= '&#8970;&nbsp;&nbsp;';
						
						}
						
						echo $pre_spacing.$page->details('name');
						
						echo '</option>';
						
					}
					
					$this->getMenu('print_option'); ?>
					
				</select>
				
				<label>Order</label>
				<input type="text" name="order" placeholder="1" class="input-mini" maxlength="2">
				
				<br>
				
				<label>Template</label>
				<input type="text" name="template" placeholder="(leave blank for normal page)">
				
				<br>
				
				<br>
				
				<label class="control-label" for="hasdropdown">Show Child Pages</label>
				<select id="hasdropdown" name="hasdropdown">
					<option value="0">Disabled</option>
					<option value="1" selected="selected">Enabled</option>
				</select>
				
				<label class="control-label" for="enabled">Enabled</label>
				<select id="enabled" name="enabled">
					<option value="0">Disabled</option>
					<option value="1" selected="selected">Enabled</option>
				</select>

				<hr>
				<button type="submit" class="btn btn-primary"><i class="icon-white icon-plus"></i>&nbsp;Add Page</button>
				
			</fieldset>
		</form>
		
	</div>
	<div class="span9">
		
		<div class="page-header">
			<h3>Current Pages</h3>
		</div>
		
		<?php echo stripslashes( $this->content() ); ?>
		
		<form method="post">
		
			<input type="hidden" name="submit_action" value="page_save_order">
		
			<table class="table table-striped" id="page_list">
				<thead>
					<tr>
						<th>Order</th>
						<th>Name</th>
						<th>Slug</th>
						<th>Template</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<? 
					
					function actions($page) {
						
						return '<a href="'.ADMIN_LOCATION.'#'.ADMIN_LOCATION.'pages/?action=edit&id='.$page->details('id').'" class="btn btn-info"><i class="icon-white icon-pencil"></i></a>&nbsp;&nbsp;
								<a href="'.ADMIN_LOCATION.'#'.ADMIN_LOCATION.'pages/?action=delete&id='.$page->details('id').'" class="btn btn-danger"><i class="icon-white icon-trash"></i></a>';
						
					}
					
					function print_row($entry, $depth) {
						
						$page = new owcms_page('id:'.$entry['id'], true);
					
						echo '<tr>';
						
						// Order
						echo '<td><input type="text" name="order['.$page->details('id').']" value="'.$page->details('order').'" class="input-mini" maxlength="2" disabled="disabled"></td>';
						
						// Name
						
						$pre_spacing = '';
							
						if ($depth > 0) {
						
							for ($i = 0; $i < $depth; $i++) {
								
								$pre_spacing .= '&nbsp;&nbsp;&nbsp;&nbsp;';
								
							}
							
							$pre_spacing .= '&#8970;&nbsp;&nbsp;';
						
						}
						
						echo '<td>'.$pre_spacing.$page->details('name').'</td>';
						
						// Slug
						echo '<td>'.$page->details('slug').'</td>';
						
						// Template
						echo '<td>'.$page->details('template').'</td>';
						
						echo '<td>'.actions($page).'</td>';
						
						echo '</tr>';
						
					}
					
					$this->getMenu('print_row');
					
					?>
				</tbody>
			</table>
			
			<div class="form-actions">
				<button type="submit" class="btn btn-primary" disabled="disabled"><i class="icon-white icon-ok"></i>&nbsp;&nbsp;Save</button>
			</div>
		
		</form>
		
	</div>
	
	<? } ?>

</div>
<script type="text/javascript">
function custom_pageLoad() {

	tinymce_init();
	
}
</script>