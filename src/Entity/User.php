<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package App\Entity
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class User implements UserInterface
{

    /**
     * @var string|null
     * @Assert\NotBlank(message="user.register.form.errors.blank")
     * @Assert\Email(message="user.register.form.errors.email")
     */
    public ?string $email = null;

    /**
     * @var string|null
     * @Assert\NotBlank(message="user.register.form.errors.blank")
     */
    public ?string $password = null;

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
}