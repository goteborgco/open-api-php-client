<?php

namespace GBGCO\Types\Entities;

class Properties
{
    private string $name;
    private int $id;
    private string $icon;
    private string $thumbnail;
    private string $type;
    private string $slug;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->id = (int)($data['id'] ?? 0);
        $this->icon = $data['icon'] ?? '';
        $this->thumbnail = $data['thumbnail'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->slug = $data['slug'] ?? '';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
} 