<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Tax Rate
    |--------------------------------------------------------------------------
    |
    | This default tax rate will be used when you make a class implement the
    | Taxable interface and use the HasTax trait.
    |
    */
    'tax' => 21, // Set to your preferred tax rate (e.g., 21 for 21%)


    /*
    |--------------------------------------------------------------------------
    | Database settings
    |--------------------------------------------------------------------------
    |
    | Here you can set the connection that the shoppingcart should use when
    | storing and restoring a cart.
    |
    */
    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'table' => 'shopping_cart',
    ],

    /*
    |--------------------------------------------------------------------------
    | Destroy the cart on user logout
    |--------------------------------------------------------------------------
    |
    | When this option is set to 'true' the cart will automatically
    | destroy all cart instances when the user logs out.
    |
    */
    'destroy_on_logout' => false,

    /*
    |--------------------------------------------------------------------------
    | Default number format
    |--------------------------------------------------------------------------
    |
    | This defaults will be used for the formatted numbers if you don't
    | set them in the method call.
    |
    */
    'format' => [
        'decimals' => 2,
        'decimal_point' => '.',
        'thousand_separator' => '',
    ],
];
