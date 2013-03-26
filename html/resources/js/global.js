function in_array (needle, haystack, argStrict) {
  var key = '',
    strict = !! argStrict;

  if (strict) {
    for (key in haystack) {
      if (haystack[key] === needle) {
        return true;
      }
    }
  } else {
    for (key in haystack) {
      if (haystack[key] == needle) {
        return true;
      }
    }
  }

  return false;
}

function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function close() {
$(".window-overlay").delay(30).fadeOut(150);
$(".window").fadeOut(150, function() {
	resetWindow();
});
}

function current_url(num) {
	
	var path = window.location.hash.split('/');
	
	return path[num];
	
}


$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function modal(title, type, contentid, vars, callback) {

	/*	title		Title of Modal
		type		Type of Modal; chooses which function to parse the data with
		contentid	?
		vars		Custom variables for each  type  , typically get posted through  btn_save_href  and the type's function
		
		modal_***	Functions that return for the content of the modal.
		
	*/

	if (!vars) {
		vars = Array();
	}
	
	if (!callback) {
		callback = function() {};
	}

	/* Set the ID of the modal being used */
	if (vars.modalid) {
		var id = vars.modalid;
	}
	else {
		var id = '#globalModal';
	}
	
	var modal_content;
	
	var btn_close_enabled;
	var btn_close_href;
	var btn_close_color;
	var btn_close_text;
	var btn_save_enabled;
	var btn_save_href;
	var btn_save_color;
	var btn_save_text;

	/* Reset modal to original state */
	modal_handler('loader', 'hide', id);
	modal_handler('error', 'reset', id);

	/* Set the title */
	$(id+' .modal-header h3').html(title);
	
	switch (type) {
	
		case 'songfiles':
		
			modal_content		= modal_songfiles(id, contentid, vars);
			btn_save_enabled	= false;
		
		break;
		case 'songFiles_delete':
			
			modal_content 		= modal_songFiles_delete(id, contentid, vars);
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_close_enabled 	= true;
			btn_save_text		= '<i class="icon-white icon-trash"></i>&nbsp;&nbsp;Delete';
			btn_save_enabled 	= true;
			btn_save_href		= "javascript:songFiles_delete('delete', '"+vars['file']+"', '"+vars['filepath']+"')";
			btn_save_color		= "danger";
			
		break;
		case 'songFiles_addDir':
			
			modal_content 		= modal_songFiles_addDir(id, contentid, vars);
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_close_enabled 	= true;
			btn_save_text		= '<i class="icon-white icon-plus"></i>&nbsp;&nbsp;Add';
			btn_save_enabled 	= true;
			btn_save_href		= "javascript:songFiles_addDir('addDir')";
			
		break;
		case 'attend':
		
			modal_content 		= modal_attend(id, contentid, vars);
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_save_text		= '<i class="icon-ok icon-white"></i>&nbsp;&nbsp;Confirm';
			btn_save_enabled 	= true;
			btn_save_href		= "javascript:goattend('"+vars[0]+"','"+vars[1]+"','"+vars[2]+"', '"+id+"')";
			btn_save_color		= "primary";
		
		break;
		case 'save':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= false;
			btn_save_enabled 	= false;
			
		break;
		case 'attendSave':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= false;
			btn_save_enabled 	= false;
			
		break;
		case 'userSave':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= false;
			btn_save_enabled 	= false;
			
		break;
		case 'songNew':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= true;
			btn_save_enabled 	= false;
			
		break;
		case 'songSave':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= false;
			btn_save_enabled 	= false;
			
		break;
		case 'songRename':
			
			modal_content 		= modal_songRename(id, contentid, vars);
			btn_close_text		= 'Cancel';
			btn_close_enabled 	= true;
			btn_save_enabled 	= true;
			btn_save_href		= "javascript:songRename('rename', '"+vars['songid']+"', '"+vars['songName']+"')";
			
		break;
		case 'scheduleEmail':
			
			modal_content 		= modal_scheduleEmail(id, contentid, vars);
			btn_close_enabled	= true;
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_save_enabled 	= true;
			btn_save_text		= '<i class="icon-white icon-ok"></i>&nbsp;&nbsp;Okay';
			btn_save_href		= 'javascript:scheduleEmail(\'save\', \''+vars.formid+'\')';
			
		break;
		case 'scheduleSave':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= false;
			btn_save_enabled 	= false;
			
		break;
		case 'bulkSave':
			
			modal_content 		= modal_saving(id, contentid, vars);
			btn_close_enabled	= false;
			btn_save_enabled 	= false;
			
		break;
		case 'userRoles':
			
			modal_content 		= modal_userRoles(id, contentid, vars);
			btn_close_enabled	= true;
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_save_enabled 	= true;
			btn_save_text		= '<i class="icon-white icon-ok"></i>&nbsp;&nbsp;Okay';
			btn_save_href		= 'javascript:userRoles(\'save\', \''+vars.usernum+'\', \''+vars.name+'\')';
			
		break;
		case 'userPassword':
		
			modal_content 		= modal_userPassword(id, contentid, vars);
			btn_close_enabled	= true;
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_save_enabled 	= true;
			btn_save_text		= '<i class="icon-white icon-ok"></i>&nbsp;&nbsp;Change Password';
			btn_save_href		= 'javascript:userPassword(\'save\', \''+vars.usernum+'\', \''+vars.name+'\')';
		
		break;
		case 'userDelete':
		
			modal_content 		= modal_userDelete(id, contentid, vars);
			btn_close_text		= '<i class="icon-remove"></i>&nbsp;Cancel';
			btn_save_text		= '<i class="icon-white icon-trash"></i>&nbsp;&nbsp;Delete';
			btn_save_enabled 	= true;
			btn_save_href		= "javascript:userDelete('"+vars[0]+"', '"+addslashes(vars[1])+"', '"+id+"')";
			btn_save_color		= "danger";
		
		break;
		
		case 'unsupportedIE':
			
			modal_content		= modal_unsupportedIE(id, contentid, vars);
			btn_close_enabled	= true;
			btn_close_text		= 'Continue Browsing';
			btn_save_enabled	= false;
			
		break;
		
		default:
		
			modal_content 		= '<div class="alert alert-error"><h4 class="alert-heading">Invalid Modal Type</h4></div>';
			btn_close_enabled	= true;
			btn_save_enabled 	= false;
		
		break;
		
	} /* switch (type) */
	
	$(id+' .modal-body').html(modal_content);
	
	/* Handle whether or not the CLOSE btn is used, and if so, configure it */
	if (btn_close_enabled!==false) {
		
		/* Set href of button */
		modal_handler('button', 'href', id, {'button':'close', 'href':btn_close_href});
		
		/* Set color of button */
		modal_handler('button', 'color', id, {'button':'close', 'color':btn_close_color});
		
		/* Set text of button */
		modal_handler('button', 'text', id, {'button':'close', 'text':btn_close_text});
		
		/* Show Button */
		modal_handler('button', 'show', id, {'button':'close'});
		
	}
	else {
		modal_handler('button', 'hide', id, {'button':'close'});
	}
	
	/* Handle whether or not the SAVE btn is used, and if so, configure it */
	if (btn_save_enabled!==false) {
		
		/* Set href of button */
		modal_handler('button', 'href', id, {'button':'save', 'href':btn_save_href});
		
		/* Set color of button */
		modal_handler('button', 'color', id, {'button':'save', 'color':btn_save_color});
		
		/* Set text of button */
		modal_handler('button', 'text', id, {'button':'save', 'text':btn_save_text});
		
		/* Show Button */
		modal_handler('button', 'show', id, {'button':'save'});
		
	}
	else {
		modal_handler('button', 'hide', id, {'button':'save'});
	}

	/* Finally, show the modal */
	$(id).modal('show');
	
	setTimeout(function(){$(id).on('shown', callback());}, 400);

}

function modal_handler(type, act, modalid, vars) {

	var vars;

	switch (type) {
	
		case 'content':
			
			switch (act) {
			
				case 'reset':
					$(modalid+' .modal-body').html();
				break;
				case 'replace':
					$(modalid+' .modal-body').html(vars[0]);
				break;
				case 'append':
					$(modalid+' .modal-body').append(vars[0]);
				break;
				case 'prepend':
					$(modalid+' .modal-body').prepend(vars[0]);
				break;
				
			} /* switch (act) */
			
		break;
		case 'loader':
			
			switch (act) {
				case 'show':
					$(modalid+' .modal-footer').prepend('<i class="loader"></i>');
				break;
				case 'hide':
					$(modalid+' .modal-footer .loader').remove();
				break;
			} /* switch (act) */
			
		break;
		case 'error':
			
			modal_handler('loader', 'hide', modalid);
			
			if (act==='reset') {
				$(modalid+' .modal-error').css('display', 'none');
				$(modalid+' .modal-error .alert-content').html('[error details]');
			}
			else {
	            $(modalid+' .alert-content').html(act);
	            $(modalid+' .modal-error').slideDown(400);
	            modal_handler('button', 'show', modalid, {'button':'close'});
	       	}
			
		break;
		case 'button':
			
			var button_selector = modalid+' .modal-footer .modal-'+vars['button'];
		
			switch(act) {
				
				case 'show':
				
					$(button_selector).css('display', 'inline-block');
				
				break;
				case 'hide':
				
					$(button_selector).css('display', 'none');
				
				break;
				case 'color':
				
					if (!vars.color) {
					
						if (vars.button=='save') {
							vars.color = 'primary';
						}
						if (vars.button=='close') {
							vars.color = 'default';
						}
						
					}
					
					$(button_selector).attr('class', 'btn modal-'+vars.button+' btn-'+vars.color);
				
				break;
				case 'text':
				
					if (!vars.text) {
					
						if (vars.button=='save') {
							vars.text = '<i class="icon-white icon-ok"></i>&nbsp;&nbsp;Save';
						}
						if (vars.button=='close') {
							vars.text = '<i class="icon-remove"></i>&nbsp;Close';
						}
						
					}
				
					$(button_selector).html(vars.text);
				
				break;
				case 'href':
				
					if (vars.href) {
						$(button_selector).attr('href', vars.href);
					}
					else {
						$(button_selector).removeAttr('href');
					}
				
				break;
				
			}
		
		break;
	
	} /* switch (type) */

} /* modal_handler() */

function modal_saving(id, contentid, vars) {
	
	var html = '<center>Saving...<i class="loading"></i></center>';

	return html;
}

function modal_loading(id, contentid, vars) {
	
	var html = '';

	return html;
}

function modal_userPassword(id, contentid, vars) {

	var html = '<div id="userroles_modal_content"><br /><form action="javascript:void()" class="form-horizontal"><fieldset><div class="control-group"><label class="control-label" for="pass1">New Password</label><div class="controls"><input type="password" class="text" name="pass1" id="pass1" value=""></div></div><div class="control-group"><label class="control-label" for="pass2">Confirm Password</label><div class="controls"><input type="password" class="text" name="pass2" id="pass2" value=""></div></div></fieldset></form></div>';

	return html;

}

function modal_unsupportedIE(id, contentid, vars) {
	
	var html = '<center><img src="/resources/images/ie.jpg" alt="ielogo" style="width: 68px; height: 68px; "><p>We do not support Internet Explorer versions older than IE 9.</p><p>We suggest updating your version of Internet Explorer, <br /><strong>but for best results, you should upgrade below:</strong></p><a href="http://browsehappy.com/" target="_blank" class="browser_ad"><img src="/resources/images/browser_ad.jpg" /></a></p><p>Most of the site <i>should</i> still work, however, you will<br />have <strong>no access to admin pages</strong> unless you update or switch.</center>';
	
	return html;
}

function showConfirm(type, url, value) {

	switch (type) {
	
		case 'pageDelete':
			html = '<p>You are about to delete the page "'+value+'".</p>';
		break;
		
		case 'userDelete':
			html = '<p>You are about to delete the user "'+value+'".</p>';
		break;
		
		case 'custom':
			html = value;
		break;
		
		default:
			html = 'undefined event "'+type+'"<br />confirming to "'+url+'"';
		break;
	
	}
	
	$("#showConfirm_bg").fadeIn(200);
	$("#showConfirm_content").html(html+'<p>Are you sure?</p><div class="button-holder"><a href="'+url+'" class="button green">Confirm</a> <a href="javascript:closeConfirm()" class="button red">Cancel</a></div>');
	$("#showConfirm_box").fadeIn(200);
} 
/* showConfirm() */

function loading_notice(act, callback) {

	switch (act) {
	
		case 'show':
		
			$("#loader-holder").show(0, function(){
			
				$('.modal:visible').each(function(i) {
					
					modal_handler('loader', 'show', '#'+this.id);
					
				});
				
				if (callback && typeof(callback) === "function") {
					callback();
				}
				
			});
			
		break
		case 'hide':
		
			$("#loader-holder").fadeOut(300, function(){
				
				$('.modal:visible').each(function(i) {
					
					modal_handler('loader', 'hide', '#'+this.id);
					
				});
				
				if (callback && typeof(callback) === "function") {
					callback();
				}
				
			});
			
		break;
		
	}
	
}
/* loading_notice() */

function resetWindow() {
$(".window .window-change").html('');
}

function addslashes(str) {
str=str.replace(/\\/g,'\\\\');
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\0/g,'\\0');
return str;
}


function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   myfield.form.submit();
   return false;
   }
else
   return true;
}

function ow_ajax(url, data, success, error, vars) {

	/*	url*		URL to post data to
		data*		Data to post to  url
		success		Calback function upon success; returns function(json.content)
		error		Callback function upon error; returns function(json.content)
		vars		Custom vars, such as  modalid, alertid
			vars.modalid	If set, acts on a modal
			vars.alertid	If set, uses the specified element for alerts
	*/
	
	if (success==undefined) {
		error = function(){};
	}
	
	if (error==undefined) {
		error = function(){};
	}

	loading_notice('show', function() {

		if (!vars) {
			vars = Array();
		}

		if (vars.modalid) {
			modalid = vars.modalid;
			alertid = modalid+' .modal-error';
		}
		else {
			modalid = false;
			alertid = '#alert-global';
		}
		
		if (vars.alertid) {
			alertid = vars.alertid;
		}
	
		var ajaxRequest = $.ajax({
	        type: "POST",
	        url: url,
	        data: data,
	        dataType: "html",
	        timeout: 6000,
	        success: function(data) {
	        	
	        	try {
				    json = $.parseJSON(data);
				    
				    switch (json.code) {
						case 'success':
				        	success(json.content);
				        break;
				        case 'error':
				        	ow_ajax_handler('error', ['', 'Error', json.content, modalid], alertid);
				        	error(json.content);
				        break;
				        default:
			        		ow_ajax_handler('error', ['', 'Unknown error code', json.code, modalid], alertid);
			        		error(json.content);
				        break;
				   	}
				    
				} catch (e) {
				
					/* Allow for redirects */
				
					var live_data = $('<div>',{html:data});
	            	
	            	var data_text = trim($(live_data).text());
	            	
	            	if ( data_text.indexOf('REDIRECT:') === 0 ) {
		            	
		            	window.location = data_text.substring(9);
		            	
	            	}
	            	else {
				
				    	ow_ajax_handler('error', ['', 'Error parsing JSON ('+e+')', data, modalid], alertid);
				    
				    }
				}
			   	
			   	loading_notice('hide');
			   	
	        },
	        error: function(request, status, err) {
	            ow_ajax_handler('error', [request, status, err, modalid], alertid);
	            loading_notice('hide');
	        }
	    });
	    
	});
	
}
/* ow_ajax() */

function ow_ajax_handler(type, act, alert_id) {

	if (alert_id!='') {
		var alertid = alert_id;
	}
	else {
		var alertid = '#alert-global';
	}
	
	var request = act[0];
	var status 	= act[1];
	var err 	= act[2];
	var modalid = act[3];

	switch (type) {
	
		case 'error':
			
			modal_handler('loader', 'hide', modalid);
			
			$(alertid+' .alert-heading').html('Error');
			
			if (act==='reset') {
				$(alertid+' .alert-content').html('[error details]');
				$(alertid).css('display', 'none');
			}
			else {
				
				switch (status) {
					
					case 'timeout':
						
						alert_content = 'Action timed out. Please try again later.';
						
					break;
					case 'Error':
					case 'error':
					
						alert_content = err;
					
					break;
					default:
					
						alert_content = status+': '+err;
					
					break;
					
				}
				
				$(alertid+' .alert-content').html(alert_content);
				
		        $(alertid).slideDown(400);
		   	}
			
		break;
	
	} /* switch (type) */
	
}
/* ow_ajax_handler() */

function jsonDataDecode(jsonDataReturn) {
	
	var r = $.parseJSON(jsonDataReturn);
	
	return r;
	
}

function bootstrap_toggle_fix() {
	
	function updateBtnState(btn, input, updateRadios) {
        btn.toggleClass('active', input.prop('checked'));
        btn.toggleClass('disabled', input.prop('disabled'));
    }

    $(document).live('change', '.btn-toggle input', function(e) {
        var input = $(e.target);
        // radio button that are automatically unchecked dont trigger a change event
        if (input.is(':radio')) {
            var selector = 'input[type="radio"][name="' + input.attr('name') + '"]';
            $(selector).each(function() {
                var input = $(this),
                    btn = input.parents('.btn-toggle');
                updateBtnState(btn, input);
            });
        } else {
            var btn = input.parents('.btn-toggle');
            updateBtnState(btn, input);
        }
    });
    
    $('.btn-toggle').each(function() {
        var btn = $(this),
            input = btn.find('input');
        updateBtnState(btn, input);
    });
	
	/* For that extra help */
	$('.btn-toggle-notice label.btn-toggle').change(function() {
		if ($(this).children().hasClass('orig_check')) {
			$(this).parent().children().removeClass('btn-warning');
			$(this).parent().children('.btn-toggle-notice-revert').remove();
		}
		else {
			$(this).parent().children().addClass('btn-warning');
			$(this).parent().children('.btn-toggle-notice-revert').remove();
			$(this).parent().append('<a class="btn btn-toggle-notice-revert"><i class="icon-remove-circle"></i></a>');
			bootstrap_toggle_notice();
		}
	});
	
}

function bootstrap_toggle_notice() {

	$('.btn-toggle-notice-revert').bind('click', function() {
		
		$(this).parent().children().children().removeAttr('checked');
		$(this).parent().children().removeClass('active');
		$(this).parent().children().removeClass('btn-warning');
		$(this).parent().children().children('.orig_check').attr('checked', 'checked').parent().addClass('active');
		$(this).remove();
	
	});

}

function tinymce_init() {
	
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
	        }
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
	        }
	});
	
}

function pageLoad() {
	
	tinymce_init();
	
}