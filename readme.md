# Simple Wishlist for WooCommerce

A simple extension for WooCommerce that provides the basic functionality of a wishlist and a set of functions and filters for easy customization.

## Features

- Powered by AJAX
- Usage of WordPress nonces for extra protection
- Customization filter
- Data saved in the usermeta table as JSON
- Filter for adding additional data to each product in the wishlist
- SVG icons
- 2.6 KB minified JS
- No CSS

## Documentation

### The Default Template

The default template used on the My Account page is located at `templates/wishlist.php`. You can override it by copying it in your theme directory at `woocommerce-simple-wishlist/wishlist.php`.

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
wcsw_after_clear_button
```

### Filters

#### The Configuration Filter

Use this filter to change the default options.

```
wcsw_config
```

The default options are the following:

```
array(
    'ajax'                   => true,
    'button_add_icon'        => DIR . 'public/assets/dist/svg/heart-add.svg',
    'button_add_label'       => 'Add to wishlist',
    'button_clear'           => true,
    'button_clear_icon'      => DIR . 'public/assets/dist/svg/clear.svg',
    'button_clear_label'     => 'Clear wishlist',
    'button_in_archive'      => true,
    'button_remove_icon'     => DIR . 'public/assets/dist/svg/heart-remove.svg',
    'button_remove_label'    => 'Remove from wishlist',
    'button_style'           => 'icon_text',
    'menu_name'              => 'Wishlist',
    'menu_position'          => 2,
    'message_add_error'      => 'The product was not added to your wishlist. Please try again.',
    'message_add_success'    => 'The product was successfully added to your wishlist.',
    'message_add_view'       => 'View wishlist',
    'message_empty'          => 'There are no products in the wishlist yet.',
    'message_empty_label'    => 'Go shop',
    'message_clear_error'    => 'The wishlist was not cleared. Please try again.',
    'message_clear_success'  => 'The wishlist was successfully cleared.',
    'message_remove_error'   => 'The product was not removed from your wishlist. Please try again.',
    'message_remove_success' => 'The product was successfully removed from your wishlist.',
)
```

##### Examples

Changing the menu position.

```
add_filter( 'wcsw_config', function( $config ) {
    $config['menu_position'] = 3;
    return $config;
} );
```

#### The Custom Data Filter

Use this filter to add more information to the product data or remove the default data.

```
wcsw_save_data
```

##### Examples

Adding the date.

```
add_filter( 'wcsw_save_data', function( $data ) {
    $data['d'] = current_time( 'timestamp' );
    return $data;
} );
```
