<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[Assert\NotBlank]
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $last_name = null;

	#[Assert\NotBlank]
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $first_name = null;

	#[Assert\NotBlank]
	#[ORM\Column(length: 255)]
	private ?string $email = null;

	#[Assert\NotBlank]
	#[Assert\Type(\DateTime::class)]
	#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
	private ?\DateTimeInterface $birthday_date = null;

	#[ORM\Column(type: Types::BOOLEAN, nullable: false)]
	private bool $active = false;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getLastName(): ?string
	{
		return $this->last_name;
	}

	public function setLastName(?string $last_name): self
	{
		$this->last_name = $last_name;

		return $this;
	}

	public function getFirstName(): ?string
	{
		return $this->first_name;
	}

	public function getFullName(): string
	{
		return trim($this->getFirstName() . ' ' . strtoupper($this->getLastName()));
	}

	public function setFirstName(?string $first_name): self
	{
		$this->first_name = $first_name;

		return $this;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getBirthdayDate(): ?\DateTimeInterface
	{
		return $this->birthday_date;
	}

	public function setBirthdayDate(?\DateTimeInterface $birthday_date): self
	{
		$this->birthday_date = $birthday_date;

		return $this;
	}

	public function isActive(): ?bool
	{
		return $this->active;
	}

	public function setActive(bool $active): self
	{
		$this->active = $active;

		return $this;
	}
}
