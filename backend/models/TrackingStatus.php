<?php

namespace models;

use JsonSerializable;

class TrackingStatus implements JsonSerializable
{
    private ?int $id = null;
    private ?int $shipment_id = null;
    private ?string $status = null;
    private ?string $location = null;
    private ?string $sendAt = null;
    private ?string $arriveAt = null;
    private ?int $post_office_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getShipmentId(): ?int
    {
        return $this->shipment_id;
    }

    public function setShipmentId(?int $shipment_id): void
    {
        $this->shipment_id = $shipment_id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getSendAt(): ?string
    {
        return $this->sendAt;
    }

    public function setSendAt(?string $sendAt): void
    {
        $this->sendAt = $sendAt;
    }

    public function getArriveAt(): ?string
    {
        return $this->arriveAt;
    }

    public function setArriveAt(?string $arriveAt): void
    {
        $this->arriveAt = $arriveAt;
    }

    public function getPostOfficeId(): ?int
    {
        return $this->post_office_id;
    }

    public function setPostOfficeId(?int $post_office_id): void
    {
        $this->post_office_id = $post_office_id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'shipment_id' => $this->getShipmentId(),
            'status' => $this->getStatus(),
            'location' => $this->getLocation(),
            'sendAt' => $this->getSendAt(),
            'arriveAt' => $this->getSendAt(),
            'post_office_id' => $this->getPostOfficeId(),
        ];
    }

}