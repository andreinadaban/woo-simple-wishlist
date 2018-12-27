<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

?>

<?php if ( $data = WCSW\Wishlist::get_data_array() ) { ?>

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

			<?php foreach ( $data['products'] as $product_id => $product_data ) { ?>

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

                        <a href="?wcsw-remove=<?php echo $product_id; ?>" class="wcsw-button wcsw-button-ajax wcsw-button-remove">

                            <span class="wcsw-remove-label"><?php _e( 'Remove from wishlist', 'wcsw' ); ?></span>

                            <span class="wcsw-remove-icon"><?php do_action( 'wcsw_remove_icon' ); ?></span>

                        </a>

                    </td>

                </tr>

			<?php } ?>

		</tbody>

	</table>

	<?php do_action( 'wcsw_after_table' ); ?>

<?php } else { ?>

    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">

        <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">

			<?php _e( 'Go shop', 'wcsw' ) ?>

        </a>

		<?php _e( 'There are no products in the wishlist yet.', 'wcsw' ); ?>

    </div>

<?php } ?>
