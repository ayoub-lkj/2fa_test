<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Scheb\TwoFactorBundle\Model\BackupCodeInterface;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface, BackupCodeInterface
{
    private const BACKUP_CODES = [111, 222];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @ORM\Column(name="twoFaActive", type="boolean",options={"default":"0"})
     */
    private $two_fa_active;

    /**
     * @ORM\Column(name="code1" ,type="integer",nullable=true)
     */
    private $backup_code1;

    /**
     * @ORM\Column(name="code2" ,type="integer",nullable=true)
     */
    private $backup_code2;

    /**
     * @ORM\Column(name="code3" ,type="integer",nullable=true)
     */
    private $backup_code3;

    /**
     * @ORM\Column(name="code4" ,type="integer",nullable=true)
     */
    private $backup_code4;

    /**
     * @ORM\Column(name="code5" ,type="integer",nullable=true)
     */
    private $backup_code5;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->email;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of backup_code1
     */ 
    public function getBackup_code1()
    {
        return $this->backup_code1;
    }

    /**
     * Set the value of backup_code1
     *
     * @return  self
     */ 
    public function setBackup_code1($backup_code1)
    {
        $this->backup_code1 = $backup_code1;

        return $this;
    }
    

    /**
     * Get the value of backup_code2
     */ 
    public function getBackup_code2()
    {
        return $this->backup_code2;
    }

    /**
     * Set the value of backup_code2
     *
     * @return  self
     */ 
    public function setBackup_code2($backup_code2)
    {
        $this->backup_code2 = $backup_code2;

        return $this;
    }

    /**
     * Get the value of backup_code3
     */ 
    public function getBackup_code3()
    {
        return $this->backup_code3;
    }

    /**
     * Set the value of backup_code3
     *
     * @return  self
     */ 
    public function setBackup_code3($backup_code3)
    {
        $this->backup_code3 = $backup_code3;

        return $this;
    }

    /**
     * Get the value of backup_code4
     */ 
    public function getBackup_code4()
    {
        return $this->backup_code4;
    }

    /**
     * Set the value of backup_code4
     *
     * @return  self
     */ 
    public function setBackup_code4($backup_code4)
    {
        $this->backup_code4 = $backup_code4;

        return $this;
    }

    /**
     * Get the value of backup_code5
     */ 
    public function getBackup_code5()
    {
        return $this->backup_code5;
    }

    /**
     * Set the value of backup_code5
     *
     * @return  self
     */ 
    public function setBackup_code5($backup_code5)
    {
        $this->backup_code5 = $backup_code5;

        return $this;
    }

    public function isBackupCode(string $code): bool
    {
        switch($code){
            case $this->backup_code1:
                $this->backup_code1 = null;
                return true;

            case $this->backup_code2:
                $this->backup_code2 = null;
                return true;

            case $this->backup_code3:
                $this->backup_code3 = null;
                return true;

            case $this->backup_code4:
                $this->backup_code4 = null;
                return true;

            case $this->backup_code5:
                $this->backup_code5 = null;
                return true;
            default:
                return false;
        }
        // $backup_codes=[$this->backup_code1,$this->backup_code2,$this->backup_code3,$this->backup_code4,$this->backup_code5];
        // return \in_array($code, $backup_codes);
    }

    public function invalidateBackupCode(string $code): void
    {
    }

    /**
     * Get the value of two_fa_active
     */ 
    public function getTwo_fa_active()
    {
        return $this->two_fa_active;
    }

    /**
     * Set the value of two_fa_active
     *
     * @return  self
     */ 
    public function setTwo_fa_active($two_fa_active)
    {
        $this->two_fa_active = $two_fa_active;

        return $this;
    }
}
