<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecoverHashRepository")
 */
class RecoverHash
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $RecoverCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CurrentDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="recoverHash")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecoverCode(): ?string
    {
        return $this->RecoverCode;
    }
    
    public function setRecoverCode(string $RecoverCode): self
    {
        $this->RecoverCode = $RecoverCode;

        return $this;
    }

    public function getCurrentDate(): ?\DateTimeInterface
    {
        return $this->CurrentDate;
    }

    public function setCurrentDate(\DateTimeInterface $CurrentDate): self
    {
        $this->CurrentDate = $CurrentDate;

        return $this;
    }

    public function getUser(): ?Usuario
    {
        return $this->user;
    }

    public function setUser(?Usuario $user): self
    {
        $this->user = $user;

        return $this;
    }
}
