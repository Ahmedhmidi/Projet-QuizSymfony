<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Questions::class, mappedBy="quiz")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="quiz")
     */
    private $result;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberQts;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="college_id")
     */
    private $users;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->result = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getNumberQts(): ?int
    {
        return $this->numberQts;
    }

    public function setNumberQts(int $numberQts): self
    {
        $this->numberQts = $numberQts;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->logo;
    }

    /**
     * @return Collection<int, questions>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(questions $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(questions $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, result>
     */
    public function getResults(): Collection
    {
        return $this->result;
    }

    public function addResult(Result $result): self
    {
        if (!$this->result->contains($result)) {
            $this->result[] = $result;
            $result->setQuiz($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->result->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getQuiz() === $this) {
                $result->setQuiz(null);
            }
        }

        return $this;
    }

     /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

}
