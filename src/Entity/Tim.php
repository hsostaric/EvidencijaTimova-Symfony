<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimRepository")
 */
class Tim
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oznakaTima;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nazivProjekta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $opisProjekta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $napomena;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="tim")
     */
    private $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOznakaTima(): ?string
    {
        return $this->oznakaTima;
    }

    public function setOznakaTima(string $oznakaTima): self
    {
        $this->oznakaTima = $oznakaTima;

        return $this;
    }

    public function getNazivProjekta(): ?string
    {
        return $this->nazivProjekta;
    }

    public function setNazivProjekta(?string $nazivProjekta): self
    {
        $this->nazivProjekta = $nazivProjekta;

        return $this;
    }

    public function getOpisProjekta(): ?string
    {
        return $this->opisProjekta;
    }

    public function setOpisProjekta(?string $opisProjekta): self
    {
        $this->opisProjekta = $opisProjekta;

        return $this;
    }

    public function getNapomena(): ?string
    {
        return $this->napomena;
    }

    public function setNapomena(?string $napomena): self
    {
        $this->napomena = $napomena;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setTim($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            // set the owning side to null (unless already changed)
            if ($student->getTim() === $this) {
                $student->setTim(null);
            }
        }

        return $this;
    }
}
