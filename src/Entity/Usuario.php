<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @UniqueEntity(
 *      fields = "email",
 *      message = "validators.usuario.email.UniqueEntity"
 *  )
 */
class Usuario
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
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *     message = "validators.usuario.fecha_nacimiento.NotBlank"
     * )
     * @Assert\NotNull(
     *     message = "validators.usuario.fecha_nacimiento.NotNull"
     * )
     * @Assert\DateTime(
     *     message = "validators.usuario.fecha_nacimiento.DateTime"
     * )
     */
    private $fechaNacimiento;

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

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }
    
}
