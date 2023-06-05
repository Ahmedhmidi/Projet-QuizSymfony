<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NumberQ;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rightAns;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberQ(): ?int
    {
        return $this->NumberQ;
    }

    public function setNumberQ(int $NumberQ): self
    {
        $this->NumberQ = $NumberQ;

        return $this;
    }

    public function getRightAns(): ?int
    {
        return $this->rightAns;
    }

    public function setRightAns(int $rightAns): self
    {
        $this->rightAns = $rightAns;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->Answer;
    }

    public function setAnswer(string $Answer): self
    {
        $this->Answer = $Answer;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->Answer;
    }
}

