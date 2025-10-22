<?php

namespace App\Http\Controllers;

use App\Helpers\CompanyHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FaviconController extends Controller
{
    public function show(Request $request)
    {
        try {
            $faviconUrl = CompanyHelper::getCurrentUserCompanyFavicon();

            return redirect($faviconUrl);
        } catch (\Exception $e) {
            return redirect(asset('favicon.ico'));
        }
    }
}
