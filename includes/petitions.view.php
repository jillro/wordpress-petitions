<div class="wrap" id="dk-speakout">

	<div id="icon-dk-speakout" class="icon32"><br /></div>
	<h2><?php echo $page_title; ?> <a href="<?php echo $addnew_url; ?>" class="add-new-h2"><?php _e( 'Add New', 'guilro_petitions' ); ?></a></h2>
	<?php if ( $message_update ) echo '<div id="message" class="updated"><p>' . $message_update . '</p></div>' ?>

	<div class="tablenav">
		<ul class='subsubsub'>
			<li class='table-label'><?php _e( 'All Petitions', 'guilro_petitions' ); ?> <span class="count">(<?php echo $count; ?>)</span></li>
		</ul>
		<?php echo guilro_petitions_SpeakOut::pagination( $query_limit, $count, 'guilro_petitions', $current_page, site_url( 'wp-admin/admin.php?page=guilro_petitions' ), true ); ?>
	</div>

	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Petition', 'guilro_petitions' ); ?></th>
				<th><?php _e( 'Shortcodes', 'guilro_petitions' ); ?></th>
				<th class="dk-speakout-right"><?php _e( 'Signatures', 'guilro_petitions' ); ?></th>
				<th class="dk-speakout-right"><?php _e( 'Goal', 'guilro_petitions' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php _e( 'Petition', 'guilro_petitions' ); ?></th>
				<th><?php _e( 'Shortcodes', 'guilro_petitions' ); ?></th>
				<th class="dk-speakout-right"><?php _e( 'Signatures', 'guilro_petitions' ); ?></th>
				<th class="dk-speakout-right"><?php _e( 'Goal', 'guilro_petitions' ); ?></th>
				<th></th>
			</tr>
		</tfoot>
		<tbody>
		<?php if ( $count == 0 ) echo '<tr><td colspan="5">' . __( "No petitions found.", "guilro_petitions" ) . ' </td></tr>'; ?>
		<?php foreach ( $petitions as $petition ) : ?>
			<?php $edit_url       = esc_url( wp_nonce_url( site_url() . '/wp-admin/admin.php?page=guilro_petitions_addnew&action=edit&id=' . $petition->id, 'guilro_petitions-edit_petition' . $petition->id ) ); ?>
			<?php $delete_url     = esc_url( wp_nonce_url( site_url() . '/wp-admin/admin.php?page=guilro_petitions&action=delete&id=' . $petition->id, 'guilro_petitions-delete_petition' . $petition->id ) ); ?>
			<?php $signatures_url = esc_url( site_url() . '/wp-admin/admin.php?page=guilro_petitions_signatures&action=petition&pid=' . $petition->id ); ?>
			<tr class="dk-speakout-tablerow">
				<td>
					<a class="row-title" href="<?php echo $edit_url; ?>"><?php echo stripslashes( esc_html( $petition->title ) ); ?></a>
					<div class="row-actions">
						<span class="edit"><a href="<?php echo $edit_url; ?>"><?php _e( 'Edit' ); ?></a> | </span>
						<span><a href="<?php echo $delete_url; ?>" class="dk-speakout-delete-petition"><?php _e( 'Delete', 'guilro_petitions' ); ?></a></span>
					</div>
				</td>
				<td><?php echo '[emailpetition&nbsp;id="' . $petition->id . '"]<br />[signaturelist&nbsp;id="' . $petition->id . '"]'; ?></td>
				<td class="dk-speakout-right"><?php echo number_format( $petition->signatures ); ?></td>
				<td class="dk-speakout-right">
					<?php echo number_format( $petition->goal ); ?>
					<div class="guilro_petitions_clear"></div>
					<?php echo guilro_petitions_SpeakOut::progress_bar( $petition->goal, $petition->signatures, 65 ); ?>
				</td>
				<td class="dk-speakout-right" style="vertical-align: middle"><a class="button" href="<?php echo $signatures_url; ?>"><?php _e( 'View Signatures', 'guilro_petitions' ); ?></a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<div class="tablenav">
		<?php echo guilro_petitions_SpeakOut::pagination( $query_limit, $count, 'guilro_petitions', $current_page, site_url( 'wp-admin/admin.php?page=guilro_petitions' ), false ); ?>
	</div>

	<div id="dk-speakout-delete-confirmation" class="dk-speakout-hidden"><?php _e( 'Delete this petition permanently? All of the petition\'s signatures will be deleted as well.', 'guilro_petitions' ); ?></div>

</div>