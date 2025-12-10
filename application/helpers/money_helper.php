<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('inr')) {
    function inr($amount, $decimals = 2)
    {
        $numeric = is_numeric($amount) ? (float) $amount : 0.0;
        return '₹ ' . number_format($numeric, $decimals, '.', ',');
    }
}
