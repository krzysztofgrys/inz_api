<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe'   => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'github'   => [
        'client_id'     => 'e340f31111e185994511',
        'client_secret' => 'aea09414257aec45c8fd64a53077b09f223919d9',
        'redirect'      => 'http://crypt.krzysztofgrys.pl:8002/login/github/callback',
    ],
    'twitter'  => [
        'client_id'     => 'e340f31111e185994511',
        'client_secret' => 'aea09414257aec45c8fd64a53077b09f223919d9',
        'redirect'      => 'http://crypt.krzysztofgrys.pl:8002/login/twitter/callback',
    ],
    'facebook' => [
        'client_id'     => '2018368981779356',
        'client_secret' => '56bc298c31d652e69b8e1fca18888edb',
        'redirect'      => 'http://crypt.krzysztofgrys.pl:8002/login/facebook/callback',
    ],
    'google'   => [
        'client_id'     => '715097515227-1i71ae3klr6h3ftij9k0ss4902tku4nm.apps.googleusercontent.com',
        'client_secret' => 'HbmrXle9UiRE3yLAoQ7opVzM',
        'redirect'      => 'http://crypt.krzysztofgrys.pl:8002/login/google/callback',
    ],

];
