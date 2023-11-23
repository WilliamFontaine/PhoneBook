<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[UniqueEntity('phone', message: 'unique')]
class Contacts
{
  #[ORM\Id]
  #[ORM\Column(type: UuidType::NAME, unique: true)]
  #[ORM\GeneratedValue(strategy: 'CUSTOM')]
  #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
  private ?Uuid $id = null;

  #[ORM\Column(length: 255)]
  #[Assert\NotNull(message: 'not_null')]
  #[Assert\NotBlank(message: 'not_blank')]
  private ?string $firstname = null;

  #[ORM\Column(length: 255)]
  #[Assert\NotNull(message: 'not_null')]
  #[Assert\NotBlank(message: 'not_blank')]
  private ?string $lastname = null;

  #[ORM\Column(length: 255, unique: true)]
  #[Assert\NotNull(message: 'not_null')]
  #[Assert\NotBlank(message: 'not_blank')]
  #[Assert\Regex(pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/', message: 'phone_format')]
  private ?string $phone = null;

  #[ORM\Column(length: 255, unique: false, nullable: true)]
  #[Assert\Email(message: 'email_format')]
  private ?string $email = null;

  #[Vich\UploadableField(mapping: 'profile_picture', fileNameProperty: 'imageName')]
  private ?File $imageFile = null;

  #[ORM\Column(type: 'string', nullable: true)]
  private ?string $imageName = null;

  #[ORM\ManyToMany(targetEntity: Groups::class, inversedBy: 'contacts')]
  private Collection $Groups;

  #[ORM\OneToMany(mappedBy: 'contact', targetEntity: ContactExtendedFields::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
  private Collection $contactExtendedFields;

  #[ORM\Column(type: 'datetime_immutable')]
  #[Assert\NotNull]
  private ?DateTimeImmutable $createdAt;

  #[ORM\Column(type: 'datetime_immutable')]
  #[Assert\NotNull]
  private ?DateTimeImmutable $updatedAt;

  public function __construct()
  {
    $this->Groups = new ArrayCollection();
    $this->contactExtendedFields = new ArrayCollection();
    $this->createdAt = new DateTimeImmutable();
    $this->updatedAt = new DateTimeImmutable();
  }

  public function getId(): ?Uuid
  {
    return $this->id;
  }

  public function getFirstname(): ?string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): static
  {
    $this->firstname = $firstname;

    return $this;
  }

  public function getLastname(): ?string
  {
    return $this->lastname;
  }

  public function setLastname(string $lastname): static
  {
    $this->lastname = $lastname;

    return $this;
  }

  public function getPhone(): ?string
  {
    return $this->phone;
  }

  public function setPhone(string $phone): static
  {
    $this->phone = $phone;

    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(?string $email): static
  {
    $this->email = $email;

    return $this;
  }

  public function getImageFile(): ?File
  {
    return $this->imageFile;
  }

  /**
   * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
   * of 'UploadedFile' is injected into this setter to trigger the update. If this
   * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
   * must be able to accept an instance of 'File' as the bundle will inject one here
   * during Doctrine hydration.
   *
   * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
   */
  public function setImageFile(?File $imageFile = null): void
  {
    $this->imageFile = $imageFile;

    if (null !== $imageFile) {
      // It is required that at least one field changes if you are using doctrine
      // otherwise the event listeners won't be called and the file is lost
      $this->updatedAt = new \DateTimeImmutable();
    }
  }

  public function getImageName(): ?string
  {
    return $this->imageName;
  }

  public function setImageName(?string $imageName): void
  {
    $this->imageName = $imageName;
  }

  /**
   * @return Collection<int, Groups>
   */
  public function getGroups(): Collection
  {
    return $this->Groups;
  }

  public function addGroups(Groups $Groups): static
  {
    if (!$this->Groups->contains($Groups)) {
      $this->Groups->add($Groups);
    }

    return $this;
  }

  public function removeGroups(Groups $Groups): static
  {
    $this->Groups->removeElement($Groups);

    return $this;
  }

  /**
   * @return Collection<int, ContactExtendedFields>
   */
  public function getContactExtendedFields(): Collection
  {
    return $this->contactExtendedFields;
  }

  public function addContactExtendedField(ContactExtendedFields $contactExtendedField): static
  {
    if (!$this->contactExtendedFields->contains($contactExtendedField)) {
      $this->contactExtendedFields->add($contactExtendedField);
      $contactExtendedField->setContact($this);
    }

    return $this;
  }

  public function removeContactExtendedField(ContactExtendedFields $contactExtendedField): static
  {
    if ($this->contactExtendedFields->removeElement($contactExtendedField)) {
      // set the owning side to null (unless already changed)
      if ($contactExtendedField->getContact() === $this) {
        $contactExtendedField->setContact(null);
      }
    }

    return $this;
  }

  public function getCreatedAt(): ?DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?DateTimeImmutable
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(DateTimeImmutable $updatedAt): static
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}
