<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\AuthnException;


class Authz {
    public static function checkRole(int $requiredRole): void {
        $user = AuthnProvider::getSignedInUser();
        if ($user['role'] !== $requiredRole) {
            throw new AuthnException("Pas acces");
        }
    }

    public static function checkPlaylistOwner(int $playlistId): void {
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();

        $ownerId = $repo->findPlaylistOwner($playlistId);
        if ($user['id'] != $ownerId && $user['role'] != 100) {
            throw new AuthnException("Vous n'avez pas le droit de voir cette playlist");
        }
    }
}
