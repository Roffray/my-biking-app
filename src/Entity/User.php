<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="registration.used.email")
 * @UniqueEntity("name", message="registration.used.name")
 *
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={
 *          "get",
 *          "put"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *              "security_message"="Acces denied. You cannot modifiy another user's data",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN') or (object == user and previous_object.getId() == user.getId())",
 *              "security_post_denormalize_message"="Acces denied. You cannot modifiy another user's data"
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_ADMIN') or object == user",
 *              "security_message"="Acces denied. You cannot modifiy another user's data",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN') or (object == user and previous_object.getId() == user.getId())",
 *              "security_post_denormalize_message"="Acces denied. You cannot modifiy another user's data"
 *          }
 *      },
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *     attributes={
 *          "pagination_items_per_page"=2,
 *          "formats"={"jsonld", "json", "html", "jsonhal", "csv"={"text/csv"}}
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isActive"})
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 * @ApiFilter(PropertyFilter::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     *
     * @Groups({"user:read", "user:write", "bike:item:get"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     *
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     *
     * @Groups({"user:read", "user:write", "bike:item:get", "route:item:get"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity=Bike::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     *
     * @ApiSubresource()
     *
     * @Groups({"user:read", "user:write"})
     */
    private $bikes;

    /**
     * @ORM\OneToMany(targetEntity=ApiToken::class, mappedBy="user", orphanRemoval=true)
     */
    private $apiTokens;

    /**
     * @ORM\OneToMany(targetEntity=Route::class, mappedBy="user", orphanRemoval=true)
     *
     * @ApiSubresource()
     */
    private $routes;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
        $this->routes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Bike[]
     */
    public function getBikes(): Collection
    {
        return $this->bikes;
    }

    public function addBike(Bike $bike): self
    {
        if (!$this->bikes->contains($bike)) {
            $this->bikes[] = $bike;
            $bike->setUser($this);
        }

        return $this;
    }

    public function removeBike(Bike $bike): self
    {
        if ($this->bikes->contains($bike)) {
            $this->bikes->removeElement($bike);
            // set the owning side to null (unless already changed)
            if ($bike->getUser() === $this) {
                $bike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Route[]
     */
    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function addRoute(Route $route): self
    {
        if (!$this->routes->contains($route)) {
            $this->routes[] = $route;
            $route->setUser($this);
        }

        return $this;
    }

    public function removeRoute(Route $route): self
    {
        if ($this->routes->removeElement($route)) {
            // set the owning side to null (unless already changed)
            if ($route->getUser() === $this) {
                $route->setUser(null);
            }
        }

        return $this;
    }
}
