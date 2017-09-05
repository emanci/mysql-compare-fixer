<?php

if (!function_exists('convert_hump')) {
    /**
     * @param string $str
     * @param bool   $ucfirst
     *
     * @return string
     */
    function convert_hump($str, $ucfirst = true)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));

        return $ucfirst ? $str : lcfirst($str);
    }
}
