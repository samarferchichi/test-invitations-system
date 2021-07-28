<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Email(mode = "html5")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="sender", cascade={"persist", "remove", "merge"})
     */
    public $senderNotifications;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="receiver", cascade={"persist", "remove", "merge"})
     */
    public $receiverNotifications;

    /**
     * User constructor.
     * @param $username
     */
    public function __construct()
    {
//        $this->username = $username;
        $this->senderNotifications = new ArrayCollection();
        $this->receiverNotifications = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return array|string[]
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getSenderNotifications(): Collection
    {
        return $this->senderNotifications;
    }

    public function addSenderNotification(Notification $senderNotification): self
    {
        if (!$this->senderNotifications->contains($senderNotification)) {
            $this->senderNotifications[] = $senderNotification;
            $senderNotification->setSender($this);
        }

        return $this;
    }

    public function removeSenderNotification(Notification $senderNotification): self
    {
        if ($this->senderNotifications->removeElement($senderNotification)) {
            // set the owning side to null (unless already changed)
            if ($senderNotification->getSender() === $this) {
                $senderNotification->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getReceiverNotifications(): Collection
    {
        return $this->receiverNotifications;
    }

    public function addReceiverNotification(Notification $receiverNotification): self
    {
        if (!$this->receiverNotifications->contains($receiverNotification)) {
            $this->receiverNotifications[] = $receiverNotification;
            $receiverNotification->setReceiver($this);
        }

        return $this;
    }

    public function removeReceiverNotification(Notification $receiverNotification): self
    {
        if ($this->receiverNotifications->removeElement($receiverNotification)) {
            // set the owning side to null (unless already changed)
            if ($receiverNotification->getReceiver() === $this) {
                $receiverNotification->setReceiver(null);
            }
        }

        return $this;
    }
}