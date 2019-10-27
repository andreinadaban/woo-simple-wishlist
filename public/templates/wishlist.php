<?php

/**
 * Simple Wishlist for WooCommerce
 * Copyright (C) 2018-2019 Andrei Nadaban
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * The wishlist public template.
 *
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="sw-content">

    <?php

    if ( ! $products || empty( $products ) ) {

	    $this->the_empty_notice();

    } else {

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

                        <?php do_action( 'sw_before_td_thumb', $product_data ); ?>

                        <td class="sw-thumb">

                            <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                                <a href="<?php echo get_the_permalink( $product_id ); ?>">

                                    <img src="<?php echo get_the_post_thumbnail_url( $product_id, 'woocommerce_gallery_thumbnail' ) ?>" alt="<?php echo get_the_title( $product_id ); ?>">

                                </a>

                            <?php } else { ?>

                                <div><img src="<?php echo wc_placeholder_img_src(); ?>" alt=""></div>

                            <?php } ?>

                        </td>

                        <?php do_action( 'sw_after_td_thumb', $product_data ); ?>

                        <td class="sw-title">

                            <?php if ( get_post( $product_id ) && get_post_status( $product_id ) === 'publish' ) { ?>

                                <a href="<?php echo get_the_permalink( $product_id ); ?>">

                                    <?php echo get_the_title( $product_id ); ?>

                                </a>

                            <?php } else { ?>

                                <div><?php echo $product_data['t'] . ' (' . __( 'no longer available', 'sw' ) . ')'; ?></div>

                            <?php } ?>

                        </td>

                        <?php do_action( 'sw_after_td_title', $product_data ); ?>

                        <td class="sw-actions">

                            <a href="?sw-remove=<?php echo $product_id; ?>&nonce-token=<?php echo wp_create_nonce( 'sw_remove_from_wishlist_' . $product_id ) ?>" class="sw-button sw-button-ajax sw-button-remove remove" title="<?php _e( 'Remove from wishlist', 'sw' ); ?>"></a>

                        </td>

                        <?php do_action( 'sw_after_td_actions', $product_data ); ?>

                    </tr>

                <?php } ?>

                <?php do_action( 'sw_before_tbody_end' ); ?>

            </tbody>

            <?php do_action( 'sw_after_tbody' ); ?>

        </table>

        <?php do_action( 'sw_after_table' ); ?>

    <?php } ?>

</div>
