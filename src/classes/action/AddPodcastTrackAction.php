<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action{

    public function get(): string
    {
        return <<<HTML
        <form method="post" action="?action=add-track" enctype="multipart/form-data">
            <label for="track-name">Nom du track :</label>
            <input type="text" id="track-name" name="track_name" required>
            
            <label for="track-author">Auteur :</label>
            <input type="text" id="track-author" name="track_author" required>

            <label for="track-date">Date :</label>
            <input type="text" id="track-date" name="track_date" required>

            <label for="track-genre">Genre :</label>
            <input type="text" id="track-genre" name="track_genre" required>

            <label for="track-file">Fichier audio (MP3) :</label>
            <input type="file" id="track-file" name="track_file" accept=".mp3" required>

            <button type="submit">Créer le track</button>
        </form>
        HTML;
    }



    protected function post(): string
    {
        $track_name = htmlspecialchars(filter_var($_POST['track_name'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');
        $track_author = htmlspecialchars(filter_var($_POST['track_author'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');
        $track_date = htmlspecialchars(filter_var($_POST['track_date'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');
        $track_genre = htmlspecialchars(filter_var($_POST['track_genre'], FILTER_SANITIZE_SPECIAL_CHARS), ENT_QUOTES, 'UTF-8');

        $destination_dir = realpath(__DIR__ . '/../../../audio') . '/';
        $nom_random = uniqid('track_', true) . '.mp3'; // normalement ça donne un nom unique donc pas de probleme
        $destination_path = $destination_dir . $nom_random;

        if (move_uploaded_file($_FILES['track_file']['tmp_name'], $destination_path)) {
            $chemin_audio = 'audio/' . $nom_random;
            

            $podcast_track = new PodcastTrack($track_name, $chemin_audio, 100);
            $podcast_track->setAuteur($track_author);
            $podcast_track->setDate($track_date);
            $podcast_track->setGenre($track_genre);

            if (isset($_SESSION['playlist']) && is_string($_SESSION['playlist'])) {
                $PL = unserialize($_SESSION['playlist']);
            } else {
                $PL = new Playlist("Playlist par défaut");
            }

            $PL->ajouterPiste($podcast_track);

            // on fait une array, au lieu de passer 300 arguments
            $trackData = [
                'titre' => $track_name,
                'genre' => $track_genre,
                'duree' => 100,  
                'filename' => $chemin_audio,
                'type' => 'A',
                'artiste_album' => $track_author,
                'titre_album' => $PL->nom,
                'annee_album' => $track_date,
                

            ];

            $repo = DeefyRepository::getInstance();
            $playlistId = $repo->findPlaylistIdByName($PL->nom);
            $repo->addTrack($trackData, $playlistId, count($PL->pistes));

            $renderer = new AudioListRenderer($PL);
            $playlist_html = $renderer->render(1);


            return $playlist_html . '<a href="?action=add-track">Ajouter encore une piste</a>';

        } else {
            return "Erreur : Le fichier n'a pas pu être déplacé.";
        }
    }

}
