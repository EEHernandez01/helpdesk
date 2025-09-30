<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index()
    {
        $companies = Company::all();
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified company in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'logo' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
            'favicon' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,ico',
                'max:1024'
            ],
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'rfc' => 'nullable|string|max:13',
            'activo' => 'boolean',
        ]);

        // Preparar los datos para la actualización
        $data = $request->except(['logo', 'favicon']);

        // Manejar el checkbox de activo (será 0 si no está marcado, 1 si está marcado)
        $data['activo'] = $request->has('activo') ? 1 : 0;

        // Manejar la subida del logo
        if ($request->hasFile('logo')) {
            // Eliminar el logo anterior si existe
            if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
                Storage::disk('public')->delete('logos/' . $company->logo);
            }

            // Subir el nuevo logo
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('logos', $logoName, 'public');

            $data['logo'] = $logoName;
        }

        // Manejar la subida del favicon
        if ($request->hasFile('favicon')) {
            // Eliminar el favicon anterior si existe
            if ($company->favicon && Storage::disk('public')->exists('favicons/' . $company->favicon)) {
                Storage::disk('public')->delete('favicons/' . $company->favicon);
            }

            // Subir el nuevo favicon
            $favicon = $request->file('favicon');
            $faviconName = time() . '_' . $favicon->getClientOriginalName();
            $favicon->storeAs('favicons', $faviconName, 'public');

            $data['favicon'] = $faviconName;
        }

        $company->update($data);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }
}
