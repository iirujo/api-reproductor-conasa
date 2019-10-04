<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
  
  private $em;

  public function __construct(EntityManagerInterface $em,UserPasswordEncoderInterface $encoder){
    $this->em = $em;
    $this->encoder = $encoder;
  }


  public function validate(Request $request)
  {
    $mensaje = [];
    $nombre = $request->request->get('nombre');
    $apellidos = $request->request->get('apellidos');
    $email = $request->request->get('email');
    $fecha_nacimiento = $request->request->get('fecha_nacimiento');

    if ($nombre == "") {
      $mensaje[] = "El campo nombre es obligatorio";
    }
    else {
      if (strlen($nombre) > 15)
      {
        $mensaje[] = "La longitud máxima del campo nombre no debe exceder los 15 caracteres";
      }
    }

    if ($apellidos == "")
    {
      $mensaje[] = "El campo apellidos es obligatorio";
    }
    else
    {
      if (strlen($apellidos) > 40)
      {
        $mensaje[] = "La longitud máxima del campo apellidos no debe exceder los 40 caracteres";
      }
      if (str_word_count($apellidos, 0) != 2)
      {
        $mensaje[] = "El campo apellidos debe contener exactamente dos apellidos, separados por un espacio. 
        En caso de compuesto debe ir todo junto";
      }
    }

    if ($email == "")
    {
      $mensaje[] = "El campo email es obligatorio";
    }
    else
    {
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false  )
      {
        $mensaje[]= "El correo electronico introducido no es correcto";
      }
    }


    if ($fecha_nacimiento == "")
    {
      $mensaje[] = "El campo fecha de nacimiento es obligatorio";
    }

    return $mensaje;
  }

  public function crearUsuario(Request $request){

    $usuario = new Usuario();
    $variables = $request->request;
    $usuario->setNombre($variables->get('nombre'));
    $usuario->setApellidos($variables->get('apellidos'));
    $usuario->setEmail($variables->get('email'));
    $usuario->setUsername($variables->get('username'));
    $usuario->setFechaNacimiento(new \DateTime($variables->get('fecha_nacimiento')));
    $usuario->setPassword($variables->get('password'));
    $encodedPassword = $this->encoder->encodePassword($usuario, $variables->get('password'));
    $usuario->setPassword($encodedPassword);
    $this->em->persist($usuario);
    $this->em->flush();
    return $usuario;

  }

  public function searchUser(int $idUser) : ?Usuario{

    $user = $this->em
            ->getRepository(Usuario::class)
            ->findOneById($idUser);
    return $user;
  }

  public function changeUser(Usuario $user, Request $request) : ?Usuario{

    $variables = $request->request;
    $nombre = $variables->get('nombre');
    $apellidos = $variables->get('apellidos');
    $email = $variables->get('email');
    $fechaNacimiento =$variables->get('fecha_nacimiento');
    $password = $variables->get('password');

    if (!is_null($nombre) && $nombre != "") {
      $user->setNombre($nombre);
    }

    if (!is_null($apellidos) && $apellidos != "") {
      $user->setApellidos($apellidos);
    }

    if (!is_null($email) && $email != "") {
      $user->setEmail($email);
    }

    if (!is_null($fechaNacimiento) && $fechaNacimiento != "") {
      $user->setFechaNacimiento($fechaNacimiento);
    }

    if (!is_null($password) && $password != "") {
      $user->setPassword($password);
    }

    $this->em->flush();

    return $user;
  }

  public function eraseUser(int $idUser) : ?Usuario{

    $repository = $this->em->getRepository(Usuario::class);
    $user = $repository->findOneById($idUser);

    if ($user) {  

      $this->em->remove($user);
      $this->em->flush();

    }

    return $user;

  }

}