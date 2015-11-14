<?php

return [

    // Templates
    'form'          => 'laravel-form-builder::form',
    'text'          => 'fields.text',
    'textarea'      => 'fields.textarea',
    'button'        => 'laravel-form-builder::button',
    'radio'         => 'laravel-form-builder::radio',
    'checkbox'      => 'fields.checkbox',
    'select'        => 'fields.select',
    'choice'        => 'laravel-form-builder::choice',
    'collection'    => 'laravel-form-builder::collection',
    'static'        => 'laravel-form-builder::static',

    'custom_fields' => [
        'password' => 'Reactor\Http\Forms\Fields\PasswordField',
        'color' => 'Reactor\Http\Forms\Fields\ColorField',
    ]
];
