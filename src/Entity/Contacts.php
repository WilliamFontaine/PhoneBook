<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[UniqueEntity('phone', message: 'unique')]
#[UniqueEntity('email', message: 'unique')]
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\Email(message: 'email_format')]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Groups::class, inversedBy: 'contacts')]
    private Collection $Groups;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: ContactExtendedFields::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $contactExtendedFields;

    public function __construct()
    {
        $this->Groups = new ArrayCollection();
        $this->contactExtendedFields = new ArrayCollection();
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

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

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
}
