<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('Letter_space', function ($attribute, $value, $parameters) {

            return preg_match('/^([\pL ]+)$/u', $value, $matches);
        },'Input contains symbols that are not part of unicode Letter group or space character');

        Validator::extend('Letter_num_space', function ($attribute, $value, $parameters) {
            return preg_match('/^([\pL0-9 ]+)$/u', $value, $matches);
        },'Input contains symbols that are not part of unicode Letter group, numbers or space characters');

        Validator::extend('text', function ($attribute, $value, $parameters) {
            return preg_match('/^([\pL\pP0-9 ]+)$/u', $value);
        },'Input contains symbols that are not part of unicode letter, punctuation group,numbers or space characters');


        Validator::extend('phone', function ($attribute, $value, $parameters) {
            return preg_match('/^([\+]{0,1}[0-9]{9,})$/', $value);
        },'Input is not a phone number');

    }
}
