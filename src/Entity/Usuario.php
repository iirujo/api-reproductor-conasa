<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @UniqueEntity(
 *      fields = "email",
 *      message = "validators.usuario.email.UniqueEntity"
 *  )
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "validators.usuario.nombre.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.nombre.NotNull"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "validators.usuario.nombre.Length.minMessage",
     *     maxMessage = "validators.usuario.nombre.Length.maxMessage"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "validators.usuario.apellidos.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.apellidos.NotNull"
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 50,
     *     minMessage = "validators.usuario.apellidos.Length.minMessage",
     *     maxMessage = "validators.usuario.apellidos.Length.maxMessage"
     * )
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "validators.usuario.email.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.email.NotNull"
     * )
     * @Assert\Email(
     *     message = "validators.usuario.email.Email",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "validators.usuario.username.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.username.NotNull"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "validators.usuario.password.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.password.NotNull"
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 30,
     *     minMessage = "validators.usuario.password.Length.minMessage",
     *     maxMessage = "validators.usuario.password.Length.maxMessage"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *      message = "validators.usuario.fechaNacimiento.DateTime"
     * )
     *  @Assert\NotBlank(
     *     message = "validators.usuario.fechaNacimiento.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.fechaNacimiento.NotNull"
     * )
     */
    private $fechaNacimiento;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecoverHash", mappedBy="usuario")
     */
    private $recoverHashes;

    public function __construct()
    {
        $this->recoverHashes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    public function getRoles()
    {
        return $this->roles;
    }
    public function eraseCredentials()
    {
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * @return Collection|RecoverHash[]
     */
    public function getRecoverHashes(): Collection
    {
        return $this->recoverHashes;
    }

    public function addRecoverHash(RecoverHash $recoverHash): self
    {
        if (!$this->recoverHashes->contains($recoverHash)) {
            $this->recoverHashes[] = $recoverHash;
            $recoverHash->setUsuario($this);
        }

        return $this;
    }

    public function removeRecoverHash(RecoverHash $recoverHash): self
    {
        if ($this->recoverHashes->contains($recoverHash)) {
            $this->recoverHashes->removeElement($recoverHash);
            // set the owning side to null (unless already changed)
            if ($recoverHash->getUsuario() === $this) {
                $recoverHash->setUsuario(null);
            }
        }

        return $this;
    }
    
}
