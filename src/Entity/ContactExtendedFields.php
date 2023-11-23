<?php

namespace App\Entity;

use App\Repository\ContactExtendedFieldsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactExtendedFieldsRepository::class)]
class ContactExtendedFields
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $fieldName = null;

  #[ORM\Column(length: 255)]
  private ?string $fieldValue = null;

  #[ORM\ManyToOne(inversedBy: 'contactExtendedFields')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Contacts $contact = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getFieldName(): ?string
  {
    return $this->fieldName;
  }

  public function setFieldName(string $fieldName): static
  {
    $this->fieldName = $fieldName;

    return $this;
  }

  public function getFieldValue(): ?string
  {
    return $this->fieldValue;
  }

  public function setFieldValue(string $fieldValue): static
  {
    $this->fieldValue = $fieldValue;

    return $this;
  }

  public function getContact(): ?Contacts
  {
    return $this->contact;
  }

  public function setContact(?Contacts $contact): static
  {
    $this->contact = $contact;

    return $this;
  }
}
