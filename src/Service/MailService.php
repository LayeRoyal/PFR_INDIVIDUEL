<?php 
namespace App\Service;

class MailService
{
    private $mailer;
    public function __construct( \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendNotifMail($user, $randomPassword ): bool
    {
        $message = (new \Swift_Message('Orange Digital Center'))
            ->setFrom('abdoulaye.drame1@uvs.edu.sn')
            ->setTo($user->getEmail())
            ->setBody("mot de passe est $randomPassword , pour " . $user->getUsername());
        $this->mailer->send($message);
        return true;
    }

}