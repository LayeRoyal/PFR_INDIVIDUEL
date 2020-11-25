<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 * @ApiResource(
    *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *                 "security_message"="Seul un admin peut faire cette action.",
    *                 "path"="/admin/profil_sortie"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *                "security_message"="Vous n'avez pas acces a cette ressource.",
    *           "normalization_context"={"groups"={"user_read"}},
    *                "path"="/admin/profil_sortie"}
    *         
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *                "security_message"="Vous n'avez pas acces a cette ressource.",
    *           "normalization_context"={"groups"={"user_read"}},
    *                "path"="/admin/profil_sortie/{id}"}, 
 *             "delete" = {"security"="is_granted('ROLE_ADMIN') ",
 *                   "security_message"="Seul l'admin a accès à cette ressource",
 *                   "path"="/admin/profil_sortie/{id}"
 *          },
    *         "put"={"security_post_denormalize"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *                "security_message"="Seul un admin peut faire cette action.",
    *                "path"="/admin/profil_sortie/{id}",},
    *  }
  * )
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profilSorties")
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage=false;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }
}
