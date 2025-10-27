<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getUser()
    {
        $data = Users::select(
            'id',
            'nom',
            'prenom',
            'email',
            'password',
            'role',
            'telephone',
            'ville',
            'code_postal',
            'cv_pdf',
            'qualification',
            'preference',
            'disponibilite',
            'photo',
            'created_at',
            'updated_at',
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200); // code reponse 200 pour success
    }

    public function getUserById($id)
    {

        $user = Users::find($id);

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur trouvé',
            'data' => $user,
        ], 200);
    }

    public function updateUser(Request $requestParam, $id)
    {

        // on trouve l'utilisateur
        $user = Users::find($id);

        // on verifie s'il existe
        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        // on valide les données reçues
        $validatedData = $requestParam->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'ville' => 'sometimes|string|max:50',
            'code_postal' => 'sometimes|string|max:20',
            'cv_pdf' => 'sometimes|string|max:255',
            'qualification' => 'sometimes|string|max:255',
            'preference' => 'sometimes|string|max:255',
            'disponibilite' => 'sometimes|string|max:255',
            'photo' => 'file|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nom.required'    => 'Le nom est obligatoire.',
            'prenom.required'    => 'Le prenom est obligatoire.',
            'telephone.max'   => 'La telephone ne peut pas dépasser 20 caractères.',
            'photo.file'        => 'L\'image doit être un fichier.',
            'photo.mimes'       => 'L\'image doit être au format jpeg, png ou jpg.',
            'photo.max'         => 'L\'image est trop volumineuse (2mo maximum).',
        ]);

        // on les met à jour
        // la methode `update()` met à jour les attributs et sauvegarde en bdd
        $user->update($validatedData);

        // on retourne la réponse
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur trouvé et mis à jour avec succès',
            'data' => $user, // user màj
        ], 200);
    }

    public function addUser(Request $requestParam)
    {

        $validatedData = $requestParam->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
            'telephone' => 'required|string|max:20',
            'ville' => 'required|string|max:50',
            'code_postal' => 'required|string|max:20',
            'cv_pdf' => 'required|file|mimes:pdf|max:2048',
            'qualification' => 'required|string|max:255',
            'preference' => 'required|string|max:255',
            'disponibilite' => 'required|string|max:255',
            'photo' => 'required|file|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $validatedData['photo'] = $requestParam->file('photo')->store('photo_user', 'public');
            $validatedData['cv_pdf'] = $requestParam->file('cv_pdf')->store('cv', 'public');

            // Hachage du mot de passe (ÉTAPE CRUCIALE DE SÉCURITÉ)
            $validatedData['password'] = Hash::make($validatedData['password']);

            $email = $validatedData['email'];
            $role = 'candidat'; // rôle par défaut

            if (str_ends_with($email, '@company.com')) {
                // str_ends_with vérifie par quoi se termine $email
                $role = 'company';
            }
            // $role est maintenant soit 'candidat', soit 'company'

            $user = Users::create([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'email' => $email,
                'password' => $validatedData['password'],
                'role' => $role,
                'telephone' => $validatedData['telephone'],
                'ville' => $validatedData['ville'],
                'code_postal' => $validatedData['code_postal'],
                'cv_pdf' => $validatedData['cv_pdf'],
                'qualification' => $validatedData['qualification'],
                'preference' => $validatedData['preference'],
                'disponibilite' => $validatedData['disponibilite'],
                'photo' => $validatedData['photo']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créée avec succès',
                'data' => $user
            ], 201); // code qui correspond a "la bonne creation de offer"

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de l\'ajout de l\'utilisateur',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser($id)
    {

        $user = User::find($id);

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        try {
            $user->delete();

            return  response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Echec de la suppression de l\'utilisateur',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function login() {}
    public function sendEmail() {}
    public function passwordForget() {}
    private function generatePassword() {}
}
