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

do_action( 'sw_before_table' );

?>

<table class="sw-table">

    <thead class="sw-thead">

        <tr>

	        <?php do_action( 'sw_before_th_thumb' ); ?>

            <th></th>

            <?php do_action( 'sw_after_th_thumb' ); ?>

            <th><?php _e( 'Product', 'sw' ); ?></th>

            <?php do_action( 'sw_after_th_title' ); ?>

            <th></th>

        </tr>

    </thead>

    <tbody class="sw-tbody">

        <?php do_action( 'sw_after_tbody_start' ); ?>

        <?php foreach ( $products as $product_id => $product_data ) { ?>

            <tr class="sw-tr-<?php echo $product_id; ?>">

	            <?php do_action( 'sw_before_td_thumb' ); ?>

                <td class="sw-thumb">

	                <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                        <a href="<?php echo get_the_permalink( $product_id ); ?>">

                            <img src="<?php echo get_the_post_thumbnail_url( $product_id, 'woocommerce_gallery_thumbnail' ) ?>" alt="<?php echo get_the_title( $product_id ); ?>">

                        </a>

	                <?php } else { ?>

                        <div><img src="<?php echo wc_placeholder_img_src(); ?>" alt=""></div>

	                <?php } ?>

                </td>

                <?php do_action( 'sw_after_td_thumb' ); ?>

                <td class="sw-title">

                    <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                        <a href="<?php echo get_the_permalink( $product_id ); ?>">

                            <?php echo get_the_title( $product_id ); ?>

                        </a>

                    <?php } else { ?>

                        <div><?php echo $product_data['t'] . ' (' . __( 'no longer available', 'sw' ) . ')'; ?></div>

                    <?php } ?>

                </td>

                <?php do_action( 'sw_after_td_title' ); ?>

                <td class="sw-actions">

                    <a href="?sw-remove=<?php echo $product_id; ?>&nonce-token=<?php echo wp_create_nonce( 'sw_remove_from_wishlist_' . $product_id ) ?>" class="sw-button sw-button-ajax sw-button-remove remove" title="<?php _e( 'Remove from wishlist', 'sw' ); ?>"></a>

                </td>

	            <?php do_action( 'sw_after_td_actions' ); ?>

            </tr>

        <?php } ?>

        <?php do_action( 'sw_before_tbody_end' ); ?>

    </tbody>

	<?php do_action( 'sw_after_tbody' ); ?>

</table>

<?php do_action( 'sw_after_table' );
