# Simple Wishlist for WooCommerce

A simple extension for WooCommerce that provides the basic functionality of a wishlist and a set of functions and filters for easy customization.

## Features

- AJAX
- Editable template
- Template action hooks
- Options filter
- Filter for adding additional data to each product in the wishlist
- SVG icons
- 2.6 KB minified JS
- No CSS

## Usage

Out of the box, the plugin provides an add to wishlist and a remove from wishlist button under each product, on both archive pages and single pages. It also provides a new section under My Account, where the wishlist content is displayed.

## Documentation

### The default template

The default template used on the My Account page is located at `templates/wishlist.php`. Similar to WooCommerce templates, you can override it by copying it in your theme directory at `simple-wishlist-woocommerce/wishlist.php`.

### Actions

The following actions are present in the template used on the My Account page.

```
wcsw_before_table
wcsw_before_th_thumb
wcsw_after_th_thumb
wcsw_after_th_title
wcsw_after_tbody_start
wcsw_before_td_thumb
wcsw_after_td_thumb
wcsw_after_td_title
wcsw_after_td_actions
wcsw_before_tbody_end
wcsw_after_tbody
wcsw_after_table
```

### Filters

#### The configuration filter

Use the `wcsw_config` filter to change the default options. The default options are the following:

```
array(
    'ajax'                    => true,
    'button_add_icon'         => DIR . 'public/assets/dist/svg/heart-add.svg',
    'button_add_label'        => 'Add to wishlist',
    'button_clear'            => true,
    'button_clear_icon'       => DIR . 'public/assets/dist/svg/clear.svg',
    'button_clear_label'      => 'Clear wishlist',
    'button_default'          => true,
    'button_in_archive'       => true,
    'button_remove_icon'      => DIR . 'public/assets/dist/svg/heart-remove.svg',
    'button_remove_label'     => 'Remove from wishlist',
    'button_style'            => 'icon_text',
    'endpoint'                => 'wishlist',
    'menu_name'               => 'Wishlist',
    'menu_position'           => 2,
    'message_add_error'       => 'The product was not added to your wishlist. Please try again.',
    'message_add_success'     => 'The product was successfully added to your wishlist.',
    'message_add_view'        => 'View wishlist',
    'message_empty'           => 'There are no products in the wishlist yet.',
    'message_empty_label'     => 'Go shop',
    'message_clear_error'     => 'The wishlist was not cleared. Please try again.',
    'message_clear_success'   => 'The wishlist was successfully cleared.',
    'message_remove_error'    => 'The product was not removed from your wishlist. Please try again.',
    'message_remove_success'  => 'The product was successfully removed from your wishlist.',
)
```

**Example:** Changing the menu position.

```
add_filter( 'wcsw_config', function( $config ) {
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

Use the `wcsw_save_data` filter to add more information to each product saved in the wishlist. The data is stored in the usermeta table, with the `wcsw_data` meta key.

**Example:** Adding the date when the product was added:

```
add_filter( 'wcsw_save_data', function( $data ) {
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
            <td><code>\WCSW\button_add_remove( $product_id );</code></td>
            <td>Displays the add and remove buttons. Pass the product ID or the parent product ID in the case of variable products.</td>
        </tr>
        <tr>
            <td><code>\WCSW\button_clear();</code></td>
            <td>Displays the clear wishlist button.</td>
        </tr>
        <tr>
            <td><code>\WCSW\load_template();</code></td>
            <td>Loads the default or the custom template.</td>
        </tr>
        <tr>
            <td><code>\WCSW\get_user_data( $user_id );</code></td>
            <td>Gets the wishlist data of a certain user. The function returns an array.</td>
        </tr>
        <tr>
            <td><code>\WCSW\is_in_wishlist( $product_id );</code></td>
            <td>Checks if a product is in the wishlist.</td>
        </tr>
    </tbody>
</table>
