<?php

namespace App\Controller;

use App\Service\UploadPasswordService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
{
    /**
   * @Route(
   *     path="/api/apprenants",
   *     methods={"POST"}
   * )
   */
  public function addApprenant (UploadPasswordService $upService, Request $request)
  {
      $apprenant=$upService->addUser($request,"Apprenant");
      return  $this->json($apprenant, Response::HTTP_CREATED);
  }

  /**
   * @Route(
   *     path="/api/apprenants/{id}",
   *     methods={"PUT"}
   * )
   */
  public function updateApprenant(UploadPasswordService $upService, Request $request,$id)
  {
      $apprenant=$upService->updateUser($request,'Apprenant',$id);
      if(!$apprenant){
          return  $this->json(['message'=>'profil not found']);
      }
      else{
          return  $this->json($apprenant, Response::HTTP_OK);            
      }
  }
}
