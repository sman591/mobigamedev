<?php

class jquery_file_form {
	
	public function __construct($form_id = 'fileupload') {
		
		$this->form_id = $form_id;
		
	}
	
	public function build_upload_form() {
		
		return '<!-- The file upload form used as target for the file upload widget -->
		<form id="'.$this->form_id.'" action="/" method="POST" enctype="multipart/form-data" class="form-inline">
		    <!-- Redirect browsers with JavaScript disabled to the origin page -->
		    <noscript><input type="hidden" name="redirect" value="http://blueimp.github.com/jQuery-File-Upload/"></noscript>
		    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
		    <div class="row-fluid fileupload-buttonbar">
		        <div class="span7">
		            <!-- The fileinput-button span is used to style the file input field as button -->
		            <span class="btn btn-success fileinput-button">
		                <i class="icon-plus icon-white"></i>
		                <span>Add files...</span>
		                <input type="file" name="files[]" multiple>
		            </span>
		            &nbsp;
		            <button type="submit" class="btn btn-primary start">
		                <i class="icon-upload icon-white"></i>
		                <span>Start upload</span>
		            </button>
		            &nbsp;
		            <button type="reset" class="btn btn-warning cancel">
		                <i class="icon-ban-circle icon-white"></i>
		                <span>Cancel upload</span>
		            </button>
		            &nbsp;
		            <button type="button" class="btn btn-danger delete">
		                <i class="icon-trash icon-white"></i>
		                <span>Delete</span>
		            </button>
		            &nbsp;
					<input type="checkbox" class="toggle">
		        </div>
		        <!-- The global progress information -->
		        <div class="span5 fileupload-progress fade">
		            <!-- The global progress bar -->
		            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
		                <div class="bar" style="width:0%;"></div>
		            </div>
		            <!-- The extended global progress information -->
		            <div class="progress-extended">&nbsp;</div>
		        </div>
		    </div>
		    <!-- The loading indicator is shown during file processing -->
		    <div class="fileupload-loading"></div>
		    
		   
		    <!-- Dropzone -->
		    <div class="row-fluid">
				<div class="dropzone fade well span12">Drop files here to upload</div>
			</div>
		    
		    <!-- The table listing the files available for upload/download -->
		    <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
		</form>';
		
	}
	
	
	public function jquery_init() {
		
		return "$(function () {
		
		    // Initialize the jQuery File Upload widget:
		    $('#".$this->form_id."').fileupload({
		        // Uncomment the following to send cross-domain cookies:
		        //xhrFields: {withCredentials: true},
		        url: '/resources/jQuery-File-Upload-master/server/php/',
		        dropZone: $('#".$this->form_id." .dropzone')
		    });
		
		    // Enable iframe cross-domain access via redirect option:
		    $('#".$this->form_id."').fileupload(
		        'option',
		        'redirect',
		        window.location.href.replace(
		            /\/[^\/]*$/,
		            '/resources/jQuery-File-Upload-master/cors/result.html?%s'
		        )
		    );
		
		});
		
		/* Loads list of existing files
			$.ajax({
			        // Uncomment the following to send cross-domain cookies:
			        //xhrFields: {withCredentials: true},
			        url: $('#".$this->form_id."').fileupload('option', 'url'),
			        dataType: 'json',
			        context: $('#".$this->form_id."')[0]
			    }).done(function (result) {
			        $(this).fileupload('option', 'done')
			            .call(this, null, {result: result});
			    });
		*/
		
		$(document).bind('dragover', function (e) {
		    var dropZone = $('#".$this->form_id." .dropzone'),
		        timeout = window.dropZoneTimeout;
		    if (!timeout) {
		        dropZone.addClass('in');
		    } else {
		        clearTimeout(timeout);
		    }
		    if (e.target === dropZone[0]) {
		        dropZone.addClass('hover');
		    } else {
		        dropZone.removeClass('hover');
		    }
		    window.dropZoneTimeout = setTimeout(function () {
		        window.dropZoneTimeout = null;
		        dropZone.removeClass('in hover');
		    }, 100);
		});";
		
	}
	
}	