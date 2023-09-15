<?php

return [
    'providers' => [
        'DataProviderX' => [
            'path' => public_path('DataProviderX.json'),
            'amount' => 'parentAmount',
            'currency' => 'Currency',
            'status_codes' => [
                1 => 'authorised',
                2 => 'decline',
                3 => 'refunded',
            ],
        ],
        'DataProviderY' => [
            'path' => public_path('DataProviderY.json'),
            'amount' => 'balance',
            'currency' => 'currency',
            'status_codes' => [
                100 => 'authorised',
                200 => 'decline',
                300 => 'refunded',
            ],
        ],
    ],
];