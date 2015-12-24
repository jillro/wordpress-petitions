<div class="wrap" id="guilro-petitions">

	<div id="icon-guilro-petitions" class="icon32"><br /></div>
	<h2><?php _e('Signatures', 'guilro_petitions'); ?></h2>
	<?php if ($message_update) {
    echo '<div id="message" class="updated"><p>'.$message_update.'</p></div>';
} ?>

	<div class="tablenav">
		<ul class='subsubsub'>
			<li class='table-label'><?php echo stripslashes($table_label); ?></li>
		</ul>

		<div class="guilro_petitions_clear">
			<div class="alignleft">
				<form action="" method="get">
					<select id="guilro-petitions-switch-petition">
						<option value=""><?php _e('Select petition', 'guilro_petitions'); ?></option>
						<?php foreach ($petitions_list as $petition) : ?>
							<option value="<?php echo $petition->id; ?>"><?php echo stripslashes($petition->title); ?></option>
						<?php endforeach; ?>
					</select>
					<?php
                        // Display the 'Download as CSV' and 'Re-send confirmations' buttond only when viewing signatures for a single petition
                        // Hide buttons when viewing All Signatures
                        if (isset($_REQUEST['pid']) || $pid != '') {
                            echo ' 
								<a class="button guilro-petitions-inline" style="margin: 0 .5em 0 .5em" href="'.esc_url(wp_nonce_url($csv_url.'&csv=signatures', 'guilro_petitions-download_signatures')).'">'.__('Download as CSV', 'guilro_petitions').'</a>
								<a id="guilro-petitions-reconfirm" class="button guilro-petitions-inline" href="'.esc_url(wp_nonce_url($reconfirm_url, 'guilro_petitions-resend_confirmations'.$pid)).'">'.__('Re-send confirmations', 'guilro_petitions').'</a>
								<div id="guilro-petitions-reconfirm-confirmation" class="guilro-petitions-hidden">'.__('Are you sure you want to do this? A separate confirmation email will be sent for each unconfirmed signature.', 'guilro_petitions').'</div>
							';
                        }
                    ?>
				</form>
			</div>
			<div class="alignright">
				<?php echo guilro_petitions_SpeakOut::pagination($query_limit, $count, 'guilro_petitions_signatures', $current_page, $base_url, true); ?>
			</div>
		</div>
	</div>

	<table class="widefat">
		<thead>
			<tr>
				<th></th>
				<th><?php _e('Name', 'guilro_petitions'); ?></th>
				<th><?php _e('Email', 'guilro_petitions'); ?></th>
				<th><?php _e('Petition', 'guilro_petitions'); ?></th>
				<th><?php _e('Confirmed', 'guilro_petitions'); ?></th>
				<th><?php _e('Opt-in', 'guilro_petitions'); ?></th>
				<th><?php _e('Date', 'guilro_petitions'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<th><?php _e('Name', 'guilro_petitions'); ?></th>
				<th><?php _e('Email', 'guilro_petitions'); ?></th>
				<th><?php _e('Petition', 'guilro_petitions'); ?></th>
				<th><?php _e('Confirmed', 'guilro_petitions'); ?></th>
				<th><?php _e('Opt-in', 'guilro_petitions'); ?></th>
				<th><?php _e('Date', 'guilro_petitions'); ?></th>
				<th></th>
			</tr>
		</tfoot>
		<tbody>
			<?php if ($count == 0) {
    echo '<tr><td colspan="8">'.__('No signatures found.', 'guilro_petitions').' </td></tr>';
} ?>
			<?php $current_row = ($count - $query_start) + 1; ?>
			<?php foreach ($signatures as $signature) : ?>
				<?php $pid_string = ($pid) ? '&pid='.$pid : ''; ?>
				<?php $delete_url = esc_url(wp_nonce_url(site_url().'/wp-admin/admin.php?page=guilro_petitions_signatures&action=delete&sid='.$signature->id.$pid_string, 'guilro_petitions-delete_signature'.$signature->id)); ?>
				<?php
                    --$current_row;
                    // make confirmed values readable
                    $confirmed = $signature->is_confirmed;
                    if ($confirmed == '1') {
                        $confirmed = '<span class="guilro-petitions-green">'.__('confirmed', 'guilro_petitions').'</span>';
                    } elseif ($confirmed == '0') {
                        $confirmed = __('unconfirmed', 'guilro_petitions');
                    } else {
                        $confirmed = '...';
                    }
                    // make email opt-in values readable
                    $optin = $signature->optin;
                    if ($optin == '1') {
                        $optin = '<span class="guilro-petitions-green">'.__('yes', 'guilro_petitions').'</span>';
                    } elseif ($optin == '0') {
                        $optin = __('no', 'guilro_petitions');
                    } else {
                        $optin = '...';
                    }
                ?>
			<tr class="guilro-petitions-tablerow">
				<td class="guilro-petitions-right"><?php echo number_format($current_row, 0, '.', ','); ?></td>
				<td><?php echo stripslashes(esc_html($signature->first_name.' '.$signature->last_name)); ?></td>
				<td><?php echo stripslashes(esc_html($signature->email)); ?></td>
				<td><?php echo stripslashes(esc_html($signature->title)); ?></td>
				<td><?php echo $confirmed; ?></td>
				<td><?php echo $optin; ?></td>
				<td><?php echo ucfirst(date_i18n('M d, Y', strtotime($signature->date))); ?></td>
				<td class="guilro-petitions-right"><span class="trash"><a href="<?php echo $delete_url; ?>"><?php _e('Delete', 'guilro_petitions'); ?></a></span></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="tablenav">
		<?php echo guilro_petitions_SpeakOut::pagination($query_limit, $count, 'guilro_petitions_signatures', $current_page, $base_url, false); ?>
	</div>

</div>