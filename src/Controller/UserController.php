<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Service\UserService;
use App\Service\Helpers;
use App\Form\UserType;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Contracts\Translation\TranslatorInterface;

/** @Route("/api", name="blog_") */
class UserController extends FOSRestController
{

    /**
     * 
     * @Get("/", name="homepage")
     *
     */
    public function homePageAction(Request $request, TranslatorInterface $translator)
    {

        $msg = $translator->trans("welcome_msg");

        return new JsonResponse(['hola' => $msg],JsonResponse::HTTP_OK);    
    }

     /**
     * Método para creación de nuevos usuarios
     *
     * Este método lo utilizaremos para el registro de usuarios
     *
     * @Route("/user", name="user_post", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el objeto del usuario en json",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Usuario::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="nombre",
     *     in="formData",
     *     type="string",
     *     description="Nombre del usuario"
     * ),
     * @SWG\Parameter(
     *     name="apellidos",
     *     in="formData",
     *     type="string",
     *     description="Apellidos del usuario"
     * ),
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="Email del usuario"
     * ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Contraseña del usuario"
     * ),
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="Nickname del usuario"
     * )*,
     * @SWG\Parameter(
     *     name="fechaNacimiento",
     *     in="body",
     *     type="datetime",
     *     description="Fecha de nacimiento del usuario",
     *     @SWG\Schema(type="integer")
     * )
     * @SWG\Tag(name="Usuario")
     * 
     */
    public function postUserAction(Request $request, UserService $userService, 
                                    ValidatorInterface $validator)
    {

        $form = $this->createForm(UserType::class);
        $json = $this->getJson($request);
        $form->submit($json);

        $errors = $validator->validate($form);
        if (count($errors) > 0) {

            return new JsonResponse(['errors' => json_decode($this->container->get('jms_serializer')
            ->serialize($errors, 'json'))], JsonResponse::HTTP_BAD_REQUEST);

        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = $userService->crearUsuario($request);
            
            return new JsonResponse(json_decode($this->container->get('jms_serializer')
            ->serialize($user, 'json')), Response::HTTP_CREATED);
        
        }
    }

    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @throws HttpException
     */
    private function getJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }
        return $data;
    }

    /**
     * Metodo que muestra los datos de un usuario a partir de su ID
     * 
     * @Route("/user/{idUser}", name="user_get", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el objeto del usuario en json",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Usuario::class, groups={"full"}))
     *     )
     * )
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     type="integer",
     *     description="ID del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function showUser(UserService $userService, int $idUser, Helpers $helper, Request $request,
                            TranslatorInterface $translator)
    {

        try{

            $user = $userService->searchUserById($idUser);

        } catch(\Exception $e) {

            $msg = $helper->handleErrors($e);
            return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

        }
        
        if (is_null($user)){

            $msg = $translator->trans('no_user_with_id');
            
            return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

        }

        return new JsonResponse(json_decode($this->container->get('jms_serializer')
            ->serialize($user, 'json')), Response::HTTP_OK);
    }

    /**
     * Metodo que a partir del ID de un usuario actualiza los datos de dicho usuario
     * 
     * @Route("/user/{idUser}", name="user_put", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el objeto del usuario en json",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Usuario::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="nombre",
     *     in="formData",
     *     type="string",
     *     description="Nombre del usuario"
     * ),
     * @SWG\Parameter(
     *     name="apellidos",
     *     in="formData",
     *     type="string",
     *     description="Apellidos del usuario"
     * ),
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="Email del usuario"
     * ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Contraseña del usuario"
     * ),
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="Nickname del usuario"
     * ),
     * @SWG\Parameter(
     *     name="fechaNacimiento",
     *     in="body",
     *     type="datetime",
     *     description="Fecha de nacimiento del usuario",
     *     @SWG\Schema(type="integer")
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function editUser(UserService $userService, Helpers $helper, int $idUser, Request $request,
                            TranslatorInterface $translator)
    {

        try{

            $user = $userService->searchUserById($idUser);

            if (is_null($user)) {

                $msg = "no_user_with_id";
                $msg = $translator->trans($msg);
            
                return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

            }
            else {

                $user = $userService->changeUser($user, $request);
                
            }

        } catch(\Exception $e) {

            $msg = $helper->handleErrors($e);
            return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

        }
        
        return new JsonResponse(json_decode($this->container->get('jms_serializer')
            ->serialize($user, 'json')), Response::HTTP_OK);
    }

    /**
     * Metodo que borra un usuario a partir de su ID.
     * 
     * @Route("/user/{idUser}", name="user_delete", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el objeto del usuario en json",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Usuario::class, groups={"full"}))
     *     )
     * )
     * 
     * @SWG\Parameter(
     *     name="id",
     *     in="formData",
     *     type="integer",
     *     description="ID del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function deleteUser(UserService $userService, int $idUser, Helpers $helper,
                                TranslatorInterface $translator){

        try{

            $user = $userService->searchUserById($idUser);

            if (is_null($user)) {
                
                $msg = "no_user_with_id";
                $msg = $translator->trans($msg);
            
                return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

            }
            else {

                $user = $userService->eraseUser($idUser);
                
            }

        } catch(\Exception $e) {

            $msg = $helper->handleErrors($e);
            return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

        }
        
        return new JsonResponse(json_decode($this->container->get('jms_serializer')
            ->serialize($user, 'json')), Response::HTTP_OK);
    }

    /**
     * @Route("/login", name="user_login", methods={"POST"})
     * 
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el objeto del usuario en json",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Usuario::class, groups={"full"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     type="string",
     *     description="Email del usuario"
     * ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Contraseña del usuario"
     * )
     */
    public function loginUserAction(UserService $userService, Request $request, 
                                    UserPasswordEncoderInterface $encoder, Helpers $helper,
                                    TranslatorInterface $translator)
    {
        
        $variables = $request->request;
        $email = $variables->get('email');
        $error = false;
        $user = $userService->searchUserByEmail($email);

        if(is_null($user)){

            $error = true;

        }
        else {

            $password = $variables->get('password');

            $validPassword = $encoder->isPasswordValid(
                $user,
                $password
            );
        
            if(!$validPassword){
            
                $error = true;

            }
        }

        if($error){
            
            $msg = "incorrect_email_or_password";
            $msg = $translator->trans($msg);
            
            return new JsonResponse(['error' => $msg], Response::HTTP_BAD_REQUEST);

        }

        $msgSuccess = $translator->trans("login_success");
            
        return new JsonResponse([$success => $msg], Response::HTTP_BAD_REQUEST);
        /* $now = new \DateTime();
        $now->modify("+ 2 days");
        return new JsonResponse(['message' => $now->format("d-m-Y")], Response::HTTP_OK); */
    }
}
