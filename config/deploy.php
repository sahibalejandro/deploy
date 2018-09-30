<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sites Directory
    |--------------------------------------------------------------------------
    |
    | The absolute path where all the sites are going to be deployed.
    | Make sure this directory has the correct write permissions.
    |
    */
    'path' => env('DEPLOY_PATH', __DIR__ . '/../sites'),
];
