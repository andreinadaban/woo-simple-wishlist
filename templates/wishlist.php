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

            <td class="wcsw-td">

                <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                    <a href="<?php echo get_the_permalink( $product_id ); ?>" class="wcsw-title">

                        <img src="<?php echo get_the_post_thumbnail_url( $product_id, 'woocommerce_gallery_thumbnail' ) ?>" alt="<?php echo get_the_title( $product_id ); ?>" class="wcsw-thumb">

                        <span class="wcsw-title-text"><?php echo get_the_title( $product_id ); ?></span>

                    </a>

                <?php } else { ?>

                    <div class="wcsw-title">

                        <img src="<?php echo wc_placeholder_img_src(); ?>" alt="" class="wcsw-thumb">

                        <span class="wcsw-title-text">

                            <?php echo $product_data['title'] . ' (' . __( 'no longer available', 'wcsw' ) . ')'; ?>

                        </span>

                    </div>

                <?php } ?>

            </td>

            <?php do_action( 'wcsw_after_td_title' ); ?>

            <td class="wcsw-td wcsw-actions">

                <a href="?wcsw-remove=<?php echo $product_id; ?>&nonce-token=<?php echo wp_create_nonce( 'wcsw_remove_from_wishlist_' . $product_id ) ?>" class="wcsw-button wcsw-button-ajax wcsw-button-remove remove" title="<?php _e( 'Remove from wishlist', 'wcsw' ); ?>"></a>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php do_action( 'wcsw_after_table' ); ?>

<?php echo $this->get_clear_wishlist_button(); ?>

<?php do_action( 'wcsw_after_clear_button' ); ?>
