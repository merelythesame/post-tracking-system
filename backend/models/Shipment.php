<?php

namespace models;

use JsonSerializable;

class Shipment implements JsonSerializable
{
    private ?int $id = null;
    private ?int $user_id = null;
    private ?string $receiverName = null;
    private ?string $address = null;
    private ?float $weight = null;
    private ?string $type = null;
    private ?string $price = null;
    private ?string $created_at = null;
    private ?User $user = null;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
        $this->user_id = $user?->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getReceiverName(): ?string
    {
        return $this->receiverName;
    }

    public function setReceiverName(?string $receiverName): void
    {
        $this->receiverName = $receiverName;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): void
    {
        $this->price = $price;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'receiverName' => $this->getReceiverName(),
            'address' => $this->getAddress(),
            'weight' => $this->getWeight(),
            'type' => $this->getType(),
            'price' => $this->getPrice(),
            'created_at' => $this->getCreatedAt(),
        ];
    }


}