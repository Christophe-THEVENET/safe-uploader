<?php

namespace App\Controller;

use App\Form\FileType;
use App\Repository\FileRepository;
use App\Services\File\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // **************************** acceuil *****************************
    #[Route('/', name: 'app_home')]
    public function index(FileRepository $fileRepository): Response
    {
        $files = $fileRepository->findAll();

        return $this->render('pages/file/list.html.twig', [
            'files' => $files
        ]);
    }

    // **************************** Créer fichier *****************************
    #[Route('/create', name: 'app_create')]
    public function create(EntityManagerInterface $em, Request $request, FileUploader $uploader): Response
    {

        $form = $this->createForm(FileType::class);
        // on peut ajouter le bouton submit du form ici dans le controleur
        $form->add('Enregistrer', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-outline-secondary',
            ]
        ]);

        //récup la data du formulaire
        $form->handleRequest($request);

        // vérif si envoyé et si contraintes validées
        if ($form->isSubmitted() && $form->isValid()) {
            // pour avoir autocomplétion de File
            /** @var File $data */
            $file = $form->getData();

            // on va chercher a la main car non mappé le fichier a télécharger du formulaire du champ nom mappé 'file'
            if ($uploadedFile = $form->get('file')->getData()) {
                // pour renseigner le filename (slug) en bdd et télécharger le fichier dans l'appli avec le service qu on a crée
                $filename = $uploader->upload($uploadedFile);
                $file->setFilename($filename);
            }

            $em->persist($file);
            $em->flush();
        }

        return $this->render('pages/file/edit.html.twig', [
            'form' => $form
        ]);
    }



    // **************************** afficher image *****************************
    #[Route('/display-image/{filename}', name: 'app_display_image')]
    public function displayImage(string $filename = null)
    {
        if ($filename === null) {
            return new Response();
        }

        $image = $this->getParameter('upload_directory') . '/' . $filename;

        // si pas d image on arrete la
        if (!file_exists($image)) {
            return new Response();
        }

        $filename = basename($filename);

        dump($this->getMimeType($image));

        // vérifie si c'est bien une image sinon on affiche pas
       /*  if (!in_array($this->getMimeType($image), ['jpeg', 'png', 'svg', 'jpg'])) {
            return new Response();
        } */


        header("Content-type: " . $this->getMimeType($image));
        // pour afficher directement dans le navigateur
        header("Content-Disposition: inline; filename=" . basename($image));


        // fonction qui renvoi le fichier en binaire pour afficher
        readfile($image);

        return new Response();
    }

    // **************************** fct vérifie le type mime *****************************
    private function getMimeType($file): string
    {
        $guesser = new MimeTypes();
        return $guesser->guessMimeType($file);
    }
}
