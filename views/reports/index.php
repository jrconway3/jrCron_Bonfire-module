<div class="row">
	<div class="column size1of2">

		<!-- Today's Cron Logs -->
		<div class="admin-box">
			<h3><?php echo lang('jrcron_logs_today'); ?></h3>
			<?php if (isset($logs_today) && is_array($logs_today) && count($logs_today)) : ?>

				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo lang('jrcron_module'); ?></th>
							<th><?php echo lang('jrcron_logged'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($logs_today as $top_module) : ?>
						<tr>
							<td>
								<strong><?php echo ucwords($top_module->module); ?></strong>
							</td>
							<td><?php echo $top_module->jrcron_count; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

			<?php else : ?>
				<?php echo lang('jrcron_no_logs_today'); ?>
			<?php endif; ?>
		</div>

	</div>

	<div class="column size1of2 last-column">

		<!-- Today's Cron Errors -->
		<div class="admin-box">
			<h3><?php echo lang('jrcron_errors_today'); ?></h3>
			<?php if (isset($errors_today) && is_array($errors_today) && count($errors_today)) : ?>

				<table class="table table-striped">
					<thead>
						<tr>
							<th><?php echo lang('jrcron_user'); ?></th>
							<th><?php echo lang('jrcron_logged'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($errors_today as $top_user) : ?>
						<tr>
							<td><strong><?php e($top_user->username == '' ? 'Not found':$top_user->username); ?></strong></td>
							<td><?php echo $top_user->jrcron_count; ?></td>
						</tr>
				<?php endforeach; ?>
					</tbody>
				</table>

			<?php else : ?>
				<?php echo lang('jrcron_no_errors_today'); ?>
			<?php endif; ?>
		</div>

	</div>
</div>