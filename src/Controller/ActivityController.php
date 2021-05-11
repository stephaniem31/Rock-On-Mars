<?php

namespace App\Controller;

use App\Model\ActivityManager;
use Symfony\Component\HttpClient\HttpClient;
use App\Service\ValidationService;

class ActivityController extends AbstractController
{
    /**
     * @var ValidationService Service de validation
     */
    private ValidationService $validationService;


    public function __construct()
    {
        parent::__construct();

        $this->validationService = new ValidationService();
    }

    /**
     * List items
     */
    public function index(): string
    {
        session_start();

        $activityManager = new ActivityManager();
        $activities = $activityManager->selectAll();

        return $this->twig->render('Activity/index.html.twig', [
            'activities' => $activities,
            'user' => $_SESSION['user']
            ]);
    }


    /**
     * Show informations for a specific activity
     */
    public function show(): string
    {
        return $this->twig->render('Activity/show.html.twig', [
            'activity' => (new ActivityManager())->selectOneAndJoinMemberByActivityId((int)$_GET['id'])
        ]);
    }

    /**
     * Add a new activity
     */
    public function add(): string
    {
        session_start();

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            array_push($errors, $this->validationService->checkAddFormEmptiness());
            array_push($errors, $this->validationService->checkAddImage());

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
        }
        return $this->twig->render('Activity/add.html.twig', [ 'errors' => $errors]);
    }
}
