<?php

namespace controllers;

use models\SupportTicket;
use repository\SupportTicketRepository;

class SupportTicketController
{
    private SupportTicketRepository $repository;

    public function __construct()
    {
        $this->repository = new SupportTicketRepository();
    }

    public function getTickets(): void
    {
        $data = [];
        $tickets = $this->repository->all();

        header('Content-Type: application/json');
        foreach ($tickets as $ticket) {
            $data[] = $ticket->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function getTicketById(int $id): void
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

    public function getTicketByUser(int $userId): void
    {
        $data = [];
        $tickets = $this->repository->findByUserId($userId);

        header('Content-Type: application/json');
        foreach ($tickets as $ticket) {
            $data[] = $ticket->jsonSerialize();
        }

        echo json_encode($data);
    }

    public function addTicket(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $ticket = new SupportTicket();
        $ticket->setUserId($data['user_id']);
        $ticket->setSubject($data['subject']);
        $ticket->setMessage($data['message']);
        $ticket->setStatus($data['status'] ?? 'open');
        $ticket->setCreatedAt(time());

        $this->repository->save($ticket);
        http_response_code(201);
        echo json_encode(['message' => 'Support ticket created']);
    }

    public function updateTicket(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['message' => 'Ticket not found']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->repository->update($ticket, $data);

        http_response_code($success ? 200 : 400);
        echo json_encode(['message' => $success ? 'Ticket updated' : 'Update failed']);
    }

    public function deleteTicket(int $id): void
    {
        $ticket = $this->repository->find($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['message' => 'Ticket not found']);
            return;
        }

        $this->repository->delete($ticket);
        http_response_code(202);
        echo json_encode(['message' => 'Ticket deleted']);
    }

}