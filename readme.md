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

The default template used on the My Account page is located at `templates/wishlist.php`. Similar to WooCommerce templates, you can override it by copying it in your theme directory at `woocommerce-simple-wishlist/wishlist.php`.

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

Use this filter to change the default options.

```
wcsw_config
```

The default options are the following:

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

`ajax`

- Boolean
- Enables or disables AJAX support. If the value is set to `false` the external script file is not loaded

`button_default`

- Boolean
- Enables or disables the add, remove and clear buttons

`button_add_icon`, `button_clear_icon`, `button_remove_icon`

- String
- The relative path of the icon

`button_add_label`, `button_clear_label`, `button_remove_label`

- String
- The label of the button

`button_clear`

- Boolean
- Enables or disables the clear wishlist button from the My Account page

`button_in_archive`

- Boolean
- Enables or disables the add and remove buttons from the archive pages

`button_style`

- `icon` | `text` | `icon_text`

#### The custom data filter

Use this filter to add more information to the product data or remove the default data. The data is saved in the usermeta table, with the meta_key `wcsw_data`.

```
wcsw_save_data
```

**Example:** Adding the date when the product was added:

```
add_filter( 'wcsw_save_data', function( $data ) {
    $data['d'] = current_time( 'timestamp' );
    return $data;
} );
```

### Functions

#### Add and remove buttons

Displays the buttons for adding a product to the wishlist and removing a product from the wishlist. Pass the product ID or the parent product ID in the case of variable products.

```
\WCSW\button_add_remove( $product_id );
```

#### Clear wishlist button

Displays the clear wishlist button.

```
\WCSW\button_clear();
```

#### Load template

Loads the default or the custom template.

```
\WCSW\load_template();
```

#### User data

Gets the wishlist data of a certain user. The function returns an array.

```
\WCSW\get_user_data( $user_id );
```

#### Conditional

Checks if a product is in the wishlist.

```
\WCSW\is_in_wishlist( $product_id );
```
