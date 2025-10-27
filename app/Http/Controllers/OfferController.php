<?php

namespace App\Http\Controllers;

use App\Models\Offers;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function getOffer()
    {

        $data = Offers::select(
            'id',
            'title',
            'description',
            'mission',
            'location',
            'category',
            'employment_type_id',
            'technologies_used',
            'benefits',
            'participants_count',
            'image_url',
            'created_at',
            'updated_at',
            'id_company',
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200); // code reponse 200 pour success
    }

    public function getOfferById($id)
    {

        $offer = Offers::find($id);

        if (!$offer) {
            return response()->json([
                'succes' => false,
                'message' => "Offre non trouvée"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Offre trouvée',
            'data' => $offer
        ], 200);
    }

    public function updateOffer(Request $requestParam, $id)
    {

        $offer = Offers::find($id);

        if (!$offer) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée',
            ], 404);
        }

        $validatedData = $requestParam->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string|max:2000',
            'mission'            => 'required|string|max:255',
            'location'           => 'required|string|max:255',
            'category'           => 'required|string|max:255',

            // Ajouter 'integer' et 'exists' pour la clé étrangère afin de faire le lien avec la table employment_type
            'employment_type_id' => 'required|integer|exists:employment_type,id',
            'technologies_used'  => 'required|string|max:255',
            'benefits'           => 'required|string|max:255',
            'image_url'          => 'required|file|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'title.required'     => 'Le titre est obligatoire.',
            'title.max'          => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.max'    => 'La description ne peut pas dépasser 2000 caractères.',
            'location.required'  => 'Le lieu est obligatoire',
            'category.required'  => 'Le domaine est obligatoire',
            'employment_type_id.required' => 'Le contrat doit être spécifié.',
            'employment_type_id.exists'   => 'Le type de contrat sélectionné n\'existe pas.',
            'image_url.file'     => 'L\'image doit être un fichier.',
            'image_url.mimes'    => 'L\'image doit être au format jpeg, png ou jpg.',
            'image_url.max'      => 'L\'image est trop volumineuse (2mo maximum).',
        ]);

        $offer->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Offre mise à jour avec succès',
            'data' => $offer
        ], 200);
    }

    public function addOffer(Request $requestParam)
    {
        // return response()->json([
        //     'data' => $requestParam
        // ], 200);

        $validatedData = $requestParam->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string|max:2000',
            'mission'            => 'required|string|max:255',
            'location'           => 'required|string|max:255',
            'category'           => 'required|string|max:255',

            // Ajouter 'integer' et 'exists' pour la clé étrangère afin de faire le lien avec la table employment_type
            'employment_type_id' => 'required|integer|exists:employment_type,id',
            'technologies_used'  => 'required|string|max:255',
            'benefits'           => 'required|string|max:255',
            'image_url'          => 'required|file|mimes:jpeg,png,jpg,webp|max:2048',
            'id_company'         => 'required|integer|exists:companys,id'
        ]);


        try {
            $validatedData['image_url'] = $requestParam->file('image_url')->store('photo_offer', 'public');

            $offer = Offers::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'mission' => $validatedData['mission'],
                'location' => $validatedData['location'],
                'category' => $validatedData['category'],
                'employment_type_id' => $validatedData['employment_type_id'],
                'technologies_used' => $validatedData['technologies_used'],
                'benefits' => $validatedData['benefits'],
                'image_url' => $validatedData['image_url'],
                'id_company' => $validatedData['id_company']
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Offre créée avec succès',
                'data' => $offer
            ], 201); // code qui correspond a "la bonne creation de offer"

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de l\'ajout de l\'offre',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function deleteOffer($id)
    {

        $offer = Offers::find($id);

        if (!$offer) {
            return response()->json([
                'succes' => false,
                'message' => 'Offre non trouvée, impossible de la supprimer',
            ], 404);
        }

        try {
            $offer->delete();

            return  response()->json([
                'success' => true,
                'message' => 'Offre supprimée avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de la suppression de l\'offre',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function last3Offers() {}
    public function apply() {}
    public function showCompanyOffer() {}
}
