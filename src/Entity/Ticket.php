<?php

namespace App\Entity;

use App\Validator\DayOffClose;
use App\Validator\DayOffOpen;
use App\Validator\NotBefore;
use App\Validator\NotPriceDayAfter14;
use App\Validator\NotSunday;
use App\Validator\NotTuesday;
use App\Validator\ToManyVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="TicketRepository")
 * @ORM\Table(name="ticket")
 * @ToManyVisitor()
 * @NotPriceDayAfter14()
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Vous devez Selectionner une date de visite!")
     * @NotTuesday()
     * @NotSunday()
     * @NotBefore()
     * @DayOffClose()
     * @DayOffOpen()
     */
    private $dateVisit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeTicket;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visitor", mappedBy="ticket")
     */
    private $visitors;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="ticket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @Assert\Range(min=1,max=10)
     */

    private $numberPlace;

    /**
     * @param mixed $numberPlace
     */
    public function setNumberPlace($numberPlace): void
    {
        $this->numberPlace = $numberPlace;
    }

    /**
     * @return mixed
     */
    public function getNumberPlace()
    {
        return $this->numberPlace;
    }


    public function needAnotherVisitor():bool {
        return $this->visitors->count() != $this->numberPlace;
    }


    public function __construct()
    {
        $this->visitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVisit(): ?\DateTimeInterface
    {
        return $this->dateVisit;
    }

    public function setDateVisit(\DateTimeInterface $dateVisit): self
    {
        $this->dateVisit = $dateVisit;

        return $this;
    }

    public function getTypeTicket(): ?string
    {
        return $this->typeTicket;
    }

    public function setTypeTicket(string $typeTicket): self
    {
        $this->typeTicket = $typeTicket;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|Visitor[]
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function addVisitor(Visitor $visitor): self
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors[] = $visitor;
            $visitor->setTicket($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): self
    {
        if ($this->visitors->contains($visitor)) {
            $this->visitors->removeElement($visitor);
            // set the owning side to null (unless already changed)
            if ($visitor->getTicket() === $this) {
                $visitor->setTicket(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {








    }


}
