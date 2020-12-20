<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\RouteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "denormalization_context"={"groups"={"route:write"}},
 *              "security"="is_granted('ROLE_USER')",
 *              "security_message"="Acces denied. You need to be authenticated to add a route.",
 *              "security_post_denormalize"="is_granted('MANAGE', object)",
 *              "security_post_denormalize_message"="Acces denied. You cannot add a route to another user."
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"route:read", "route:item:get"}}
 *          },
 *          "delete"={
 *              "security"="is_granted('MANAGE', object)",
 *              "security_message"="Acces denied. You can only delete your own routes."
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=RouteRepository::class)
 *
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 * @ApiFilter(PropertyFilter::class)
 */
class Route
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="routes")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"route:read","route:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"route:read","route:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"route:read","route:write"})
     */
    private $data = [];

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"route:read","route:write"})
     */
    private $search = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getSearch(): ?array
    {
        return $this->search;
    }

    public function setSearch(array $search): self
    {
        $this->search = $search;

        return $this;
    }
}
