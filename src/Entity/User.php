<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Hateoas\Relation("self",
 *      href = @Hateoas\Route(
 *          "app_users_details",
 *          parameters = {
 *              "user_id" = "expr(object.getId())",
 *              "client_id" = "expr(object.client.getId())"
 *          }
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups = {"show", "list"})
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "app_users_list",
 *          parameters = {
 *              "id" = "expr(object.client.getId())"
 *          }
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups = {"show"})
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Groups(groups={"show"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     * @JMS\Groups(groups={"show"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @JMS\Expose()
     * @JMS\Groups(groups={"show"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @JMS\Expose()
     * @JMS\Groups(groups={"show"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    public $client;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="users")
     * @JMS\Groups(groups={"show"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
