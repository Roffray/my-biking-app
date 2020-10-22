<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\BikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *         "get"= {
 *             "normalization_context"={"groups"={"bike:read", "bike:item:get"}}
 *         },
 *         "put",
 *         "patch"
 *     },
 *     normalizationContext={"groups"={"bike:read"}},
 *     denormalizationContext={"groups"={"bike:write"}},
 * )
 * @ORM\Entity(repositoryClass=BikeRepository::class)
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "name": "partial",
 *     "user": "exact",
 *     "user.name": "partial"
 * })
 * @ApiFilter(PropertyFilter::class)
 */
class Bike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Groups({"bike:read", "bike:write", "user:read", "user:write"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bikes")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     *
     * @Groups({"bike:read", "bike:write"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
