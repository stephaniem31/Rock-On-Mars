<?php

namespace App\Controller;

use App\Model\ItemManager;
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
     * Show informations for a specific item
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
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $activityManager = new ActivityManager();
        $activity = $activityManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $activity = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $activityManager->update($activity);
            header('Location: /activity/show/' . $id);
        }

        return $this->twig->render('Item/edit.html.twig', [
            'activity' => $activity,
        ]);
    }


    /**
     * Add a new item
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $activity = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $activityManager = new ActivityManager();
            $id = $activityManager->insert($activity);
            header('Location:/activity/show/' . $id);
        }

        return $this->twig->render('Activity/add.html.twig');
    }


    /**
     * Delete a specific item
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activityManager = new ActivityManager();
            $activityManager->delete($id);
            header('Location:/activity/index');
        }
    }
}
