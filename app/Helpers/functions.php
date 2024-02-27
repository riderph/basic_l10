<?php

if (!function_exists('trans_array')) {

    /**
     * Translate with array
     *
     * @param array  $data    Data format
     * @param string $strLocal Local
     * @param bool   $isFlip  Check use flip array
     *
     * @return array
     */
    function trans_array(array $data, $strLocal = null, bool $isFlip = false)
    {
        $locales = $strLocal ? explode(",", $strLocal) : ['en'];
        $arr = [];

        foreach ($data as $key => $value) {
            foreach ($locales as $local) {
                if ($isFlip) {
                    $arr[trans($value, [], $local)] = $key;
                } else {
                    $arr[$key] = trans($value, [], $local);
                }
            }
        }

        return $arr;
    }
}
