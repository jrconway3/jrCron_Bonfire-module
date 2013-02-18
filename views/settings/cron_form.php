<div class="admin-box">

	<h3><?php echo lang('jrcs_edit') ?></h3>

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal" autocomplete="off"'); ?>

	<fieldset>
		<legend><?php echo lang('jrcs_settings') ?></legend>

		<div class="control-group">
			<label for="job_title" class="control-label"><?php echo lang('jrcs_name') ?></label>
			<div class="controls">
				<?php echo set_value('job_title', $job->job_title); ?>
			</div>
		</div>

		<div class="control-group">
			<label for="job_name" class="control-label"><?php echo lang('jrcs_code') ?></label>
			<div class="controls">
				<?php echo set_value('job_name', $job->job_name); ?>
			</div>
		</div>

		<div class="control-group">
			<label for="job_name" class="control-label"><?php echo lang('jrcs_desc') ?></label>
			<div class="controls">
				<?php echo set_value('job_desc', $job->job_desc); ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('job_file') ? 'error' : '' ?>">
			<label for="job_file" class="control-label"><?php echo lang('jrcs_filename') ?></label>
			<div class="controls">
				<input type="text" name="job_file" id="job_file" class="input-xlarge" value="<?php echo set_value('job_file', $job->job_file) ?>">
				<?php if (form_error('job_file')) echo '<span class="help-inline">'. form_error('job_file') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('limit_time') ? 'error' : '' ?>">
			<label for="limit_time" class="control-label"><?php echo lang('jrcs_time') ?></label>
			<div class="controls">
				<input type="text" id="limit_time" name="limit_time" value="<?php echo set_value('limit_time', $job->limit_time) ?>">&nbsp;Seconds
				<?php if (form_error('limit_time')) echo '<span class="help-inline">'. form_error('limit_time') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('limit_memory') ? 'error' : '' ?>">
			<label for="limit_time" class="control-label"><?php echo lang('jrcs_memory') ?></label>
			<div class="controls">
				<input type="text" id="limit_memory" name="limit_memory" value="<?php echo set_value('limit_memory', $job->limit_memory) ?>">&nbsp;Megabytes
				<?php if (form_error('limit_memory')) echo '<span class="help-inline">'. form_error('limit_memory') .'</span>'; ?>
			</div>
		</div>
	</fieldset>

	<div class="form-actions">
		<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('jrcs_job') ?> " /> <?php echo lang('bf_or') ?>
		<?php echo anchor(SITE_AREA .'/settings/jrcron', '<i class="icon-refresh icon-white">&nbsp;</i>&nbsp;' . lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
	</div>

	<?php echo form_close(); ?>

</div>
