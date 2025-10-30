<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    // recup toutes nos request !
    public function getRequest()
    {
        $data = RequestModel::select(
            'id',
            'title',
            'description',
            'type',
            'status',
            'created_at',
            'updated_at',
            'user_id',
            'company_id',
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 201);
    }

    public function getRequestById($id)
    {

        $request = RequestModel::find($id);

        if (!$request) {
            return response()->json([
                'succes' => false,
                'message' => "Demande non trouvée"
            ], 404);
        }

        return response()->json([
            'succes' => true,
            'message' => 'Demande trouvée',
            'data' => $request
        ], 200);
    }

    public function updateRequest(Request $requestParam, $id)
    {
        $request = RequestModel::find($id);

        if (!$request) {
            return response()->json([
                'success' => false,
                'message' => 'Demande non trouvée.',
            ], 404);
        }

        $validatedData = $requestParam->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2000',
            'type'        => 'sometimes|string|max:255|in:RECLAMATION,DEMANDES,SUPPRESSION,MODIFICATION',
        ], [
            'title.string'       => 'Le titre doit être une chaîne de caractères.',
            'title.max'          => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max'    => 'La description ne peut pas dépasser 2000 caractères.',
            'type.string'        => 'Le type doit être une chaîne de caractères.',
            'type.in'            => 'Le type doit être l\'une des valeurs suivantes : RECLAMATION, DEMANDES, SUPPRESSION ou MODIFICATION.',
        ]);

        $request->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Demande mise à jour avec succès.',
            'data'    => $request,
        ], 200);
    }

    public function addRequest(Request $requestParam)
    {
        $validatedData = $requestParam->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type'        => 'required|string|max:255|in:RECLAMATION,DEMANDES,SUPPRESSION,MODIFICATION',
            'user_id'     => 'nullable|integer|exists:users,id|required_without:company_id',
            'company_id'  => 'nullable|integer|exists:companys,id|required_without:user_id',
            // forcer au moins un des deux : → ajouter required_without et la colonne en question
        ], [
            'title.required'       => 'Le titre est obligatoire.',
            'title.string'         => 'Le titre doit être une chaîne de caractères.',
            'title.max'            => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.string'   => 'La description doit être une chaîne de caractères.',
            'description.max'      => 'La description ne peut pas dépasser 2000 caractères.',
            'type.required'        => 'Le type est obligatoire.',
            'type.string'          => 'Le type doit être une chaîne de caractères.',
            'type.in'              => 'Le type doit être l\'une des valeurs suivantes : RECLAMATION, DEMANDES, SUPPRESSION ou MODIFICATION.',
            'user_id.integer'      => 'L\'identifiant de l\'utilisateur doit être un nombre entier.',
            'user_id.exists'       => 'L\'utilisateur spécifié n\'existe pas.',
            'company_id.integer'   => 'L\'identifiant de la société doit être un nombre entier.',
            'company_id.exists'    => 'La société spécifiée n\'existe pas.',
        ]);

        try {
            $request = RequestModel::create([
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'type'        => $validatedData['type'],
                'user_id'     => $validatedData['user_id'] ?? null,
                'company_id'  => $validatedData['company_id'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Demande créée avec succès.',
                'data'    => $request,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la création de la demande.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteRequest($id)
    {

        $request = RequestModel::find($id);

        if (!$request) {

            return response()->json([
                'success' => false,
                'message' => 'Demande non trouvé',
            ], 404);
        }

        try {
            $request->delete();

            return  response()->json([
                'success' => true,
                'message' => 'Demande supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de la suppression de la demande',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function upadateRequest() {}
}
