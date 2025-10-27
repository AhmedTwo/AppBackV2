<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Companys;

class CompanyController extends Controller
{
    public function getCompany()
    {

        $data = Companys::select(
            'id',
            'name',
            'logo',
            'number_of_employees',
            'industry',
            'address',
            'latitude',
            'longitude',
            'description',
            'email_company',
            'n_siret',
            'status',
            'created_at',
            'updated_at',
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200); // code reponse 200 pour success
    }

    public function getCompanyById($id)
    {
        $company = Companys::find($id);

        if (!$company) {
            return response()->json([
                'succes' => false,
                'message' => "Société non trouvée"
            ], 404);
        }

        return response()->json([
            'succes' => true,
            'message' => 'Société trouvée',
            'data' => $company
        ], 200);
    }

    // fonctionne pas ?
    public function updateCompany(Request $requestParam, $id)
    {

        $company = Companys::find($id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Société non trouvée',
            ], 404);
        }

        $validatedData = $requestParam->validate([
            'name'               => 'required|string|max:255',
            'logo'               => 'nullable|string|max:255', // si pas d’upload
            'number_of_employees'  => 'required|integer',
            'industry'           => 'required|string|max:255',
            'address'           => 'required|string|max:255',
            'latitude'          => 'required|string|max:255',
            'longitude'             => 'required|string|max:255',
            'description'           => 'required|string|max:255',
            'email_company' => 'required|string|email|max:255|unique:companys,email_company,' . $id,
            'n_siret'          => 'required|string|min:14|max:14'
        ], [
            'name.required'     => 'Le nom est obligatoire.',
            'name.max'          => 'Le nom ne peut pas dépasser 255 caractères.',
            'number_of_employees.max'    => 'La nombre d\'employé doit $etre un chiffre.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            'email_company.required'     => 'Le mail est déjà utilisé.',
            'logo.file'     => 'L\'image doit être un fichier.',
            'logo.mimes'    => 'L\'image doit être au format jpeg, png, jpg ou webp.',
            'logo.max'      => 'L\'image est trop volumineuse (2mo maximum).',
        ]);

        $company->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Société mise à jour avec succès',
            'data' => $company
        ], 200);
    }

    // fonctionne pas ?
    public function addCompany(Request $requestParam)
    {

        $validatedData = $requestParam->validate([
            'name'               => 'required|string|max:255',
            'logo'               => 'nullable|string|max:255', // si pas d’upload
            'number_of_employees'  => 'required|integer',
            'industry'           => 'required|string|max:255',
            'address'           => 'required|string|max:255',
            'latitude'          => 'sometimes|string|max:255',
            'longitude'             => 'sometimes|string|max:255',
            'description'           => 'required|string|max:255',
            'email_company' => 'required|string|email|max:255|unique:companys,email_company',
            'n_siret'          => 'required|string|min:14|max:14'
        ]);

        try {
            $validatedData['logo'] = $requestParam->file('logo')->store('photo_company', 'public');

            $company = Companys::create([
                'name' => $validatedData['name'],
                'logo' => $validatedData['logo'],
                'number_of_employees' => $validatedData['number_of_employees'],
                'industry' => $validatedData['industry'],
                'address' => $validatedData['address'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'description' => $validatedData['description'],
                'email_company' => $validatedData['email_company'],
                'n_siret' => $validatedData['n_siret']
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Société créée avec succès',
                'data' => $company
            ], 201); // code qui correspond a "la bonne creation de offer"

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de l\'ajout de la société',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCompany($id)
    {

        $company = Companys::find($id);

        if (!$company) {
            return response()->json([
                'succes' => false,
                'message' => 'Société non trouvée, impossible de la supprimer',
            ], 404);
        }

        try {
            $company->delete();

            return  response()->json([
                'success' => true,
                'message' => 'Société supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de la suppression de la Société',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function applyCompany() {}
    public function toggleStatus() {}
}
