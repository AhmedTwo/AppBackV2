<?php

namespace App\Http\Controllers;

use App\Models\Requests as RequestModel;
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
        ], 200); // code reponse 200 pour success
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
                'message' => 'Demande non trouvé',
            ], 404);
        }

        $vaildatedData = $requestParam->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|max:2000',
            'type' => 'required|string|max:255',
        ], [
            'title.required'    => 'Le titre est obligatoire.',
            'title.max'         => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.max'   => 'La description ne peut pas dépasser 2000 caractères.',
            'type.required' => 'Le type doit soit être RECLAMATION, DEMANDES, SUPPRESION ou MODIFICATION',
        ]);

        $request->update($vaildatedData);

        return response()->json([
            'success' => true,
            'message' => 'Demandes trouvée et mis à jour avec succès',
            'data' => $request, // request màj
        ], 200);
    }

    public function addRequest(Request $requestParam)
    {
        $validatedData = $requestParam->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|string|max:255|in:RECLAMATION,DEMANDES,SUPPRESION,MODIFICATION',
            'user_id'      => 'sometimes|integer|exists:users,id',
            'company_id'   => 'sometimes|integer|exists:companys,id'
        ], [
            'title.required'    => 'Le titre est obligatoire.',
            'title.max'         => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.max'   => 'La description ne peut pas dépasser 2000 caractères.',
            'type.in' => 'Le type doit être l\'une des valeurs suivantes : RECLAMATION, DEMANDES, SUPPRESION ou MODIFICATION.'
        ]);

        try {
            $request = RequestModel::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'type' => $validatedData['type'],
                'user_id' => $validatedData['user_id'],
                'company_id' => $validatedData['company_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Demande créée avec succès',
                'data' => $request
            ], 201); // code qui correspond a "la bonne creation de offer"
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de l\'ajout de la demande',
                'error'   => $e->getMessage()
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
