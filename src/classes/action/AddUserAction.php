<?php

namespace iutnc\deefy\action;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\auth\AuthnProvider;



class AddUserAction extends Action {
    public function get(): string {
        return <<<HTML
        <p></p>
        <form method="post" action="?action=add-user">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" required>
            
            <label for="password_confirmation">Confirmez le mot de passe:</label>
            <input type="password" name="password_confirmation" required>
            
            <button type="submit">S'inscrire</button>
        </form>
        HTML;
    }

    protected function post(): string {
        $email = $_POST['email'];
        $password = $_POST['password'] ;
        $passwordConfirmation = $_POST['password_confirmation'];

        try {
            AuthnProvider::register($email, $password, $passwordConfirmation);
            return "Inscription réussie ! ";
        } catch (AuthnException $e) {
            return "Erreur d'inscription : " . $e->getMessage();
        }
    }
}
