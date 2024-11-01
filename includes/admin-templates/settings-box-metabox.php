<?php
/*
* @Author 		Pluginrox
* Copyright: 	2018 Pluginrox
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$meta_data = get_option( 'wpdl_meta_fields' );
$meta_data = empty( $meta_data ) ? array() : $meta_data;

?>

<div class="meta-field-groups">

    <div class="button add-new-meta-group">Add Group</div>

	<?php foreach ( $meta_data as $group_id => $group_data ) : ?>
		<?php echo wpdl_add_meta_group( $group_id, $group_data ); ?>
	<?php endforeach; ?>

</div>


<div class="wpdl-popup meta-icon-search">
    <div class="wpdl-popup-box">
        <i class="wpdl-popup-box-close fa fa-close"></i>
        <h2 class="wpdl-popup-header">Select Icon</h2>
        <input class="search-field" type="search">
    </div>
</div>