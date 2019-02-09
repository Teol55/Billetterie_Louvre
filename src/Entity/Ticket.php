<?php

namespace App\Entity;

use App\Validator\ToManyVisitor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="TicketRepository")
 * @ORM\Entity
 * @ORM\Table(name="ticket")
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
     * @ToManyVisitor()
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
     * @ORM\OneToMany(targetEntity="App\Entity\Visitor", mappedBy="order")
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
//            le musee est fermé les mardis
        if(strftime ("%A",strtotime(date_format($this->dateVisit,"d-m-Y")))==='Tuesday'){

            $context->buildViolation('Le musée du Louvre est fermé tous les mardi')
                ->atpath('dateVisit')
                ->addViolation();

        }
//        le musee est ouvert le dimanche mais fermé à la réservation

        if(strftime ("%A",strtotime(date_format($this->dateVisit,"d-m-Y")))==='Sunday'){

            $context->buildViolation('La réservation pour le dimanche est fermée mais le musée est ouvert')
                ->atpath('dateVisit')
                ->addViolation();

        }
//        le musée est fermé le 1er Mai le 1er Novembre et le 25Déecembre


        $dateString=date_format($this->dateVisit,"d-m");
        $dateTimestamp=$this->dateVisit->getTimestamp();

        $year=intval(date_format($this->dateVisit,"Y"));

        //jours ferié Variable
        $easterDate=easter_date($year);

        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);




        $dateOff=array(
            // Dates fixes
            mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
            mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
            mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
            mktime(0, 0, 0, 8,  15, $year),  // Assomption
            mktime(0, 0, 0, 11, 11, $year),  // Armistice

            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 2,  $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 51, $easterYear),
        );


        switch ($dateString)
        {
            case'25-12':
                $context->buildViolation('le musée est fermé le 25 Décembre')
                    ->atpath('dateVisit')
                    ->addViolation();
                break;
            case'01-11':
                $context->buildViolation('le musée est fermé le 1er Novembre')
                    ->atpath('dateVisit')
                    ->addViolation();
                break;
            case'01-05':
                $context->buildViolation('le musée est fermé le 1er Mai')
                    ->atpath('dateVisit')
                    ->addViolation();
                break;

        }

//         Jours feriés ouvert mais réservation fermée

        if(in_array($dateTimestamp,$dateOff)){
            $context->buildViolation('le musée est ouvert sans réservation')
                ->atpath('dateVisit')
                ->addViolation();
        }
    }


}
