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

        header('Content-Type: application/json');
        foreach ($tickets as $ticket) {
            $data[] = $ticket->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getEntityById(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['message' => 'Ticket not found']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($ticket->jsonSerialize());
    }

    public function getEntityByUser(int $id): void
    {
        $data = [];
        $tickets = $this->repository->findByUserId($id);

        header('Content-Type: application/json');
        foreach ($tickets as $ticket) {
            $data[] = $ticket->jsonSerialize();
        }

        echo json_encode($data);
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
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode(['message' => 'Support ticket created']);
    }

    public function updateEntity(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['message' => 'Ticket not found']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->repository->update($ticket, $data);

        header('Content-Type: application/json');
        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Ticket updated' : 'Update failed']);
    }

    public function deleteEntity(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['message' => 'Ticket not found']);
            return;
        }

        $this->repository->delete($ticket);
        header('Content-Type: application/json');
        http_response_code(202);
        echo json_encode(['message' => 'Ticket deleted']);
    }

}