<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Usuario;
use App\Service\UserService;
use App\Form\UserType;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/** @Route("/api", name="blog_") */
class UserController extends FOSRestController
{
    /**
     * 
     * @Get("/", name="homepage")
     *
     */
    public function homePageAction(Request $request)
    {
        return new JsonResponse(
            [
                'message' => 'Bienvenido al api rest de Iñaki',
            ],
            JsonResponse::HTTP_OK
        );    
    }

     /**
     * Método para creación de nuevos usuarios
     *
     * Este método lo utilizaremos para el registro de usuarios
     *
     * @Route("/user", name="user_post", methods={"POST", "GET", "PUT", "DELETE"})
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
     *     name="fecha_nacimiento",
     *     in="body",
     *     type="date",
     *     description="Fecha de nacimiento del usuario",
     *     @SWG\Schema(type="integer")
     * ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="Contraseña del usuario"
     * )
     * @SWG\Tag(name="Usuario")
     */
    public function postUserAction(Request $request, UserService $userService, ValidatorInterface $validator)
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
     * @Route("/user/{idUser}", name="user_post", methods={"GET"})
     */
    public function showUser(UserService $userService, int $idUser)
    {

        try{
            $userService->searchUser($idUser);
        } catch(\Exception $e) {

        }
        

        //return new JsonResponse(..., Response::HTTP_OK);
    }
}
