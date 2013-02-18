<h1><?=lang('jrcs_files')?> <?=$job->job_title?></h1>


<!-- START Exports Table -->
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th><?php echo lang('jrcs_run'); ?></th>
			<th><?php echo lang('jrcs_runtime'); ?></th>
			<th><?php echo lang('jrcs_file'); ?></th>
		</tr>
	</thead>
	<tbody>

		<!-- Exports Set? -->
		<?php if(is_array($files) && count($files)) : ?>
			<!-- Loop Exports -->
			<?php foreach($files as $file) : ?>
			<tr>
				<td><?php
					if(!empty($file->finished_on)) {
						echo date('M j, y g:i A', strtotime($file->finished_on));
					}
					else {
						echo lang('jrcs_never');
					}
				?></td>
				<td><?php
					if(isset($file->runtime)) {
						echo $file->runtime;
					}
					else {
						echo '0';
					}
				?> Seconds</td>
				<td>
					<?php if(!empty($file->export_name)) : ?>
						<a href="<?php echo $this->csv->href_csv($file->export_name, $job->job_name); ?>"><?php echo lang('jrcs_download'); ?></a>
					<?php else :
						echo lang('jrcs_unavailable');
					endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
			<!-- END Loop Exports -->
		<?php else: ?>
			<tr>
				<td colspan="8"><?php echo lang('jrcs_no_files'); ?></td>
			</tr>
		<?php endif; ?>
		<!-- END Exports -->
	</tbody>
</table>

<div class="form-actions">
	<a class="btn btn-primary" href="<?php echo site_url(SITE_AREA .'/content/jrcron/zip/'. $job->job_name); ?>"><?php echo lang('jrcs_zip') ?></a>
</div>