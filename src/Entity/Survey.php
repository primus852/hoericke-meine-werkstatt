<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $q1;

    /**
     * @ORM\Column(type="integer")
     */
    private $q2;

    /**
     * @ORM\Column(type="integer")
     */
    private $q3;

    /**
     * @ORM\Column(type="integer")
     */
    private $q4;

    /**
     * @ORM\Column(type="integer")
     */
    private $q5;

    /**
     * @ORM\Column(type="integer")
     */
    private $q6;

    /**
     * @ORM\Column(type="integer")
     */
    private $q7;

    /**
     * @ORM\Column(type="integer")
     */
    private $q8;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $q9;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ideas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emotions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $enteredOn;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Voucher", mappedBy="survey", cascade={"persist", "remove"})
     */
    private $voucher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQ1(): ?int
    {
        return $this->q1;
    }

    public function setQ1(int $q1): self
    {
        $this->q1 = $q1;

        return $this;
    }

    public function getQ2(): ?int
    {
        return $this->q2;
    }

    public function setQ2(int $q2): self
    {
        $this->q2 = $q2;

        return $this;
    }

    public function getQ3(): ?int
    {
        return $this->q3;
    }

    public function setQ3(int $q3): self
    {
        $this->q3 = $q3;

        return $this;
    }

    public function getQ4(): ?int
    {
        return $this->q4;
    }

    public function setQ4(int $q4): self
    {
        $this->q4 = $q4;

        return $this;
    }

    public function getQ5(): ?int
    {
        return $this->q5;
    }

    public function setQ5(int $q5): self
    {
        $this->q5 = $q5;

        return $this;
    }

    public function getQ6(): ?int
    {
        return $this->q6;
    }

    public function setQ6(int $q6): self
    {
        $this->q6 = $q6;

        return $this;
    }

    public function getQ7(): ?int
    {
        return $this->q7;
    }

    public function setQ7(int $q7): self
    {
        $this->q7 = $q7;

        return $this;
    }

    public function getQ8(): ?int
    {
        return $this->q8;
    }

    public function setQ8(int $q8): self
    {
        $this->q8 = $q8;

        return $this;
    }

    public function getQ9(): ?string
    {
        return $this->q9;
    }

    public function setQ9(string $q9): self
    {
        $this->q9 = $q9;

        return $this;
    }

    public function getIdeas(): ?string
    {
        return $this->ideas;
    }

    public function setIdeas(?string $ideas): self
    {
        $this->ideas = $ideas;

        return $this;
    }

    public function getEmotions(): ?string
    {
        return $this->emotions;
    }

    public function setEmotions(?string $emotions): self
    {
        $this->emotions = $emotions;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getEnteredOn(): ?\DateTimeInterface
    {
        return $this->enteredOn;
    }

    public function setEnteredOn(\DateTimeInterface $enteredOn): self
    {
        $this->enteredOn = $enteredOn;

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(Voucher $voucher): self
    {
        $this->voucher = $voucher;

        // set the owning side of the relation if necessary
        if ($this !== $voucher->getSurvey()) {
            $voucher->setSurvey($this);
        }

        return $this;
    }
}
