<?php

namespace App\Entity;

use App\Mappers\ApiResponseMapperInterface;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\AccessType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

/**
 * Class User
 * @package App\Entity
 *
 * @AccessType("public_method")
 * @Serializer\XmlRoot("user")
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class User implements UserInterface, ApiResponseMapperInterface, SecurityUserInterface
{

    /**
     * @var string|null
     * @Assert\NotBlank(message="user.register.form.errors.blank")
     * @Assert\Email(message="user.register.form.errors.email")
     *
     * @Serializer\Type("string")
     * @Accessor(getter="getEmail",setter="setEmail")
     * @Serializer\XmlElement(cdata=false)
     */
    public ?string $email = null;

    /**
     * @var string|null
     * @Assert\NotBlank(message="user.register.form.errors.blank")
     *
     * @Serializer\Type("string")
     * @Accessor(getter="getPassword",setter="setPassword")
     * @Serializer\XmlElement(cdata=false)
     */
    public ?string $password = null;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getName",setter="setName")
     * @Serializer\XmlElement(cdata=false)
     */
    private ?string $name;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getSurname",setter="setSurname")
     * @Serializer\XmlElement(cdata=false)
     */
    private ?string $surname;

    /**
     * @Serializer\Type("string")
     * @Accessor(getter="getCreatedAt",setter="setCreatedAt")
     * @Serializer\XmlElement(cdata=false)
     * @Serializer\Type("DateTime<'d-m-Y H:i:s'>")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getUsername();
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserInterface
     */
    public function setEmail(?string $email = null): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return UserInterface
     */
    public function setPassword(?string $password = null): UserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * @return null
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     * @return $this
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}