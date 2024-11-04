<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;

class AddPlaylistAction extends Action {

    protected function get(): string
    {
        return <<<HTML
        <p></p>
        <form method="post" action="?action=add-playlist">
            <label for="playlist-name">Nom de la playlist :</label>
            <input type="text" id="playlist-name" name="playlist_name" required>
            <button type="submit">Créer la playlist</button>
        </form>
        HTML;
    }

    protected function post(): string
    {
        $nomPlaylist = filter_var($_POST['playlist_name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $user = AuthnProvider::getSignedInUser();
        $repo = DeefyRepository::getInstance();
        $repo->addPlaylist($user['id'], $nomPlaylist);

        $playlist = new Playlist($nomPlaylist);

        $_SESSION['playlist'] = serialize($playlist);

        $renderer = new AudioListRenderer($playlist);
        $playlist_html = $renderer->render(1);

        return $playlist_html . '<a href="?action=add-track">Ajouter une piste</a>';
    }
}
