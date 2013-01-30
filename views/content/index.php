<h1><?=lang('jrcron_review')?></h1>


<!-- START Exports Table -->
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th><?php echo lang('jrcron_export_job'); ?></th>
			<th><?php echo lang('jrcron_export_time'); ?></th>
			<th><?php echo lang('jrcron_export_run'); ?></th>
			<th><?php echo lang('jrcron_export_file'); ?></th>
		</tr>
	</thead>
	<tbody>

		<!-- Exports Set? -->
		<?php if(is_array($exports) && count($exports)) : ?>
			<!-- Loop Exports -->
			<?php foreach($exports as $export) : ?>
			<tr>
				<td><?php echo $export->cron_job ?></td>
				<td><?php echo $export->finished_on ?></td>
				<td><?php echo $export->runtime ?></td>
				<td>
					<a href="<?php echo $this->csv->href_csv($export->export_name, $export->cron_job); ?>"><?php echo lang('jrcron_export_download'); ?></a>
				</td>
			</tr>
			<?php endforeach; ?>
			<!-- END Loop Exports -->
		<?php else: ?>
			<tr>
				<td colspan="8"><?php echo lang('jrcron_export_none'); ?></td>
			</tr>
		<?php endif; ?>
		<!-- END Exports -->
	</tbody>
</table>