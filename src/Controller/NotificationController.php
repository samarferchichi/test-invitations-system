<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Form\NotificationType;
use App\Manager\NotificationManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

/**
 * Class TestController
 * @Route("/api/notification", name="api_notification_")
 */
class NotificationController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/users")
     * @Rest\View()
     *
     * @param Request $request
     *
     * @return View
     */
    public function getUsers(Request $request, NotificationManager $notificationManager)
    {
        $users = $notificationManager->findOtherUsers($this->getUser()->getId());
        $receivers = $notificationManager->getInvitationsReceivedIds($this->getUser()->getId());

        $data = [];
        foreach($users as $user){
            if(array_search($user->getId(), array_column($receivers, 'id')) === false){
                array_push($data, $user);
            }
        }

        return $this->view($data);
    }

    /**
     * @Rest\Post("/send-invitation")
     *
     * @param Request               $request
     * @param NotificationManager   $notificationManager
     *
     * @Rest\View()
     */
    public function sendInvitation(Request $request, NotificationManager $notificationManager)
    {
        $notification = new Notification();
        $notification->setSender($this->getUser());
        $notification->setStatus(Notification::PENDING_STATUS);
        $dataInput = json_decode($request->getContent(), true);

        $form = $this->createForm(NotificationType::class, $notification);
        $form->submit($dataInput);

        if ($form->isValid()) {
            $notificationManager->createInvitation($notification);

            return View::create($notification, Response::HTTP_CREATED);
        }

        return View::create($form);
    }

    /**
     * @Rest\Post("/accept-invitation/{id}")
     *
     * @param Notification        $notification
     * @param NotificationManager $notificationManager
     *
     * @Rest\View()
     */
    public function acceptInvitation(Notification $notification, NotificationManager $notificationManager)
    {
        try {
            $notificationManager->updateInvitation($notification->getId(), Notification::ACCEPTED_STATUS);

            return View::create([
                'status' => Response::HTTP_OK,
                'message' => 'Status changed with success'
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return View::create([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @Rest\Post("/cancel-invitation/{id}")
     *
     * @param Notification        $notification
     * @param NotificationManager $notificationManager
     *
     * @Rest\View()
     */
    public function cancelInvitation(Notification $notification, NotificationManager $notificationManager)
    {
        try {
            $notificationManager->updateInvitation($notification->getId(), Notification::REFUSED_STATUS);

            return View::create([
                'status' => Response::HTTP_OK,
                'message' => 'Status changed with success'
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return View::create([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

     /**
     * @Rest\Get("/invitations-sent")
     *
     * @param Request        $request
     * @param NotificationManager $notificationManager
     *
     * @Rest\View()
     */
    public function invitationsSent(Request $request, NotificationManager $notificationManager)
    {
        return $notificationManager->getInvitationsSent($this->getUser()->getId());
    }

    /**
     * @Rest\Get("/invitations-received")
     *
     * @param Request        $request
     * @param NotificationManager $notificationManager
     *
     * @Rest\View()
     */
    public function invitationsReceived(Request $request, NotificationManager $notificationManager)
    {
        return $notificationManager->getInvitationsReceived($this->getUser()->getId());
    }

        /**
     * @Rest\Post("/delete-invitation/{id}")
     *
     * @param Notification        $notification
     * @param NotificationManager $notificationManager
     *
     * @Rest\View()
     */
    public function deleteInvitation(Notification $notification, NotificationManager $notificationManager)
    {
        try {
            $notificationManager->deleteInvitation($notification);

            return View::create([
                'status' => Response::HTTP_OK,
                'message' => 'Invitation deleted with success'
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return View::create([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
