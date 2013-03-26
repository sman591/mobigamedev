<?php $user->is_logged_in() ?>
<div class="page-header">
<h1>Convert</h1>
</div>

<form action="/resources/convert/converter.php" method="post" class="form-horizontal">

	<h3 class="page-header">Input</h3>
	
	<div class="control-group">
		<label class="control-label" for="file_in">Input File</label>
		<div class="controls">
			<input type="text" id="file_in" name="file_in" class="input-xxlarge" placeholder="File Path (include extension)">
		</div>
	</div>
	
	
	<h3 class="page-header">Output</h3>
	
	<div class="control-group">
		<label class="control-label" for="file_out">Output File</label>
		<div class="controls">
			<input type="text" id="file_out" name="file_out" class="input-xxlarge" placeholder="File Path (include extension)">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_format">Format</label>
		<div class="controls">
			<select name="out_format" id="out_format">
				<option value="mp4" selected="selected">mp4/m4v (default)</option>
				<option value="mov">mov</option>
				<option value="mkv">mkv</option>
				<option value="flv">flv</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<h4>Video Options</h4>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_quality">Quality</label>
		<div class="controls">
			<select name="out_quality" id="out_quality">
				<? for ($i = 100; $i >= 1; $i = $i-5)
						echo '<option value="'.$i.'"'.($i==100 ? ' selected="selected"' : '').'>'.$i.'%'.($i==100 ? ' (default)' : '').'</option>';
				?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_quality">Frame Rate (FPS)</label>
		<div class="controls">
			<select name="out_quality" id="out_quality">
				<option value="0" selected="selected">Source (default)</option>
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="12">12</option>
				<option value="15">15</option>
				<option value="23.976">23.976 (NTSC Film)</option>
				<option value="24">24</option>
				<option value="25">25 (PAL Film/Video)</option>
				<option value="29.97">29.97 (NTSC Video)</option>
				<option value="30">30</option>
				<option value="50">50</option>
				<option value="59.94">59.94</option>
				<option value="60">60</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_video_anamorphic">Anamorphic</label>
		<div class="controls">
			<select name="out_video_anamorphic" id="out_video_anamorphic" disabled="disabled">
				<option value="none" selected="selected">None (default)</option>
				<option value="strict">Strict</option>
				<option value="loose">Loose</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_res_w">Resolution</label>
		<div class="controls">
			<input type="text" id="out_res_w" name="out_res_w" class="input-small" placeholder="Source Width" maxlength="4"> x <input type="text" id="out_res_h" name="out_res_h" class="input-small" placeholder="Source Height" maxlength="4">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_crop_t">Cropping</label>
		<div class="controls">
			<input type="text" id="out_crop_t" name="out_crop_t" class="input-small" placeholder="0" maxlength="4" style="margin-left: 55px"><br>
			<input type="text" id="out_crop_t" name="out_crop_t" class="input-small" placeholder="0" maxlength="4">&nbsp;&nbsp;<input type="text" id="out_crop_t" name="out_crop_t" class="input-small" placeholder="0" maxlength="4"><br>
			<input type="text" id="out_crop_t" name="out_crop_t" class="input-small" placeholder="0" maxlength="4" style="margin-left: 55px">
		</div>
	</div>
	
	
	<div class="control-group">
		<div class="controls">
			<h4>Audio Options</h4>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_audio_codec">Codec</label>
		<div class="controls">
			<select name="out_audio_codec" id="out_audio_codec">
				<option value="0" selected="selected">Source (auto passthru) (default)</option>
				<option value="acc">ACC (ffmpeg)</option>
				<option value="ac3">AC3 (ffmpeg)</option>
				<option value="MP3">MP3 (lame)</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_audio_mixdown">Mixdown</label>
		<div class="controls">
			<select name="out_audio_mixdown" id="out_audio_mixdown">
				<option value="0" selected="selected">Source (default)</option>
				<option value="mono">Mono</option>
				<option value="stereo">Stereo</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_audio_sample">Sample Rate</label>
		<div class="controls">
			<select name="out_audio_sample" id="out_audio_sample">
				<option value="0" selected="selected">Source (default)</option>
				<option value="22.05">22.05</option>
				<option value="24">24</option>
				<option value="32">32</option>
				<option value="44.1">44.1</option>
				<option value="48">48</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="out_audio_bit">Bit Rate</label>
		<div class="controls">
			<select name="out_audio_bit" id="out_audio_bit">
				<option value="0">Source (default)</option>
				<option value="64">32</option>
				<option value="64">64</option>
				<option value="80">80</option>
				<option value="96">96</option>
				<option value="112">112</option>
				<option value="128">128</option>
				<option value="160">160</option>
				<option value="192">192</option>
				<option value="224">224</option>
				<option value="256">256</option>
				<option value="320">320</option>
			</select>
		</div>
	</div>
	
	<div class="form-actions">
	
		<input type="submit" name="submit" value="Convert" class="btn btn-primary">
		
	</div>

</form>

<script type="text/javascript">
function custom_pageLoad() {

	
}
</script>