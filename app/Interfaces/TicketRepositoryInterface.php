<?php

namespace App\Interfaces;

interface TicketRepositoryInterface
{
    public function all(); // Get all tickets
    public function create(array $data); // Create a new ticket
    public function update(string $id, array $data); // Update an existing ticket
    public function delete(string $id); // Delete a ticket
}
