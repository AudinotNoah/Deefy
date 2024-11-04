<?php

namespace iutnc\deefy\action;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\auth\AuthnProvider;

class Login extends Action {

    public function get(): string {
        return <<<HTML
        <p></p>
        <form method="POST" action="?action=sign-in">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
          </form>
        HTML;
    }

    protected function post(): string {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        try{
            AuthnProvider::signin($email, $password);
            return "Succes";
        }
        catch (AuthnException $e) {
            return "Erreur de login : " . $e->getMessage();
        }
        
        
    }
}
