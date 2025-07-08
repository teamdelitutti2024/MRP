<?php $alertmessage = session( 'alertmessage' );
if ( $alertmessage ) {
	if( $alertmessage['type'] == 'success' ){ ?>
	<div class="alert alert-success mb20">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo __( $alertmessage['message'] ); ?>
	</div>
<?php }
	if( $alertmessage['type'] == 'error' ){ ?>
	<div class="alert alert-danger mb20">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo __( $alertmessage['message'] ); ?>
	</div>
<?php }
}