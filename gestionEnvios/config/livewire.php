<?php

return [

    'class_namespace' => 'App\\Livewire',

    'view_path' => resource_path('views/livewire'),

    'layout' => 'components.layouts.app',

    'lazy_placeholder' => null,

    'temporary_file_upload' => [
        'disk' => null,
        'rules' => null,
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
        'cleanup' => true,
    ],

    'render_on_redirect' => false,

    'legacy_model_binding' => false,

    'inject_assets' => false,

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],

    'inject_morph_markers' => true,

    'smart_wire_keys' => false,

    'pagination_theme' => 'bootstrap',

    'release_token' => 'a',

    'asset_url' => env('ASSET_URL'),

    'app_url' => env('APP_URL', 'http://localhost:8080/gestionEnvios/gestionEnvios/public'),

    'middleware_group' => 'web',
    
    /*
    |---------------------------------------------------------------------------
    | Livewire Endpoint Middleware
    |---------------------------------------------------------------------------
    */
    
    'middleware' => [],

    /*
    |---------------------------------------------------------------------------
    | Livewire Asset Base URL  
    |---------------------------------------------------------------------------
    */
    
    'asset_base_url' => env('ASSET_URL', env('APP_URL')),

    /*
    |---------------------------------------------------------------------------
    | Livewire Update URI
    |---------------------------------------------------------------------------
    */
    
    'update_uri' => env('LIVEWIRE_UPDATE_URI', '/livewire/update'),

];