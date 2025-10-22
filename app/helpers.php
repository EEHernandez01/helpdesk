<?php

use App\Helpers\CompanyHelper;

if (!function_exists('isImageFile')) {
    function isImageFile($filename)
    {
        return CompanyHelper::isImageFile($filename);
    }
}
