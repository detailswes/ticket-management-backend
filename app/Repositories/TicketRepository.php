<?php

namespace App\Repositories;

use App\Interfaces\TicketRepositoryInterface;
use App\Models\Ticket;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * Get all tickets.
     */
    public function all()
    {
        return Ticket::orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new ticket.
     */
    public function create(array $data)
    {
        return Ticket::create($data);
    }

    /**
     * Update a ticket by ID.
     */
    public function update(string $id, array $data)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete a ticket by ID.
     */
    public function delete(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return $ticket->delete();
    }
}
