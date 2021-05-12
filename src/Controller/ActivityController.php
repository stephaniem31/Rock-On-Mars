<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\GatheringManager;
use App\Model\MemberManager;
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
        session_start();

        $activityManager = new ActivityManager();

        $participantsId = (new GatheringManager())->selectAllParticipantsbyActivityId((int)$_GET['id']);

        $participantsName = [];

        foreach ($participantsId as $key) {
            foreach ($key as $participantId) {
                array_push($participantsName, ((new MemberManager())->selectOnlyNameById((int)$participantId)));
            }
        }

        return $this->twig->render('Activity/show.html.twig', [
            'activity' =>
            $activityManager->selectOneAndJoinMemberById($activityManager->selectOneById((int)$_GET['id'])),
            'user' => $_SESSION['user'],
            'participants' => $participantsName
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

            $hasNotErrors = false;

            foreach ($errors as $error) {
                $error === null ?  $hasNotErrors = true : $hasNotErrors = false;
            }

            if ($hasNotErrors === true) {
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
                $activityId =  (new ActivityManager())->insert($activity);

                $idArray = [
                    'memberid' => $_SESSION['user']['id'],
                    'activityid' => $activityId
                ];

                (new GatheringManager())->insert($idArray);
                header('Location:/activity/index');
            }
        }
        return $this->twig->render('Activity/add.html.twig', [ 'errors' => $errors]);
    }

    public function join()
    {
        $_GET = array_map('intval', $_GET);

        (new GatheringManager())->insert($_GET);
        header('Location:/activity/show/?id=' . $_GET['activityid']);
    }

    public function myactivities()
    {
        session_start();

        if ($_GET['user'] !== $_SESSION['user']['name']) {
            header('Location: /activity/index');
        } else {
            return $this->twig->render('Activity/myactivities.html.twig', [
                'myactivities' => (new ActivityManager())->selectAllByMemberId($_SESSION['user']['id'])
            ]);
        }
    }
}
