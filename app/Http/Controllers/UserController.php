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
            'photo' => 'sometimes|file|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            // Messages personnalisés
            'nom.string'        => 'Le nom doit être une chaîne de caractères.',
            'nom.max'           => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.string'     => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max'        => 'Le prénom ne peut pas dépasser 255 caractères.',
            'telephone.string'  => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.max'     => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'ville.string'      => 'La ville doit être une chaîne de caractères.',
            'ville.max'         => 'La ville ne peut pas dépasser 50 caractères.',
            'code_postal.string' => 'Le code postal doit être une chaîne de caractères.',
            'code_postal.max'   => 'Le code postal ne peut pas dépasser 20 caractères.',
            'cv_pdf.string'     => 'Le chemin du CV doit être une chaîne de caractères.',
            'cv_pdf.max'        => 'Le chemin du CV ne peut pas dépasser 255 caractères.',
            'qualification.string' => 'La qualification doit être une chaîne de caractères.',
            'qualification.max'    => 'La qualification ne peut pas dépasser 255 caractères.',
            'preference.string' => 'La préférence doit être une chaîne de caractères.',
            'preference.max'    => 'La préférence ne peut pas dépasser 255 caractères.',
            'disponibilite.string' => 'La disponibilité doit être une chaîne de caractères.',
            'disponibilite.max'    => 'La disponibilité ne peut pas dépasser 255 caractères.',
            'photo.file'        => 'L\'image doit être un fichier.',
            'photo.mimes'       => 'L\'image doit être au format jpeg, png, jpg ou webp.',
            'photo.max'         => 'L\'image est trop volumineuse (2 Mo maximum).',
        ]);

        // on met à jour l'utilisateur
        $user->update($validatedData);

        // on retourne la réponse
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur trouvé et mis à jour avec succès',
            'data' => $user, // utilisateur mis à jour
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
        ], [
            // Messages personnalisés
            'nom.required'         => 'Le nom est obligatoire.',
            'nom.string'           => 'Le nom doit être une chaîne de caractères.',
            'nom.max'              => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required'      => 'Le prénom est obligatoire.',
            'prenom.string'        => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max'           => 'Le prénom ne peut pas dépasser 255 caractères.',
            'email.required'       => 'L\'adresse e-mail est obligatoire.',
            'email.string'         => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email'          => 'Le format de l\'adresse e-mail n\'est pas valide.',
            'email.max'            => 'L\'adresse e-mail ne peut pas dépasser 255 caractères.',
            'email.unique'         => 'Cette adresse e-mail est déjà utilisée.',
            'password.required'    => 'Le mot de passe est obligatoire.',
            'password.string'      => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min'         => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.max'         => 'Le mot de passe ne peut pas dépasser 255 caractères.',
            'telephone.required'   => 'Le numéro de téléphone est obligatoire.',
            'telephone.string'     => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.max'        => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'ville.required'       => 'La ville est obligatoire.',
            'ville.string'         => 'La ville doit être une chaîne de caractères.',
            'ville.max'            => 'La ville ne peut pas dépasser 50 caractères.',
            'code_postal.required' => 'Le code postal est obligatoire.',
            'code_postal.string'   => 'Le code postal doit être une chaîne de caractères.',
            'code_postal.max'      => 'Le code postal ne peut pas dépasser 20 caractères.',
            'cv_pdf.required'      => 'Le CV est obligatoire.',
            'cv_pdf.file'          => 'Le CV doit être un fichier.',
            'cv_pdf.mimes'         => 'Le CV doit être au format PDF.',
            'cv_pdf.max'           => 'Le CV est trop volumineux (2 Mo maximum).',
            'qualification.required' => 'La qualification est obligatoire.',
            'qualification.string'   => 'La qualification doit être une chaîne de caractères.',
            'qualification.max'      => 'La qualification ne peut pas dépasser 255 caractères.',
            'preference.required'  => 'La préférence est obligatoire.',
            'preference.string'    => 'La préférence doit être une chaîne de caractères.',
            'preference.max'       => 'La préférence ne peut pas dépasser 255 caractères.',
            'disponibilite.required' => 'La disponibilité est obligatoire.',
            'disponibilite.string'   => 'La disponibilité doit être une chaîne de caractères.',
            'disponibilite.max'      => 'La disponibilité ne peut pas dépasser 255 caractères.',
            'photo.required'       => 'La photo est obligatoire.',
            'photo.file'           => 'L\'image doit être un fichier.',
            'photo.mimes'          => 'L\'image doit être au format jpeg, png, jpg ou webp.',
            'photo.max'            => 'L\'image est trop volumineuse (2 Mo maximum).',
        ]);

        try {
            // Sauvegarde des fichiers
            $validatedData['photo'] = $requestParam->file('photo')->store('photo_user', 'public');
            $validatedData['cv_pdf'] = $requestParam->file('cv_pdf')->store('cv', 'public');

            // Hachage du mot de passe
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Détermination du rôle
            $email = $validatedData['email'];
            $role = str_ends_with($email, '@company.com') ? 'company' : 'candidat';

            // Création de l'utilisateur
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
                'photo' => $validatedData['photo'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Échec de l\'ajout de l\'utilisateur.',
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
