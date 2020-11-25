<?php 
namespace App\Service;

use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UploadPasswordService
{
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    public function __construct(MailService $notifMail ,UserPasswordEncoderInterface $encoder, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager, UserRepository $repo, ProfilRepository $repoP)
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->notifMail = $notifMail;
        $this->repo=$repo;
        $this->repoP=$repoP;
    }

    public function upload($file)
    {
        if($file){
            $avatar = fopen($file->getRealPath(), "rb");
            return $avatar;
        }
        else{
            return null;
        }

    }
    public function hashPassword($user,$password)
    {
        if($user && $password){ 
            $hashpass=$this->encoder->encodePassword($user, $password);
            return $hashpass ;
        }
        else{
            return null;
        }
        
    }
    public function randomPassword($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        
        public function addUser($request, string $profil)
        {
            $user = $request->request->all();
            //avatar & error verification
            $avatar = $request->files->get("avatar");
            $avatar = $this->upload($avatar);
            $profil="App\Entity\\".$profil;
            // dd($user);
            $user["avatar"] = $avatar;
            //random password
            $randomPass=$this->randomPassword();
            $user["password"]=$randomPass;
            //dd($user);
            $user = $this->serializer->denormalize($user,$profil);
            $errors = $this->validator->validate($user);
            if ($errors) {
                $errors = $this->serializer->serialize($errors, "json");
                return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, [], true);
            }
            $user->setPassword($this->hashPassword($user,$randomPass));
            $this->manager->persist($user);
            $this->manager->flush();
    
            //envoi email
            if($this->notifMail->sendNotifMail($user,$randomPass)){
                return $user;
            }
    
            fclose($avatar);
        }

        public function putData($request, string $fileName = null)
        {
            if (empty($request)) {
                return null;
            }
            $content = $request->getContent();
            $data = [];
            $items = preg_split("/form-data; /", $content);
            unset($items[0]);
            foreach ($items as $value) {
                $item = preg_split("/\r\n/", $value);
                array_pop($item);
                array_pop($item);
                $key = explode('"', $item[0]);
                $data[$key[1]] = end($item);
            }
            if($data['profil']){
                $profil = $this->repoP->find($data["profil"]);
                if(!$profil){
                    return null;
                }
                $data["profil"] = $profil;
            }
            if (!empty($data['avatar'])) {
                $stream = fopen('php://memory', 'r+');
                //dd($stream);
                fwrite($stream, $data['avatar']);
                rewind($stream);
                $data['avatar'] = $stream;
            }
            return $data;
        }
        
        public function updateUser($request,$profil,$id)
        {
            $user=$this->repo->find($id);
            $updateUser=$this->putData($request);
            if(!$updateUser)
            {
                return null;
            }
            foreach ($updateUser as $key => $value) {
                $method='set'.ucfirst($key);
                if(method_exists($user,$method) && $key!='username'){
                    $user->$method($value);
                }
            }
            $this->manager->persist($user);
            $this->manager->flush();
            return $user;
        }
    }