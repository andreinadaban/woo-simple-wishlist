# Simple Wishlist for WooCommerce

A simple extension for WooCommerce that provides the basic functionality of a wishlist and a set of functions and hooks for easy customization.

## Features

- AJAX
- Editable template
- Template action hooks
- Options filter
- Filter for adding additional data to each product in the wishlist
- SVG icons
- 3.1 KB minified JS
- No CSS

## Requirements

- WordPress 5.2 or greater
- WooCommerce 3.7 or greater
- PHP 7.3 or greater
- MySQL 5.6 or greater or MariaDB 10.0 or greater

## Usage

Out of the box, the plugin provides an add to wishlist and a remove from wishlist button under each product, on both archive and single pages. It also provides a new section under My Account, where the wishlist content is displayed.

Additional functionality can be created using the available action and filter hooks, functions or by overriding the default template.

## Documentation

### The default template

The default template used on the My Account page is located at `templates/wishlist.php`. Similar to WooCommerce templates, you can override it by copying it in your theme directory at `simple-wishlist-for-woocommerce/wishlist.php`.

### Action hooks

The following action hooks are present in the template used on the My Account page.

```
sw_before_table
sw_before_th_thumb
sw_after_th_thumb
sw_after_th_title
sw_after_tbody_start
sw_before_td_thumb
sw_after_td_thumb
sw_after_td_title
sw_after_td_actions
sw_before_tbody_end
sw_after_tbody
sw_after_table
```

### Filters

#### The configuration filter

Use the `sw_config` filter to change the default options. The default options are the following:

```
array(
    'ajax'                    => true,
    'button_add_icon'         => DIR . 'public/assets/dist/svg/heart-add.svg',
    'button_add_label'        => __( 'Add to wishlist', 'sw' ),
    'button_clear'            => true,
    'button_clear_icon'       => DIR . 'public/assets/dist/svg/clear.svg',
    'button_clear_label'      => __( 'Clear wishlist', 'sw' ),
    'button_default'          => true,
    'button_in_archive'       => true,
    'button_remove_icon'      => DIR . 'public/assets/dist/svg/heart-remove.svg',
    'button_remove_label'     => __( 'Remove from wishlist', 'sw' ),
    'button_style'            => 'icon_text',
    'endpoint'                => __( 'wishlist', 'sw' ),
    'menu_name'               => __( 'Wishlist', 'sw' ),
    'menu_position'           => 2,
    'message_add_error'       => __( 'The product was not added to your wishlist. Please try again.', 'sw' ),
    'message_add_success'     => __( 'The product was successfully added to your wishlist.', 'sw' ),
    'message_add_view'        => __( 'View wishlist', 'sw' ),
    'message_empty'           => __( 'There are no products in the wishlist yet.', 'sw' ),
    'message_empty_label'     => __( 'Go shop', 'sw' ),
    'message_clear_error'     => __( 'The wishlist was not cleared. Please try again.', 'sw' ),
    'message_clear_success'   => __( 'The wishlist was successfully cleared.', 'sw' ),
    'message_remove_error'    => __( 'The product was not removed from your wishlist. Please try again.', 'sw' ),
    'message_remove_success'  => __( 'The product was successfully removed from your wishlist.', 'sw' ),
)
```

**Example:** Changing the menu position.

```
add_filter( 'sw_config', function( $config ) {
    $config['menu_position'] = 3;
    return $config;
} );
```

#### The options

<table>
    <thead>
        <tr>
            <th>Option</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>ajax</code></td>
            <td>Enables or disables AJAX support. If the value is set to <code>false</code> the external script file is not loaded.</td>
        </tr>
        <tr>
            <td><code>button_default</code></td>
            <td>Enables or disables the add, remove and clear buttons.</td>
        </tr>
        <tr>
            <td><code>button_add_icon</code>, <code>button_clear_icon</code>, <code>button_remove_icon</code></td>
            <td>The relative path of the icon.</td>
        </tr>
        <tr>
            <td><code>button_clear</code></td>
            <td>Enables or disables the clear wishlist button from the My Account page.</td>
        </tr>
        <tr>
            <td><code>button_in_archive</code></td>
            <td>Enables or disables the add and remove buttons from the archive pages.</td>
        </tr>
        <tr>
            <td><code>button_style</code></td>
            <td>The available options are <code>icon</code>, <code>text</code> or <code>icon_text</code>.</td>
        </tr>
    </tbody>
</table>

#### The custom data filter

Use the `sw_save_data` filter to add more information to each product saved in the wishlist. The data is stored in the usermeta table, with the `sw_data` meta key.

**Example:** Adding the date when the product was added:

```
add_filter( 'sw_save_data', function( $data ) {
    $data['d'] = current_time( 'timestamp' );
    return $data;
} );
```

### Functions

<table>
    <thead>
        <tr>
            <th>Function</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>\SW\the_buttons( $product_id );</code></td>
            <td>Displays the add and remove buttons. Pass the product ID or the parent product ID in the case of variable products.</td>
        </tr>
        <tr>
            <td><code>\SW\the_template();</code></td>
            <td>Loads the default or the custom template.</td>
        </tr>
        <tr>
            <td><code>\SW\get_user_data( $user_id );</code></td>
            <td>Gets the wishlist data of a certain user. The function returns an array.</td>
        </tr>
        <tr>
            <td><code>\SW\is_in_wishlist( $product_id );</code></td>
            <td>Checks if a product is in the wishlist.</td>
        </tr>
    </tbody>
</table>
