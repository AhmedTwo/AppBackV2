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
            'title'              => 'sometimes|string|max:255',
            'description'        => 'sometimes|string|max:2000',
            'mission'            => 'sometimes|string|max:255',
            'location'           => 'sometimes|string|max:255',
            'category'           => 'sometimes|string|max:255',
            // Ajouter 'integer' et 'exists' pour la clé étrangère afin de faire le lien avec la table employment_type
            'employment_type_id' => 'sometimes|integer|exists:employment_type,id',
            'technologies_used'  => 'sometimes|string|max:255',
            'benefits'           => 'sometimes|string|max:255',
            'image_url'          => 'sometimes|file|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            // Champs texte
            'title.string'              => 'Le titre doit être une chaîne de caractères.',
            'title.max'                 => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.string'        => 'La description doit être une chaîne de caractères.',
            'description.max'           => 'La description ne peut pas dépasser 2000 caractères.',
            'mission.string'            => 'La mission doit être une chaîne de caractères.',
            'mission.max'               => 'La mission ne peut pas dépasser 255 caractères.',
            'location.string'           => 'Le lieu doit être une chaîne de caractères.',
            'location.max'              => 'Le lieu ne peut pas dépasser 255 caractères.',
            'category.string'           => 'La catégorie doit être une chaîne de caractères.',
            'category.max'              => 'La catégorie ne peut pas dépasser 255 caractères.',
            'employment_type_id.integer' => 'Le type de contrat doit être un entier valide.',
            'employment_type_id.exists' => 'Le type de contrat sélectionné n\'existe pas.',
            'technologies_used.string'  => 'Les technologies doivent être une chaîne de caractères.',
            'technologies_used.max'     => 'Les technologies ne peuvent pas dépasser 255 caractères.',
            'benefits.string'           => 'Les avantages doivent être une chaîne de caractères.',
            'benefits.max'              => 'Les avantages ne peuvent pas dépasser 255 caractères.',
            'image_url.file'            => 'L\'image doit être un fichier.',
            'image_url.mimes'           => 'L\'image doit être au format jpeg, png, jpg ou webp.',
            'image_url.max'             => 'L\'image est trop volumineuse (2 Mo maximum).',
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
        $validatedData = $requestParam->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string|max:2000',
            'mission'            => 'required|string|max:255',
            'location'           => 'required|string|max:255',
            'category'           => 'required|string|max:255',
            'employment_type_id' => 'required|integer|exists:employment_type,id',
            'technologies_used'  => 'required|string|max:255',
            'benefits'           => 'required|string|max:255',
            'image_url'          => 'required|file|mimes:jpeg,png,jpg,webp|max:2048',
            'id_company'         => 'required|integer|exists:companys,id',
        ], [
            // Champs obligatoires
            'title.required'              => 'Le titre est obligatoire.',
            'title.string'                => 'Le titre doit être une chaîne de caractères.',
            'title.max'                   => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required'        => 'La description est obligatoire.',
            'description.string'          => 'La description doit être une chaîne de caractères.',
            'description.max'             => 'La description ne peut pas dépasser 2000 caractères.',
            'mission.required'            => 'La mission est obligatoire.',
            'mission.string'              => 'La mission doit être une chaîne de caractères.',
            'mission.max'                 => 'La mission ne peut pas dépasser 255 caractères.',
            'location.required'           => 'Le lieu est obligatoire.',
            'location.string'             => 'Le lieu doit être une chaîne de caractères.',
            'location.max'                => 'Le lieu ne peut pas dépasser 255 caractères.',
            'category.required'           => 'La catégorie est obligatoire.',
            'category.string'             => 'La catégorie doit être une chaîne de caractères.',
            'category.max'                => 'La catégorie ne peut pas dépasser 255 caractères.',
            'employment_type_id.required' => 'Le type de contrat est obligatoire.',
            'employment_type_id.integer'  => 'Le type de contrat doit être un entier valide.',
            'employment_type_id.exists'   => 'Le type de contrat sélectionné n\'existe pas.',
            'technologies_used.required'  => 'Les technologies utilisées sont obligatoires.',
            'technologies_used.string'    => 'Les technologies doivent être une chaîne de caractères.',
            'technologies_used.max'       => 'Les technologies ne peuvent pas dépasser 255 caractères.',
            'benefits.required'           => 'Les avantages sont obligatoires.',
            'benefits.string'             => 'Les avantages doivent être une chaîne de caractères.',
            'benefits.max'                => 'Les avantages ne peuvent pas dépasser 255 caractères.',
            'image_url.required'          => 'L\'image est obligatoire.',
            'image_url.file'              => 'L\'image doit être un fichier.',
            'image_url.mimes'             => 'L\'image doit être au format jpeg, png, jpg ou webp.',
            'image_url.max'               => 'L\'image est trop volumineuse (2 Mo maximum).',
            'id_company.required'         => 'L\'entreprise associée est obligatoire.',
            'id_company.integer'          => 'L\'identifiant de l\'entreprise doit être un entier valide.',
            'id_company.exists'           => 'L\'entreprise sélectionnée n\'existe pas.',
        ]);

        try {
            $validatedData['image_url'] = $requestParam->file('image_url')->store('photo_offer', 'public');

            $offer = Offers::create([
                'title'              => $validatedData['title'],
                'description'        => $validatedData['description'],
                'mission'            => $validatedData['mission'],
                'location'           => $validatedData['location'],
                'category'           => $validatedData['category'],
                'employment_type_id' => $validatedData['employment_type_id'],
                'technologies_used'  => $validatedData['technologies_used'],
                'benefits'           => $validatedData['benefits'],
                'image_url'          => $validatedData['image_url'],
                'id_company'         => $validatedData['id_company'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offre créée avec succès.',
                'data' => $offer,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Échec de l\'ajout de l\'offre.',
                'error'   => $e->getMessage(),
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
