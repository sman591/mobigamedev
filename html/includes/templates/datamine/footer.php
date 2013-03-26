</div>
</div><!--.container-->
<? if (!$this->is_dynamic()) { ?>

<? if (!$this->is_bare()) { ?>
<div id="footer">
    Team Zero Day, Imagine Cup 2013 - World Citizenship
</div><!--#footer-->
<? } ?>

<div class="modal fade" id="globalModal">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>

        <h3>Modal header</h3>
    </div>

	<div class="modal-error">
        <? $alert = new bootstrap_alert('Error', '[error details]', 'error', '', array('href' => "javascript:modal_handler('error', 'reset', '#globalModal')"));
        echo $alert->display(); ?>
	</div>

    <div class="modal-body">
        <p>One fine body…</p>
    </div>

    <div class="modal-footer">
        <a class="btn modal-close" data-dismiss="modal">Close</a> <a class="btn btn-primary modal-save">Save changes</a>
    </div>
</div>

<div class="modal fade" id="errorModal">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>

        <h4>Error</h4>
    </div>

	<div class="modal-error">
        <? $alert = new bootstrap_alert('Error', '[error details]', 'error', '', array('href' => "javascript:modal_handler('error', 'reset', '#errorModal')"));
        echo $alert->display(); ?>
	</div>

    <div class="modal-body">
        <p>[error details]</p>
    </div>

    <div class="modal-footer">
        <a class="btn modal-close" data-dismiss="modal">Close</a> <a class="btn btn-primary modal-save">Save changes</a>
    </div>
</div>

<div id="loader-holder">
    <i class="loader"></i>
</div>

<!-- Javascript goodies! -->

<?php

if (in_array($site->current_env(), array('LIVE', 'STAGE'))) {
	$testingMin = '.min';
} else {$testingMin = '';}

$testingMin = '';

?>

<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
<script type="text/javascript">
if (typeof jQuery == 'undefined')
{
    document.write(unescape("%3Cscript src='/resources/js/jquery-1.9.1.min.js' type='text/javascript'%3E%3C/script%3E"));
}
</script>

<script src="/resources/js/underscore-1.4.4.min.js" type="text/javascript"></script>
<script src="/resources/js/backbone-0.9.10.min.js"></script>
<script src="/resources/js/backbone.routefilter.min.js"></script>

<script src="/resources/js/jquery.fittext.js"></script>

<!-- <script type="text/javascript" src="/resources/js/jquery-ui-1.10.0.custom/js/jquery-ui-1.10.0.custom.min.js"></script> -->
<script type="text/javascript" src="/resources/bootstrap-2.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/resources/tinymce-3.5.8/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/resources/js/global.js"></script>

<script src="/resources/js/jquery.stickytableheaders.js" type="text/javascript"></script>

<!-- BACKBONE -->

<? 	include 'backbone/user.php';
	include 'backbone/project.php';
?>

<script src="/resources/js/app.js" type="text/javascript"></script>

<script type="text/javascript">

$(function() {
	
	var app = app || {};
	
	window.app = app;
	
	window.app.debug = false;
	
	window.app.router = new AppRouter();
	
	window.app.router.start();

});

</script>

<!-- END BACKBONE -->

<? } /* if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') */ ?>
</body>
</html>
