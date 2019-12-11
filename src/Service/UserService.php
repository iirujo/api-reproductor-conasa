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
    $fechaNacimiento = $request->request->get('fechaNacimiento');

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


    if ($fechaNacimiento == "")
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
    $usuario->setFechaNacimiento(new \DateTime($variables->get('fechaNacimiento')));
    $encodedPassword = $this->encoder->encodePassword($usuario, $variables->get('password'));
    $usuario->setPassword($encodedPassword);
    $this->em->persist($usuario);
    $this->em->flush();
    return $usuario;

  }

  public function searchUserById(int $idUser) : ?Usuario{

    $user = $this->em
            ->getRepository(Usuario::class)
            ->findOneById($idUser);
    return $user;

  }

  public function searchUserByEmail($email) : ?Usuario{

    $user = $this->em
            ->getRepository(Usuario::class)
            ->findOneBy(['email' => $email]);
    return $user;
    
  }

  public function changeUser(Usuario $user, Request $request) : ?Usuario{

    $variables = $request->request;
    $nombre = $variables->get('nombre');
    $apellidos = $variables->get('apellidos');
    $email = $variables->get('email');
    $fechaNacimiento = new \DateTime($variables->get('fechaNacimiento'));
    $username = $variables->get('username');

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

    if (!is_null($username) && $username != "") {
      $user->setNombre($username);
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

  public function authorize() {

    $ch = curl_init();
    //$headers = array("Authorization: Basic ZWM5ZmYyNzIxMjhkNGI0NTg2MWYwNzU5YjY3MWZjZDM6NWViYzQzMWVmNDJiNDFiMDk2MzRjMzMzNzQzNTJjZDI=");
    $headers = array("Authorization: Basic ".$_ENV['USER_DATA_BASE64']);
    $data = "grant_type=client_credentials";
    //curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");
    curl_setopt($ch, CURLOPT_URL, $_ENV['URL_TOKEN']);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec ($ch);

    curl_close($ch);

    return $result;

  }

  public function searchItem($token, $keyword, $type) {

    $keyword = str_replace(' ', '%20', $keyword);
    $data = "q=".$keyword."&type=".$type."";
    $url = $_ENV['URL_SEARCH'];
    $token = json_decode($token, true);
    $token = "Authorization: Bearer ".$token["access_token"];
    $token = array($token);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $token);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;

  }

  public function sendEmail($name, \Swift_Mailer $mailer) {
    $message = (new  \Swift_Message('Hello Email'))
      ->setFrom('iirujoconasa1@gmail.com')
      ->setTo('inakiirujo@gmail.com')
      ->setBody(
        "holajksna de nuevo"
        /* $this->renderView(
          'registration.html.twig',
          ['name' => $name]
        ),
        'text/html' */
      );
      $mailer->send($message);
      
      //return $this->render(...);
  }

}