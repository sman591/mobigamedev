<script type="text/template" id="projectView_template">

	<div class="carousel slide full-page-carousel project-carousel">
		<!-- Carousel items -->
		<div class="carousel-inner">
			<div class="active item">
				<img src="<%= header_img %>" alt="">
				<div class="container-fluid">
				<div class="carousel-caption">
					<h1><%= title %></h1>
					<p class="lead"><%= short_desc %></p>
				</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="page">
		<div class="tile-container tile-nav tile-nav-right">
			<div class="tile btn btn-primary" data-href="contribute">
				<div class="tile-content">contribute</div>
			</div>
			<div class="tile btn btn-inverse" data-href="edit">
				<div class="tile-content">edit</div>
			</div>
		</div>
		<div class="tile-container tile-nav">
			<div class="tile btn btn-info" data-href="overview">
				<div class="tile-content">overview</div>
			</div>
			<div class="tile btn btn-success" data-href="data">
				<div class="tile-content">data</div>
			</div>
			<div class="tile btn btn-warning" data-href="info">
				<div class="tile-content">more info</div>
			</div>
		</div>
		<div class="tab-container">
			<div id="tab-overview" class="tab-content">
				<div class="page-header">
					<h1>Overview</h1>
				</div>
				<div class="alert alert-success">This serves as a general "front cover" to the project. What it is, why it's important, what the data represents, how it can be used, etc. Should include graphics/graphs to make the project attractive to visitors.</div>
				<%= content %>
			</div>
			<div id="tab-contribute" class="tab-content">
				<div class="page-header">
					<h1>Contribute</h1>
				</div>
				<div class="alert alert-success">Form to add on your own data</div>
				<%= contribute_content %>
			</div>
			<div id="tab-data" class="tab-content">
				<div class="page-header">
					<h1>Data</h1>
				</div>
				<div class="alert alert-success">Data tables (and graphs?) go here with their descriptions</div>
				<%= data_content %>
			</div>
			<div id="tab-info" class="tab-content">
				<div class="page-header">
					<h1>More Info</h1>
				</div>
				<div class="alert alert-success">Author of project can add whatever write here. Embed graphs, give an entire background to their project, whatever.</div>
				<%= info_content %>
			</div>
		</div>
	</div>

</script>

<script type="text/template" id="projectEditView_template">
	
	<div class="page">
	
		<div class="carousel slide full-page-carousel project-carousel">
			<!-- Carousel items -->
			<div class="carousel-inner">
				<div class="active item">
					<img src="<%= header_img %>" alt="">
					<div class="container-fluid">
					<div class="carousel-caption">
						<h1><%= title %></h1>
						<p class="lead"><%= short_desc %></p>
					</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="editing-notice">
			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">

						<div class="text pull-left">
							<p><i class="icon-white icon-warning-sign"></i>&nbsp;&nbsp;Editing Project</i></p>
						</div>
						<div class="actions pull-right">
							<button class="btn btn-primary" data-href="save">Save</button>
							<div class="btn-group dropup">
								<button class="btn btn-inverse" style="margin-left: 10px;" data-href="done">Save & Exit</button>
								<button class="btn dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a data-href="edit">Exit without saving</a></li>
								</ul>
							</div>
						
						</div>

					</div>
				</div>
			</div>
		</div>
			
		<div class="tile-container tile-nav tile-nav-right">
			<div class="tile btn btn-primary" data-href="contribute">
				<div class="tile-content">contribute</div>
			</div>
			<div class="tile btn btn-inverse" data-href="done">
				<div class="tile-content">save & exit</div>
			</div>
		</div>
		<div class="tile-container tile-nav">
			<div class="tile btn btn-info" data-href="overview">
				<div class="tile-content">overview</div>
			</div>
			<div class="tile btn btn-success" data-href="data">
				<div class="tile-content">data</div>
			</div>
			<div class="tile btn btn-warning" data-href="info">
				<div class="tile-content">more info</div>
			</div>
		</div>
		<div class="tab-container">
			<form class="row-fluid">
				<div id="tab-overview" class="tab-content">
					<div class="page-header">
						<h1>Overview</h1>
					</div>
					<fieldset>
					
						<legend>Project Info</legend>
						<label for="title">Title</label>
						<input type="text" name="title" value="<%= title %>" id="title" class="span12" placeholder="My Amazing New Project">
						
						<label for="short_desc">Short Description</label>
						<textarea name="short_desc" id="short_desc" class="span12" rows="3" placeholder="This is what my project's about" maxlength="250"><%= short_desc %></textarea>
						
						<p>&nbsp;</p>
						
						<legend>Header Image</legend>
						<label for="header_img">Image File</label>
						<div class="well">
							<input type="file" name="header_img" id="header_img" class="span12">
						</div>
						<p>Current image: <a href="<%= header_img %>"><%= header_img %></a></p>
						<p>&nbsp;</p>
						
						<legend>Overview Content</legend>
						<textarea name="content" id="content" class="span12 mceEditor" style="height: 400px;" placeholder="Content for the overview (default) tab"><%= content %></textarea>
					
					</fieldset>
				</div>
				<div id="tab-contribute" class="tab-content">
					<div class="page-header">
						<h1>Contribute</h1>
					</div>
					
					<fieldset>
					
						<legend>Contribution Options</legend>
						<label for="allow_contribute">Public Contributions</label>
						<select name="allow_contribute">
							<option value="true">Allow (default)</option>
							<option value="false">Deny</option>
						</select>
						
						<p>&nbsp;</p>
					
						<legend>Contribute Content</legend>
						<textarea name="contribute_content" id="contribute_content" class="span12 mceEditor" style="height: 400px;" placeholder="Content for the contribute tab"><%= contribute_content %></textarea>
					
					</fieldset>
				</div>
				<div id="tab-data" class="tab-content">
					<div class="page-header">
						<h1>Data</h1>
					</div>
					
					<legend>Data Content</legend>
					<textarea name="data_content" id="data_content" class="span12 mceEditor" style="height: 400px;" placeholder="Content for the data tab"><%= data_content %></textarea>
					
					<p>&nbsp;</p>
						
					<div class="alert alert-success">Options to edit data go here</div>
				</div>
				<div id="tab-info" class="tab-content">
					<div class="page-header">
						<h1>More Info</h1>
					</div>
					
					<fieldset>
					
						<legend>Content</legend>
						<textarea name="info_content" id="info_content" class="span12 mceEditor" style="height: 400px;" placeholder="Content for the more info tab"><%= info_content %></textarea>
					
					</fieldset>
				</div>
			</form>
		</div>
	</div>
	
	<script type="text/javascript">
	tinyMCE.init({
	        theme : "advanced",
	        mode : "specific_textareas",
	        editor_selector : "mceEditor",
	        content_css : "/resources/bootstrap-2.3.1/css/bootstrap.min.css,/resources/cosmo-2.3.1/css/bootstrap.min.css,/resources/css/style.css",
	        plugins : "fullscreen, codemagic",
	        theme_advanced_buttons3_add : "fullscreen, codemagic",
	        fullscreen_new_window : true,
	        fullscreen_settings : {
	                theme_advanced_path_location : "top"
	        },
	        width: "100%"
	});
	
	tinyMCE.init({
	        theme : "simple",
	        mode : "specific_textareas",
	        editor_selector : "mceEditor-simple",
	        content_css : "/resources/bootstrap-2.3.1/css/bootstrap.min.css,/resources/cosmo-2.3.1/css/bootstrap.min.css,/resources/css/style.css",
	        plugins : "fullscreen, codemagic",
	        theme_advanced_buttons3_add : "fullscreen, codemagic",
	        fullscreen_new_window : true,
	        fullscreen_settings : {
	                theme_advanced_path_location : "top"
	        },
	        width: "100%"
	});
	</script>

</script>