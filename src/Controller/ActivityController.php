<?php

namespace App\Controller;

use App\Model\ActivityManager;
use Symfony\Component\HttpClient\HttpClient;

class ActivityController extends AbstractController
{

    /**
     * List items
     */
    public function index(): string
    {
        // $activityManager = new ActivityManager();
        // $activity = $activityManager->selectAll();

        return $this->twig->render('Activity/index.html.twig');
    }


    /**
     * Show informations for a specific activity
     */
    public function show($id): string
    {
        $activityManager = new ActivityManager();
        $activity = $activityManager->selectOneById($id);


        // $client = HttpClient::create();
        // $response = $client->request('GET', 'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?sol=1000&api_key=sGRiW62hIIGP2B3zRgfyyJ8bJn7qJeFx5lnza8PT');
        // $content = $response->toArray();

        // var_dump($content);
        // exit;

        return $this->twig->render('Activity/show.html.twig', ['activity' => $activity]);
    }

    /**
     * Add a new activity
     */
    public function add(): string
    {
        $errors = [];
        $arrExtensionsOK = ['jpg','svg','png', 'jpeg'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // echo '<pre>';
            // var_dump($_POST);
            // exit;
            // echo '<pre>';
            $extension = (pathinfo($_POST['image'], PATHINFO_EXTENSION));

            if (
                empty($_POST['title']) || empty($_POST['activity_type'])
                || empty($_POST['content']) || empty($_POST['max_registered_members'])
                || empty($_POST['localisation']) || empty($_POST['start_at'])
                || empty($_POST['end_at']) || empty($_POST['image'])
            ) {
                array_push($errors, 'Veuillez remplir tout les champs.');
            }

            if (!in_array($extension, $arrExtensionsOK)) {
                array_push($errors, "L'url doit amener sur un fichier png, jpg, jpeg ou svg.");
            }

            if (empty($errors)) {
                $activity = [
                    'name' => $_POST['title'],
                    'activity_type' => $_POST['activity_type'],
                    'content' => $_POST['content'],
                    'max_registered_members' => $_POST['max_registered_members'],
                    'localisation' => $_POST['localisation'],
                    'start_at' => $_POST['start_at'],
                    'end_at' => $_POST['end_at'],
                    'image' => $_POST['image'],
                    'member_id' => ($_SESSION['user']['id']),
                ];
                (new ActivityManager())->insert($activity);
                header('Location:/activity/index');
            }
            // header('Location:/activity/index/');
        }

        return $this->twig->render('Activity/add.html.twig', [ 'errors' => $errors]);
    }
}
