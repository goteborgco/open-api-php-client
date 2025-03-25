<?php

namespace GBGCO\Types\Entities;

class Contact
{
    private ?string $email;
    private ?string $phone;
    private ?string $website;
    private ?string $facebook;
    private ?string $instagram;

    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->facebook = $data['facebook'] ?? null;
        $this->instagram = $data['instagram'] ?? null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }
} 