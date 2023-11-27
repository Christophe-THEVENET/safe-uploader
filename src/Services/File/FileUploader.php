<?php

namespace App\Services\File;

use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
        private string $upload_directory
    ) {
    }

    public function upload(UploadedFile $file, ?string $directory = null): string
    {
        // récup du nom de fichier original
        $original_filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // on slug le nom du fichier pour sup les accents, espace etc...
        $safe_filename = $this->slugger->slug($original_filename);
        // récupere l'extention
        $explode = explode('.', $file->getClientOriginalName());

        // si ya plusieurs extentions on récup sinon on récup la derniere chose du explode
        $extension = '' !== $file->guessExtension() ? $file->guessExtension() : end($explode);

        $fileName = $safe_filename . '-' . uniqid() . '.' . $extension;

        try {

            // l'emplacement des dossiers téléchargés doit être paramétré dans la config de symfony services.yaml

            // on peut test si un directory et passé en param alors on l crée dans le dossiers upload
            $full_dir = $this->upload_directory . ($directory ? '/' . $directory : '');
            // Filesystem permet de gérer les dossiers, fichiers, droits utilisateur, etc...
            $fs = new Filesystem();
            

            // si le dossier /data/uploads n'existe pas il faut le créer.
            if (!$fs->exists($full_dir)) {
                $fs->mkdir($full_dir);
            };

            // ondéplace le fichier téléchargé du dossier temp au dossier indiqué avec son nouveau nom.
            $file->move($full_dir, $fileName);
        } catch (Exception $e) {
            // renvoyer et gérer les erreurs ex: !!! attention une erreur et survenu
        }

        return $fileName;
    }
}
