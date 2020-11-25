<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
    *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Seul un admin peut faire cette action.","path"="/apprenants"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FOMATEUR') or is_granted('ROLE_CM'))", "security_message"="Vous n'avez pas acces a cette ressource.","path"="/apprenants"}
    *         
    *     },
    *     
    *     itemOperations={
    *         "get"={"path"="/apprenants/{id}"},
    *         "archivage"={"method"="put",
                        "security"="is_granted('ROLE_ADMIN')",
    *                   "security_message"="Seul un admin peut faire cette action.",
    *                   "path"="/apprenants/{id}"}, 
    *         "put"={"security_post_denormalize"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT'))","security_message"="Seul un admin peut faire cette action.","path"="/apprenants/{id}",},
    *  }
    *)
 */
class Apprenant extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $genre;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     */
    private $profilSortie;

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }
}
