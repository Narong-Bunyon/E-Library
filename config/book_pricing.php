<?php

/*
|--------------------------------------------------------------------------
| Book Pricing Configuration (Static – no database table needed)
|--------------------------------------------------------------------------
|
| access_level values in the books table:
|   0 = Free         – anyone can read & download
|   1 = Subscription – logged-in subscribers can read & download
|   2 = Buy          – must purchase to read & download
|
| Subscription plans and per-book prices are defined here as static lists.
| When a real payment gateway is integrated, these values will drive the
| checkout flow.
|
*/

return [

    /*
    |----------------------------------------------------------------------
    | Access Level Labels
    |----------------------------------------------------------------------
    */
    'labels' => [
        0 => 'Free',
        1 => 'Subscription',
        2 => 'Buy',
    ],

    /*
    |----------------------------------------------------------------------
    | Subscription Plans (static list)
    |----------------------------------------------------------------------
    */
    'subscription_plans' => [
        [
            'name'     => 'Monthly',
            'price'    => 4.99,
            'currency' => 'USD',
            'period'   => 'month',
            'features' => ['Unlimited reading', 'Offline downloads', 'No ads'],
        ],
        [
            'name'     => 'Yearly',
            'price'    => 39.99,
            'currency' => 'USD',
            'period'   => 'year',
            'features' => ['Unlimited reading', 'Offline downloads', 'No ads', 'Save 33%'],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Static Book Prices (keyed by book ID)
    |----------------------------------------------------------------------
    | Only books with access_level = 2 (Buy) need a price entry here.
    | If a book ID is not listed, the default_price is used.
    |----------------------------------------------------------------------
    */
    'default_price'    => 9.99,
    'default_currency' => 'USD',

    'book_prices' => [
        6  => 12.99,  // JavaScript Fundamentals
        18 => 7.49,   // Book Test
    ],

];
