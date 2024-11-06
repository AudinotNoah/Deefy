<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {
    public static function signin(string $email, string $password) {
        $repo = DeefyRepository::getInstance();
        $user = $repo->findInfos($email);
        print_r($user);

        if (!$user) {
            throw new AuthnException("Email invalide");
        }

        if (!password_verify($password, $user->passwd)) {
            throw new AuthnException("mdp invalide");
        }

        $_SESSION['user'] = [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role
        ];
    }


    public static function register(string $email, string $password, string $passwordConfirmation) {
        if (strlen($password) < 10) {
            throw new AuthnException("Le mot de passe doit contenir au moins 10 caractères.");
        }

        if ($password !== $passwordConfirmation) {
            throw new AuthnException("Les mots de passe ne correspondent pas.");
        }

        $repo = DeefyRepository::getInstance();
        
        $existingUser = $repo->findInfos($email);
        if ($existingUser) {
            throw new AuthnException("Un compte avec cet email existe déjà.");
        }

        $hash = password_hash($password, PASSWORD_BCRYPT); // pas sur de l'algo

        $repo->addUser($email, $hash, 1);
    }

    public static function getSignedInUser(): array {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Pas connecté.");
        }
        return $_SESSION['user'];
    }
}
