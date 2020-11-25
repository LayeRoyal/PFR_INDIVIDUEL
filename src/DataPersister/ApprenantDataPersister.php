<?php

namespace App\DataPersister;

use App\Entity\Apprenant;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class ApprenantDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
   
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Apprenant;
    }

    public function persist($data, array $context = [])
    {
       return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $data->setArchivage(true);
        $this->_entityManager->flush();
    }
}