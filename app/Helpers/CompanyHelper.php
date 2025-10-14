<?php

namespace App\Helpers;

use App\Models\Company;

class CompanyHelper
{
    public static function getCurrentUserCompanyLogo()
    {
        $user = auth()->user();

        if (!$user || !$user->empresa_id) {
            return asset('logo.svg'); // Logo por defecto
        }

        $company = Company::find($user->empresa_id);

        if (!$company) {
            return asset('logo.svg'); // Logo por defecto
        }

        return $company->logo_url;
    }

    /**
     * Obtener el nombre de la empresa del usuario autenticado
     */
    public static function getCurrentUserCompanyName()
    {
        $user = auth()->user();

        if (!$user || !$user->empresa_id) {
            return 'Sistema';
        }

        $company = Company::find($user->empresa_id);

        if (!$company) {
            return 'Sistema';
        }

        return $company->nombre;
    }

    /**
     * Obtener el favicon de la empresa del usuario autenticado
     */
    public static function getCurrentUserCompanyFavicon()
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->empresa_id) {
                return asset('favicon.ico'); // Favicon por defecto
            }

            $company = Company::find($user->empresa_id);

            if (!$company) {
                return asset('favicon.ico'); // Favicon por defecto
            }

            // Usar el favicon específico de la empresa, o el logo como fallback
            return $company->favicon_url;

        } catch (\Exception $e) {
            return asset('favicon.ico');
        }
    }

    /**
     * Verificar si un archivo es una imagen basado en su extensión
     */
    public static function isImageFile($filename)
    {
        if (empty($filename)) {
            return false;
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
    }
}
