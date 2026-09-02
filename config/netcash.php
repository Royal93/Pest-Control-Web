<?php

return [

    /*
    |--------------------------------------------------------------------
    | Pay Now Service Key
    |--------------------------------------------------------------------
    | This is the GUID from the client's Netcash account:
    | Account Profile -> NetConnector -> Pay Now
    | Put it in .env as NETCASH_SERVICE_KEY, never commit it directly.
    */
    'service_key' => env('NETCASH_SERVICE_KEY'),

    /*
    |--------------------------------------------------------------------
    | Software Vendor Key
    |--------------------------------------------------------------------
    | Only relevant if we ever become a registered Netcash ISV. Until then
    | Netcash's own default value is used for the m2 field.
    */
    'vendor_key' => env('NETCASH_VENDOR_KEY', '24ade73c-98cf-47b3-99be-cc7b867b3080'),

    /*
    |--------------------------------------------------------------------
    | Test Mode
    |--------------------------------------------------------------------
    | Mirrors the "Make test mode active" checkbox in the client's
    | NetConnector profile. Keep this true until the client is ready to
    | accept real payments, then flip both this and the Netcash setting.
    */
    'test_mode' => env('NETCASH_TEST_MODE', true),

    /*
    |--------------------------------------------------------------------
    | Pay Now endpoint
    |--------------------------------------------------------------------
    */
    'pay_now_url' => 'https://paynow.netcash.co.za/site/paynow.aspx',

];
