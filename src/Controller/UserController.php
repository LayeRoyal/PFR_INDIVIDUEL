<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UploadPasswordService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/admin/users",
     *     methods={"POST"}
     * )
     */
    public function addUser(UploadPasswordService $upService, Request $request)
    {
        $user=$upService->addUser($request,"User");
        return  $this->json($user, Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/admin/users/{id}",
     *     methods={"PUT"}
     * )
     */
    public function updateUser(UploadPasswordService $upService, Request $request,$id)
    {
        $user=$upService->updateUser($request,'User',$id);
        if(!$user){
            return  $this->json(['message'=>'profil not found']);
        }
        else{
            return  $this->json($user, Response::HTTP_OK);            
        }
    }
}