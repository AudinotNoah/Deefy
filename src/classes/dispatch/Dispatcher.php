<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\Login;
use iutnc\deefy\action\Logout;

class Dispatcher {

    private string $action;
    
    public function __construct(string $action) {
        $this->action = $action;
    }

    public function run(): void {
        $html = ''; 

        switch ($this->action) {
            case 'default':
                $actionInstance = new DefaultAction();
                $html = $actionInstance->execute();
                break;
            case 'displayPlaylist':
                $actionInstance = new DisplayPlaylistAction();
                $html = $actionInstance->execute();
                break;

            case 'add-playlist':
                $actionInstance = new AddPlaylistAction();
                $html = $actionInstance->execute();
                break;

            case 'add-track':
                $actionInstance = new AddPodcastTrackAction();
                $html = $actionInstance->execute();
                break;

            case 'add-user': 
                $actionInstance = new AddUserAction();
                $html = $actionInstance->execute();
                break;
            case 'sign-in':
                $actionInstance = new Login();
                $html = $actionInstance->execute();
                break;
            case 'logout':
                $actionInstance = new Logout();
                $html = $actionInstance->execute();
                break;
            default:
                $actionInstance = new DefaultAction();
                $html = $actionInstance->execute();
                break;
        }

        $this->renderPage($html);
    }

    private function renderPage(string $html): void {
        $estco = isset($_SESSION['user']);
        
        $menu = <<<HTML
            <nav>
                <a href="?action=default">Accueil</a>
        HTML;
    
        if ($estco) {
            $menu = $menu . <<<HTML
                <a href="?action=add-playlist">Créer une Playlist</a>
                <a href="?action=displayPlaylist">Voir les Playlists</a>
                <a href="?action=logout">Se Déconnecter</a>
            HTML;
        } else {
            $menu = $menu . <<<HTML
                <a href="?action=add-user">Inscription</a>
                <a href="?action=sign-in">Se Connecter</a>
            HTML;
        }
        
        $menu = $menu . "</nav>";
    
        echo <<<HTML
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>projet web</title>
        </head>
        <body>
            $menu
            <main>
                <p></p> <!-- espace -->
                $html
            </main>
        </body>
        </html>
        HTML;
    }
    
    
}
