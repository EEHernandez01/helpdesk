<?php

use App\Helpers\CompanyHelper;

if (!function_exists('isImageFile')) {
    /**
     * Verificar si un archivo es una imagen basado en su extensión
     *
     * @param string $filename
     * @return bool
     */
    function isImageFile($filename)
    {
        return CompanyHelper::isImageFile($filename);
    }
}
