<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class DisplayPlaylistAction extends Action {
    
    public function execute(): string {
        $id = $_GET['id'] ?? null;
        $repo = DeefyRepository::getInstance();
    
        try {
            $user = AuthnProvider::getSignedInUser();
            if ($id) {
                Authz::checkPlaylistOwner($id); // active une erreur si faux
                $playlist = $repo->findPlaylistById($id);
    
                if ($playlist === null) return "Pas de playlist trouvée.";
                $_SESSION['playlist'] = serialize($playlist);
    
                $renderer = new AudioListRenderer($playlist);
                $playlistHtml = $renderer->render(1);
    
                return $playlistHtml . '<a href="?action=add-track">Ajouter une piste</a>';
            } else {
                $playlists = $repo->findAllAccessiblePlaylists($user['id'], $user['role']);
                if (empty($playlists)) return "<p>Aucune playlist accessible.</p>";
    
                $html = "<h2>Playlists Disponibles</h2><ul>";
                foreach ($playlists as $pl) {
                    $html = $html . "<li><a href='?action=displayPlaylist&id={$pl['id']}'>{$pl['nom']}</a></li>";
                }
    
                return $html . "</ul>";
            }
        } catch (AuthnException $e) {
            return "<p>" . $e->getMessage() . "</p>";
        }
    }
    
}
