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

<table class="wcsw-table">

    <thead class="wcsw-thead">

        <tr>

	        <?php do_action( 'wcsw_before_th_thumb' ); ?>

            <th></th>

            <?php do_action( 'wcsw_after_th_thumb' ); ?>

            <th><?php _e( 'Product', 'wcsw' ); ?></th>

            <?php do_action( 'wcsw_after_th_title' ); ?>

            <th></th>

        </tr>

    </thead>

    <tbody class="wcsw-tbody">

        <?php do_action( 'wcsw_after_tbody_start' ); ?>

        <?php foreach ( $products as $product_id => $product_data ) { ?>

            <tr>

	            <?php do_action( 'wcsw_before_td_thumb' ); ?>

                <td class="wcsw-thumb">

	                <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                        <a href="<?php echo get_the_permalink( $product_id ); ?>">

                            <img src="<?php echo get_the_post_thumbnail_url( $product_id, 'woocommerce_gallery_thumbnail' ) ?>" alt="<?php echo get_the_title( $product_id ); ?>">

                        </a>

	                <?php } else { ?>

                        <div><img src="<?php echo wc_placeholder_img_src(); ?>" alt=""></div>

	                <?php } ?>

                </td>

                <?php do_action( 'wcsw_after_td_thumb' ); ?>

                <td class="wcsw-title">

                    <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                        <a href="<?php echo get_the_permalink( $product_id ); ?>">

                            <?php echo get_the_title( $product_id ); ?>

                        </a>

                    <?php } else { ?>

                        <div><?php echo $product_data['title'] . ' (' . __( 'no longer available', 'wcsw' ) . ')'; ?></div>

                    <?php } ?>

                </td>

                <?php do_action( 'wcsw_after_td_title' ); ?>

                <td class="wcsw-actions">

                    <a href="?wcsw-remove=<?php echo $product_id; ?>&nonce-token=<?php echo wp_create_nonce( 'wcsw_remove_from_wishlist_' . $product_id ) ?>" class="wcsw-button wcsw-button-ajax wcsw-button-remove remove" title="<?php _e( 'Remove from wishlist', 'wcsw' ); ?>"></a>

                </td>

	            <?php do_action( 'wcsw_after_td_actions' ); ?>

            </tr>

        <?php } ?>

        <?php do_action( 'wcsw_before_tbody_end' ); ?>

    </tbody>

	<?php do_action( 'wcsw_after_tbody' ); ?>

</table>

<?php do_action( 'wcsw_after_table' ); ?>

<?php $this->clear_wishlist_button(); ?>

<?php do_action( 'wcsw_after_clear_button' ); ?>
