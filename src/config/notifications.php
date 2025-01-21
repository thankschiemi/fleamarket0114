<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Notification Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the default transport that will be used for
    | sending email-based notifications. You can set it to the transport
    | specified in your .env file (e.g., SMTP, Mailgun, etc.).
    |
    */

    'mail' => [
        'transport' => env('MAIL_MAILER', 'smtp'),
    ],

];
