<?php

namespace models;

use JsonSerializable;

class Shipment implements JsonSerializable
{
    private ?int $id = null;
    private ?int $user_id = null;
    private ?int $receiver_id = null;
    private ?string $receiverName = null;
    private ?string $senderName = null;
    private ?float $weight = null;
    private ?string $type = null;
    private ?string $created_at = null;
    private ?int $sendOffice = null;
    private ?int $receiveOffice = null;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
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

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiver_id;
    }

    public function setReceiverId(?int $receiver_id): void
    {
        $this->receiver_id = $receiver_id;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }
    public function getSendOffice(): ?int
    {
        return $this->sendOffice;
    }

    public function setSendOffice(?int $sendOffice): void
    {
        $this->sendOffice = $sendOffice;
    }

    public function getReceiveOffice(): ?int
    {
        return $this->receiveOffice;
    }

    public function setReceiveOffice(?int $receiveOffice): void
    {
        $this->receiveOffice = $receiveOffice;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'receiverName' => $this->getReceiverName(),
            'senderName' => $this->getSenderName(),
            'receiver_id' => $this->getReceiverId(),
            'weight' => $this->getWeight(),
            'type' => $this->getType(),
            'created_at' => $this->getCreatedAt(),
            'sendOffice' => $this->getSendOffice(),
            'receiveOffice' => $this->getReceiveOffice(),
        ];
    }




}