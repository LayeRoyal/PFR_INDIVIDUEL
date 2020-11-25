<?php

namespace App\DataPersister;

use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

/**
 *
 */
class ProfilDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Profil;
    }

    /**
     * @param Profil $data
     */
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
        $users=$data->getUsers();
        $this->_entityManager->persist($data);
        foreach ($users as $user ) {
            $user->setArchivage(true);
            $this->_entityManager->persist($user);
        }
        $this->_entityManager->flush();
    }
}