<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('format_department_name')) {
    function format_department_name($department)
    {
        $department = trim((string) $department);
        if ($department === '') {
            return '';
        }

        $known_departments = array(
            'PRASOOTI_STRIROGA' => 'Prasooti & Striroga',
            'PRASOOTI_&_STRIROGA' => 'Prasooti & Striroga'
        );
        $key = strtoupper($department);

        if (isset($known_departments[$key])) {
            return $known_departments[$key];
        }

        return ucwords(strtolower(str_replace('_', ' ', $department)));
    }
}

if (!function_exists('format_discharge_date')) {
    function format_discharge_date($date, $include_time = false)
    {
        $date = trim((string) $date);
        if ($date === '' || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return '';
        }

        $timestamp = strtotime($date);
        return $timestamp ? date($include_time ? 'd-m-Y H:i' : 'd-m-Y', $timestamp) : $date;
    }
}

if (!function_exists('format_medicine_bullets')) {
    function format_medicine_bullets($medicine_text)
    {
        $medicine_text = trim((string) $medicine_text);
        if ($medicine_text === '') {
            return '';
        }

        $items = preg_split('/[\r\n,]+/', $medicine_text);
        $html = '<ul style="margin:0;padding-left:16px;">';
        foreach ($items as $item) {
            $item = trim($item);
            if ($item !== '') {
                $html .= '<li style="margin-bottom:1px;">' . html_escape($item) . '</li>';
            }
        }

        return $html . '</ul>';
    }
}
