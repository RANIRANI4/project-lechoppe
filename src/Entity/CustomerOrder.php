<?php

namespace App\Entity;

use App\Enum\EnumOrderStatus;
use App\Repository\CustomerOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CustomerOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'string', enumType: EnumOrderStatus::class)]
    private EnumOrderStatus $status = EnumOrderStatus::ToPrepare;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Consumer $consumer = null;

    /**
     * @var Collection<int, CustomerOrderItem>
     */
    #[ORM\OneToMany(
        targetEntity: CustomerOrderItem::class,
        mappedBy: 'customerOrder',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $customerOrderItems;

    public function __construct()
    {
        $this->customerOrderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getConsumer(): ?Consumer
    {
        return $this->consumer;
    }

    public function setConsumer(Consumer $consumer): static
    {
        $this->consumer = $consumer;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function getStatus(): EnumOrderStatus
    {
        return $this->status;
    }

    public function setStatus(EnumOrderStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection<int, CustomerOrderItem>
     */
    public function getCustomerOrderItems(): Collection
    {
        return $this->customerOrderItems;
    }

    public function addCustomerOrderItem(CustomerOrderItem $customerOrderItem): static
    {
        if (!$this->customerOrderItems->contains($customerOrderItem)) {
            $this->customerOrderItems->add($customerOrderItem);
            $customerOrderItem->setCustomerOrder($this);
        }

        return $this;
    }

    public function removeCustomerOrderItem(CustomerOrderItem $customerOrderItem): static
    {
        if ($this->customerOrderItems->removeElement($customerOrderItem)) {
            // set the owning side to null (unless already changed)
            if ($customerOrderItem->getCustomerOrder() === $this) {
                $customerOrderItem->setCustomerOrder(null);
            }
        }

        return $this;
    }
}
