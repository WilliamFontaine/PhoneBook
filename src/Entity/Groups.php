<?php

namespace App\Entity;

use App\Repository\GroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GroupsRepository::class)]
#[UniqueEntity('name', message: 'unique')]
class Groups
{
  #[ORM\Id]
  #[ORM\Column(type: UuidType::NAME, unique: true)]
  #[ORM\GeneratedValue(strategy: 'CUSTOM')]
  #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
  private ?Uuid $id = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $name = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $description = null;

  #[ORM\ManyToMany(targetEntity: Contacts::class, mappedBy: 'groups')]
  private Collection $contacts;

  public function __construct()
  {
    $this->contacts = new ArrayCollection();
  }

  public function getId(): ?Uuid
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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;

    return $this;
  }

  /**
   * @return Collection<int, Contacts>
   */
  public function getContact(): Collection
  {
    return $this->contacts;
  }

  public function addContact(Contacts $contact): static
  {
    if (!$this->contacts->contains($contact)) {
      $this->contacts->add($contact);
      $contact->addGroups($this);
    }

    return $this;
  }

  public function removeContact(Contacts $contact): static
  {
    if ($this->contacts->removeElement($contact)) {
      $contact->removeGroups($this);
    }

    return $this;
  }
}
