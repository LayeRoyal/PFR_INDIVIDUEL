<?php

namespace App\Controller;

use App\Repository\FormateurRepository;
use App\Service\UploadPasswordService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/formateurs",
     *     methods={"POST"}
     * )
     */
    public function addFormateur (UploadPasswordService $upService, Request $request)
    {
        $formateur=$upService->addUser($request,"Formateur");
        return  $this->json($formateur, Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *     path="/api/formateurs/{id}",
     *     methods={"PUT"}
     * )
     */
    public function updateFormateur(UploadPasswordService $upService, Request $request,$id)
    {
        $user=$upService->updateUser($request,'Formateur',$id);
        if(!$user){
            return  $this->json(['message'=>'profil not found']);
        }
        else{
            return  $this->json($user, Response::HTTP_OK);            
        }
    }
}