<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id'     => '461656770702888',
        'client_secret' => '762642f898568264ab91dc02be28c50c',
        'redirect'      => 'http://recall.dev/auth/facebook/callback',
    ],

    'twitter' => [
        'client_id'     => 'x1cPahHxZzIS8ajAOqBfVClQy',
        'client_secret' => 'I2WH8Xqj5Eb9VrD9ipvo3EccxcAWntPozZ5BMjKzVObl8Tuy3V',
        'redirect'      => 'http://recall.dev/auth/twitter/callback',
    ],

    'bibles' => [
        'key' => 'JnosQMR5Zmdf0qPCEeaaR90zehwMBD6mf0JybEXa',
    ],

];