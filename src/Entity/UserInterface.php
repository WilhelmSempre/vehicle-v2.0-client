<?php

namespace App\Entity;

/**
 * Interface UserInterface
 * @package App\Entity
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
interface UserInterface
{
    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     * @return UserInterface
     */
    public function setEmail(?string $email): UserInterface;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * @param string|null $password
     * @return UserInterface
     */
    public function setPassword(?string $password): UserInterface;
}
