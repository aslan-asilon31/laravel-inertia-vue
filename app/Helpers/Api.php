<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Api
{
    protected static $basePath = 'http://livewire-starter-2026.test/api';

    public static function get($endpoint)
    {
        return Http::get(self::$basePath . $endpoint)->json();
    }

    public static function post($endpoint, $data = [])
    {
        return Http::post(self::$basePath . $endpoint, $data)->json();
    }

    public static function put($endpoint, $data = [])
    {
        return Http::put(self::$basePath . $endpoint, $data)->json();
    }

    public static function delete($endpoint)
    {
        return Http::delete(self::$basePath . $endpoint)->json();
    }
}
