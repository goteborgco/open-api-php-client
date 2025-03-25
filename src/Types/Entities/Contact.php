<?php

namespace GBGCO\Types\Entities;

/**
 * Entity representing contact information
 * 
 * This class represents contact details for a content item, including
 * email, phone, website, and social media links. It is typically used
 * for places, events, or other content types that have contact information.
 */
class Contact
{
    /** @var string|null Email address for contact */
    private ?string $email;

    /** @var string|null Phone number for contact */
    private ?string $phone;

    /** @var string|null Website URL */
    private ?string $website;

    /** @var string|null Facebook profile/page URL */
    private ?string $facebook;

    /** @var string|null Instagram profile URL */
    private ?string $instagram;

    /**
     * Create a new contact information set from API data
     * 
     * @param array $data Raw contact data from the API with keys:
     *                    - email: Email address
     *                    - phone: Phone number
     *                    - website: Website URL
     *                    - facebook: Facebook URL
     *                    - instagram: Instagram URL
     */
    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->website = $data['website'] ?? null;
        $this->facebook = $data['facebook'] ?? null;
        $this->instagram = $data['instagram'] ?? null;
    }

    /**
     * Get the email address
     * 
     * @return string|null Contact email address
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get the phone number
     * 
     * @return string|null Contact phone number
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Get the website URL
     * 
     * @return string|null Website address
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * Get the Facebook URL
     * 
     * @return string|null Facebook profile/page URL
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * Get the Instagram URL
     * 
     * @return string|null Instagram profile URL
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }
} 