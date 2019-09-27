<?php

namespace App\Controller;

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
     * 
     * @Route("/user", name="user_post", methods={"POST"})
     *
     */
    public function postUserAction(Request $request, UserService $userService, ValidatorInterface $validator)
    {
        
        $form = $this->createForm(UserType::class);
        $errors = $validator->validate($form);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {

            $nombre = $userService->crearUsuario($request);
            return new JsonResponse(["success" => $nombre." has been registered!"], Response::HTTP_CREATED);
        
        }   
    }
}
