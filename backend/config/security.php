<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public Form Origin Enforcement
    |--------------------------------------------------------------------------
    |
    | The inquiry endpoint is intentionally unauthenticated. In production,
    | require an exact browser Origin match in addition to CORS, rate limits,
    | validation, and the honeypot.
    |
    */
    'require_origin_header' => (bool) env('REQUIRE_ORIGIN_HEADER', false),
];
