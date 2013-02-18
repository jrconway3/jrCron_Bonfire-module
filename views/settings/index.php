<div class="admin-box">
	<h3><?php echo lang('jrcs_jobs') ?></h3>
	
	<ul class="nav nav-tabs" >
		<li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url; ?>"><?php echo lang('jrcs_tab_all'); ?></a></li>
		<li <?php echo $filter=='active' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url .'?filter=active'; ?>"><?php echo lang('jrcs_tab_active'); ?></a></li>
		<li <?php echo $filter=='inactive' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url .'?filter=inactive'; ?>"><?php echo lang('jrcs_tab_inactive'); ?></a></li>
	</ul>

	<?php echo form_open($current_url .'?'. htmlentities($_SERVER['QUERY_STRING'], ENT_QUOTES, 'UTF-8')); ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th><?php echo lang('jrcs_name'); ?></th>
				<th><?php echo lang('jrcs_filename'); ?></th>
				<th><?php echo lang('jrcs_time'); ?></th>
				<th><?php echo lang('jrcs_memory'); ?></th>
				<th style="width: 11em"><?php echo lang('jrcs_modified'); ?></th>
				<th style="width: 10em"><?php echo lang('us_status'); ?></th>
			</tr>
		</thead>
		<?php if (isset($jobs) && is_array($jobs) && count($jobs)) : ?>
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo lang('bf_with_selected') ?>
					<input type="submit" name="activate" class="btn" value="<?php echo lang('bf_action_activate') ?>">
					<input type="submit" name="deactivate" class="btn" value="<?php echo lang('bf_action_deactivate') ?>">
				</td>
			</tr>
		</tfoot>
		<?php endif; ?>
		<tbody>

			<?php if (isset($jobs) && is_array($jobs) && count($jobs)) : ?>
				<?php foreach ($jobs as $job) : ?>
				<tr>
					<td>
						<input type="checkbox" name="checked[]" value="<?php echo $job->job_name ?>" />
					</td>
					<td>
						<a href="<?php echo site_url(SITE_AREA .'/settings/jrcron/edit/'. $job->job_name); ?>"><?php echo $job->job_title; ?></a>
					</td>
					<td><?php echo $job->job_file ?></td>
					<td>
						<?php echo $job->limit_time ?>S
					</td>
					<td>
						<?php echo $job->limit_memory ?>M
					</td>
					<td>
						<?php
							if ($job->modified_on != '0000-00-00 00:00:00')
							{
								echo date('M j, y g:i A', strtotime($job->modified_on));
							}
							else
							{
								echo '---';
							}
						?>
					</td>
					<td>
						<?php
						$class = '';
						switch ($job->job_enabled)
						{
							case 1:
								$class = " label-success";
								break;
							case 0:
							default:
								$class = " label-warning";
								break;

						}
						?>
						<span class="label<?php echo($class); ?>">
							<?php
							if ($job->job_enabled == 1)
							{
								echo(lang('us_active'));
							}
							else
							{
								echo(lang('us_inactive'));
							}
							?>
						</span>
					</td>
				</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="8"><?php echo lang('jrcs_no_jobs'); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?php echo form_close(); ?>

</div>
