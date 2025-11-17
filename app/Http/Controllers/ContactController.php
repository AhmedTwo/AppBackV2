<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submitContact(Request $request)
    {
        // on valide les des données du form contact.vue
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // on recup ensuite les des données validées
        $name = $request->input('name');
        $fromEmail = $request->input('email');
        $subject = $request->input('subject');
        $messageContent = $request->input('message');

        // Configuration de PHPMailer pour l'envoi
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = env('MAIL_PORT');
            $mail->CharSet = 'UTF-8';

            // Expéditeur technique : Doit correspondre à l'utilisateur SMTP (pour l'authentification)
            // DOIT être MAIL_USERNAME pour Gmail
            $auth_email = env('MAIL_USERNAME');
            $mail->setFrom($auth_email, 'Formulaire Contact Portal_Job');

            // Destinataire : Votre adresse e-mail personnelle/d'administration
            $mail->addAddress('seghiriahmed9@gmail.com');

            // Adresse de Réponse : L'e-mail de l'utilisateur. C'est ici que vous répondrez.
            $mail->addReplyTo($fromEmail, $name);

            // Contenu du message
            $mail->isHTML(false);
            $mail->Subject = "Nouveau message de contact : " . $subject;
            $mail->Body = "De: {$name} ({$fromEmail})\n\nSujet: {$subject}\n\nMessage:\n{$messageContent}";

            $mail->send();

            // Succès
            return response()->json(['message' => 'Votre message a été envoyé avec succès !'], 200);
        } catch (Exception $e) {
            // Échec
            // Loggez l'erreur pour la déboguer
            Log::error("Erreur d'envoi PHPMailer: " . $mail->ErrorInfo);

            return response()->json(['error' => "Erreur lors de l'envoi du mail. Veuillez réessayer."], 500);
        }
    }
}
