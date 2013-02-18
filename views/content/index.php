<h1><?=lang('jrcs_jobs')?></h1>


<!-- START Exports Table -->
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th><?php echo lang('jrcs_job'); ?></th>
			<th><?php echo lang('jrcs_run'); ?></th>
			<th><?php echo lang('jrcs_runtime'); ?></th>
			<th><?php echo lang('jrcs_file'); ?></th>
		</tr>
	</thead>
	<tbody>

		<!-- Exports Set? -->
		<?php if(is_array($jobs) && count($jobs)) : $exports = 0; ?>
			<!-- Loop Exports -->
			<?php foreach($jobs as $job) :
				// Count Exports
				if(!empty($job->latest)) {
					$exports++;
				}
			?>
			<tr>
				<td>
					<?php if(!empty($job->latest)) : ?>
						<a href="<?php echo site_url(SITE_AREA .'/content/jrcron/files/'. $job->job_name); ?>"><?php echo $job->job_title ?></a>
					<?php else: ?>
						<?php echo $job->job_title ?>
					<?php endif; ?>
				</td>
				<td><?php
					if(!empty($job->latest->finished_on)) {
						echo date('M j, y g:i A', strtotime($job->latest->finished_on));
					}
					else {
						echo lang('jrcs_never');
					}
				?></td>
				<td><?php
					if(isset($job->latest->runtime)) {
						echo $job->latest->runtime;
					}
					else {
						echo '0';
					}
				?> Seconds</td>
				<td>
					<?php if(!empty($job->latest->export_name)) : ?>
						<a href="<?php echo $this->csv->href_csv($job->latest->export_name, $job->job_name); ?>"><?php echo lang('jrcs_download'); ?></a>
					<?php else :
						echo lang('jrcs_unavailable');
					endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
			<!-- END Loop Exports -->
		<?php else: ?>
			<tr>
				<td colspan="8"><?php echo lang('jrcs_no_jobs'); ?></td>
			</tr>
		<?php endif; ?>
		<!-- END Exports -->
	</tbody>
</table>

<?php if($exports > 0) : ?>
	<div class="form-actions">
		<a class="btn btn-primary" href="<?php echo site_url(SITE_AREA .'/content/jrcron/zip'); ?>"><?php echo lang('jrcs_zip') ?></a>
	</div>
<?php endif; ?>