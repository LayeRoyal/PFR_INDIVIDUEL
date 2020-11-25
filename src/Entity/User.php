<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="profil", type="string")
 * @ORM\DiscriminatorMap({"ADMIN" = "User", "APPRENANT" = "Apprenant", "FORMATEUR" = "Formateur", "CM" = "Cm"})
 * @ApiResource(
 *    attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={
    *          "security"="is_granted('ROLE_ADMIN') ",
    *          "security_message"="Seul un admin peut faire cette action.",
    *          "path"="admin/users"
    *           },
    *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/users",
    *           "normalization_context"={"groups"={"user_read"}}
 *              }
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="is_granted('ROLE_ADMIN')",
    *            "security_message"="Seul un admin peut faire cette action.",
    *            "path"="admin/users/{id}", 
    *            "normalization_context"={"groups"={"user_read","user_details_read"}}
    *            }, 
    *         "archivage"={"method"="put",
    *                    "security"="is_granted('ROLE_ADMIN')",
    *                   "security_message"="Seul un admin peut faire cette action.",
    *                   "path"="admin/users/{id}"},
    *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN')",
    *                "security_message"="Seul un admin peut faire cette action.",
    *                "path"="admin/users/{id}"}
    *  }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archivage":true})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "username can't be null")
     * @Groups({"user_read","user_details_read","profil_user_read"})
     */
    protected $username;

    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "password can't be null")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "prenom can't be null")
     * @Groups({"user_read","user_details_read","profil_user_read"})
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "nom can't be null")
     * @Groups({"user_read","user_details_read","profil_user_read"})
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *  message = "Email '{{ value }}' is not valid!.")
     * @Groups({"user_details_read","profil_user_read"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_read","user_details_read","profil_user_read"})
     */
    protected $statut;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"user_read","user_details_read","profil_user_read"})
     */
    protected $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_read","user_details_read"})
     */
    protected $profil;

    /**
     * @ORM\OneToMany(targetEntity=ProfilSortie::class, mappedBy="createdBy")
     * @Groups({"user_details_read"})
     */
    private $profilSorties;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $archivage=false;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="createdBy")
     */
    private $promos;

    public function __construct()
    {
        $this->profilSorties = new ArrayCollection();
        $this->promos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatut(): ?string
    { 
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAvatar()
    {
        $data = stream_get_contents($this->avatar);

       return base64_encode($data);
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection|ProfilSortie[]
     */
    public function getProfilSorties(): Collection
    {
        return $this->profilSorties;
    }

    public function addProfilSorty(ProfilSortie $profilSorty): self
    {
        if (!$this->profilSorties->contains($profilSorty)) {
            $this->profilSorties[] = $profilSorty;
            $profilSorty->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProfilSorty(ProfilSortie $profilSorty): self
    {
        if ($this->profilSorties->removeElement($profilSorty)) {
            // set the owning side to null (unless already changed)
            if ($profilSorty->getCreatedBy() === $this) {
                $profilSorty->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(?bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setCreatedBy($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getCreatedBy() === $this) {
                $promo->setCreatedBy(null);
            }
        }

        return $this;
    }
}
