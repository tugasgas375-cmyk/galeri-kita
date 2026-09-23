<?php

return [
    'admin_username' => env('ADMIN_USERNAME', 'admin'),
    'admin_password' => env('ADMIN_PASSWORD', 'rahasia123'),
    'first_date' => env('FIRST_DATE', '2020-01-01'),
    'home_per_page' => (int) env('HOME_PER_PAGE', 6),
];
