<?php

namespace App\Traits;
use DateTime;
use Doctrine\ORM\Mapping as ORM;


trait TimeStampTrait
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $UpdatedAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    #[ORM\PrePersist()]

    public function onPrePersist(){
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(){
        $this->updatedAt = new DateTime();

    }
}