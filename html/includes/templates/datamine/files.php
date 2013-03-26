<?php $user->is_logged_in(); ?>

<div class="page-header" style="margin-bottom: 0">
<h1>Files</h1>
</div>

<? $upload_id = 'upload_form'; ?>

<!-- The file upload form used as target for the file upload widget -->
<form id="<? echo $upload_id; ?>" action="/" method="POST" enctype="multipart/form-data" class="form-inline">
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
    <!--
<div class="row-fluid">
		<div class="dropzone fade well span12">Drop files here to upload</div>
	</div>
-->
    
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
</form>


<div class="dropzone-surround" id="<? echo $upload_id; ?>_dropzone">
	<div class="drop-notice">Drop Files Here</div>
	<div id="songfiles_inline_content"><i class="loader"></i></div>
</div>

<script>
songFiles('Public Files', 'public', 'inline');
</script>


<script type="text/javascript">
function custom_pageLoad() {
	
	$(function () {
		
	    // Initialize the jQuery File Upload widget:
	    $('#<? echo $upload_id; ?>').fileupload({
	        // Uncomment the following to send cross-domain cookies:
	        //xhrFields: {withCredentials: true},
	        url: '/resources/jQuery-File-Upload-master/server/php/',
	        dropZone: $('#<? echo $upload_id; ?>_dropzone'),
	        done: function (e, data) {
                var that = $(this).data('blueimp-fileupload') ||
                        $(this).data('fileupload'),
                    files = that._getFilesFromResponse(data),
                    template,
                    deferred;
                if (data.context) {
                    data.context.each(function (index) {
                        var file = files[index] ||
                                {error: 'Empty file upload result'},
                            deferred = that._addFinishedDeferreds();
                        if (file.error) {
                            that._adjustMaxNumberOfFiles(1);
                        }
                        
						if (that._hasError(file)) {                        
						
							that._transition($(this)).done(
	                            function () {
	                                var node = $(this);
	                                template = that._renderDownload([file])
	                                    .replaceAll(node);
	                                that._forceReflow(template);
	                                that._transition(template).done(
	                                    function () {
	                                        data.context = $(this);
	                                        that._trigger('completed', e, data);
	                                        that._trigger('finished', e, data);
	                                        deferred.resolve();
	                                    }
	                                );
	                            }
	                        );
							
						}
						else {
							
							$(this).children('td').children().css('display', 'block').animate({
								height: 'toggle'
								}, 500, function() {
									$(this).parent().parent().remove();
									songFiles('Public Files', 'public', 'inline');
							});
							
						}
						
                        
                    });
                } else {
                    if (files.length) {
                        $.each(files, function (index, file) {
                            if (data.maxNumberOfFilesAdjusted && file.error) {
                                that._adjustMaxNumberOfFiles(1);
                            } else if (!data.maxNumberOfFilesAdjusted &&
                                    !file.error) {
                                that._adjustMaxNumberOfFiles(-1);
                            }
                        });
                        data.maxNumberOfFilesAdjusted = true;
                    }
                    template = that._renderDownload(files)
                        .appendTo(that.options.filesContainer);
                    that._forceReflow(template);
                    deferred = that._addFinishedDeferreds();
                    that._transition(template).done(
                        function () {
                            data.context = $(this);
                            that._trigger('completed', e, data);
                            that._trigger('finished', e, data);
                            deferred.resolve();
                        }
                    );
                }
            }
	    });
	
	    // Enable iframe cross-domain access via redirect option:
	    $('#<? echo $upload_id; ?>').fileupload(
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
		        url: $('#<? echo $upload_id; ?>').fileupload('option', 'url'),
		        dataType: 'json',
		        context: $('#<? echo $upload_id; ?>')[0]
		    }).done(function (result) {
		        $(this).fileupload('option', 'done')
		            .call(this, null, {result: result});
		    });
	*/
	
	$(document).bind('dragover', function (e) {
	    var dropZone = $('#<? echo $upload_id; ?>_dropzone'),
	        timeout = window.dropZoneTimeout;
	    if (!timeout) {
	        dropZone.addClass('in');
	    } else {
	        clearTimeout(timeout);
	    }
	    if ($(e.target).parents('#<? echo $upload_id; ?>_dropzone').length==1) {
	        dropZone.addClass('hover');
	    } else {
	        dropZone.removeClass('hover');
	    }
	    window.dropZoneTimeout = setTimeout(function () {
	        window.dropZoneTimeout = null;
	        dropZone.removeClass('in hover');
	    }, 100);
	});
	
}
</script>