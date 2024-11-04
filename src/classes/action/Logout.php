<?php

namespace iutnc\deefy\action;

class Logout extends Action {
    
    public function execute(): string {
        // Détruit toutes les variables de session
        $_SESSION = [];
        return 'fait';
    }
}
