<?php

/**
 * The wishlist public template.
 *
 * @since    1.0.0
 */

/**
 * If this file is called directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php do_action( 'wcsw_before_table' ); ?>

<table class="wcsw-table woocommerce-table">

    <thead>

    <tr>

        <?php do_action( 'wcsw_before_th_title' ); ?>

        <th><?php _e( 'Product', 'wcsw' ); ?></th>

        <?php do_action( 'wcsw_after_th_title' ); ?>

        <th></th>

    </tr>

    </thead>

    <tbody>

    <?php foreach ( $products as $product_id => $product_data ) { ?>

        <tr>

            <?php do_action( 'wcsw_before_td_title' ); ?>

            <td class="wcsw-title">

                <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                    <a href="<?php echo get_the_permalink( $product_id ); ?>">

                        <?php echo get_the_title( $product_id ); ?>

                    </a>

                <?php } else { ?>

                    <?php echo $product_data['title'] . ' (' . __( 'no longer available', 'wcsw' ) . ')'; ?>

                <?php } ?>

            </td>

            <?php do_action( 'wcsw_after_td_title' ); ?>

            <td class="wcsw-remove">

	            <?php echo $this->get_remove_from_wishlist_button( $product_id ); ?>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php echo $this->get_clear_wishlist_button(); ?>

<?php do_action( 'wcsw_after_table' ); ?>
