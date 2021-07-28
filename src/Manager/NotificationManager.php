<?php

namespace App\Manager;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationManager
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * NotificationManager constructor.
     *
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(
        NotificationRepository $notificationRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * findOtherUsers.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function findOtherUsers(int $id)
    {
        return $this->userRepository->findOtherUsers($id);
    }

    /**
     * createInvitation.
     *
     * @param Notification $notification
     *
     * @return Notification
     */
    public function createInvitation(Notification $notification)
    {
        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }

    /**
     * updateInvitation.
     *
     * @param int $id
     * @param int $status
     */
    public function updateInvitation(int $id, int $status)
    {
        $this->notificationRepository->updateInvitation($id, $status);
    }

    /**
     * getInvitationsSent.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getInvitationsSent(int $id)
    {
        return $this->notificationRepository->getInvitationsSent($id);
    }

    /**
     * getInvitationsReceived.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getInvitationsReceived(int $id)
    {
        return $this->notificationRepository->getInvitationsReceived($id);
    }

    /**
     * getInvitationsReceivedIds.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getInvitationsReceivedIds(int $id)
    {
        return $this->notificationRepository->getInvitationsReceivedIds($id);
    }

    /**
     * deleteInvitation.
     *
     * @param int $id
     *
     * @return boolean
     */
    public function deleteInvitation(Notification $notification)
    {
        $this->entityManager->remove($notification);
        $this->entityManager->flush();

        return true;
    }

}