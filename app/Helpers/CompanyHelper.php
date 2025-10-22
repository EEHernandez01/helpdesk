<?php

namespace App\Helpers;

use App\Models\Company;

class CompanyHelper
{
    public static function getCurrentUserCompanyLogo()
    {
        $user = auth()->user();
        if (!$user || !$user->empresa_id) {
            return asset('logo.svg');
        }
        $company = Company::find($user->empresa_id);
        if (!$company) {
            return asset('logo.svg');
        }
        return $company->logo_url;
    }
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
    public static function getCurrentUserCompanyFavicon()
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->empresa_id) {
                return asset('favicon.ico');
            }

            $company = Company::find($user->empresa_id);

            if (!$company) {
                return asset('favicon.ico');
            }
            return $company->favicon_url;
        } catch (\Exception $e) {
            return asset('favicon.ico');
        }
    }
    public static function isImageFile($filename)
    {
        if (empty($filename)) {
            return false;
        }
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
    }
}
