<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *    attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={
    *          "security"="is_granted('ROLE_ADMIN') ",
    *          "security_message"="Seul un admin peut faire cette action.",
    *          "path"="/formateurs"
    *           },
    *          "get"={
 *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FOMATEUR') or is_granted('ROLE_CM'))", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="/formateurs",
    *           "normalization_context"={"groups"={"user_read"}}
 *              }
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FOMATEUR') or is_granted('ROLE_CM'))",
    *            "security_message"="Vous ne pouvez pas faire cette action.",
    *            "path"="/formateurs/{id}", 
    *            "normalization_context"={"groups"={"user_details_read"}}
    *            },
    *         "delete"={"security"="is_granted('ROLE_ADMIN')",
    *                   "security_message"="Seul un admin peut faire cette action.",
    *                   "path"="/formateurs/{id}"}, 
    *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN')",
    *                "security_message"="Seul un admin peut faire cette action.",
    *                "path"="/formateurs/{id}"}
    *  }
 * )
 */
class Formateur extends User
{

}
