<?php

namespace App\Controller;

use App\Service\UploadPasswordService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CmController extends AbstractController
{
      /**
     * @Route(
     *     path="/api/cms",
     *     methods={"POST"}
     * )
     */
    public function addCm (UploadPasswordService $upService, Request $request)
    {
        $formateur=$upService->addUser($request,"Cm");
        return  $this->json($formateur, Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/cms/{id}",
     *     methods={"PUT"}
     * )
     */
    public function updateCm(UploadPasswordService $upService, Request $request,$id)
    {
        $user=$upService->updateUser($request,'Cm',$id);
        if(!$user){
            return  $this->json(['message'=>'profil not found']);
        }
        else{
            return  $this->json($user, Response::HTTP_OK);            
        }
    }
}
