<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, UserProduct>
     */
    #[ORM\OneToMany(targetEntity: UserProduct::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $userProducts;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    public function __construct()
    {
        $this->userProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, UserProduct>
     */
    public function getUserProducts(): Collection
    {
        return $this->userProducts;
    }

    public function addUserProduct(UserProduct $userProduct): static
    {
        if (!$this->userProducts->contains($userProduct)) {
            $this->userProducts->add($userProduct);
            $userProduct->setProduct($this);
        }

        return $this;
    }

    public function removeUserProduct(UserProduct $userProduct): static
    {
        if ($this->userProducts->removeElement($userProduct)) {
            // set the owning side to null (unless already changed)
            if ($userProduct->getProduct() === $this) {
                $userProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
