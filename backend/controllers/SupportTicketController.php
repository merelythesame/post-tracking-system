<?php

namespace controllers;

use models\SupportTicket;
use repository\SupportTicketRepository;

class SupportTicketController extends AbstractController implements HasUserEntitiesInterface
{
    public function __construct()
    {
        parent::__construct(new SupportTicketRepository());
    }

    public function getAllEntities(): void
    {
        $data = [];
        $tickets = $this->repository->all();

        foreach ($tickets as $ticket) {
            $data[] = $ticket->jsonSerialize();
        }

        $this->jsonResponse($data);
    }

    public function getEntityById(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            $this->notFoundResponse('Ticket');
            return;
        }

        $this->jsonResponse($ticket->jsonSerialize());
    }

    public function getEntityByUser(int $id): void
    {
        $data = [];
        $tickets = $this->repository->findByUserId($id);

        foreach ($tickets as $ticket) {
            $data[] = $ticket->jsonSerialize();
        }

        $this->jsonResponse($data);
    }

    public function addEntity(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $ticket = new SupportTicket();
        $ticket->setUserId($data['user_id']);
        $ticket->setSubject($data['subject']);
        $ticket->setMessage($data['message']);
        $ticket->setResponse($data['response'] ?? '');
        $ticket->setStatus($data['status'] ?? 'open');
        $ticket->setCreatedAt(time());

        $this->repository->save($ticket);
        $this->createdResponse('Support ticket');
    }

    public function updateEntity(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            $this->notFoundResponse('Ticket');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->repository->update($ticket, $data);

        $this->updateResponse('Ticket', $success);
    }

    public function deleteEntity(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            $this->notFoundResponse('Ticket');
            return;
        }

        $this->repository->delete($ticket);
        $this->deleteResponse('Ticket');
    }
}