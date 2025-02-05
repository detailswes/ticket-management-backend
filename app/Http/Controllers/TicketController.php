<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\TicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;

class TicketController extends Controller
{
    protected $ticketService;

    /**
     * Create a new class instance.
     */
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = $this->ticketService->getAllTickets();
        return response()->json([
            'status' => 'success',
            'data' => TicketResource::collection($tickets),
        ], 200);
    }

    /**
     * Store a new ticket.
     */
    public function store(TicketRequest $request)
    {
        $ticket = $this->ticketService->createTicket($request->validated());
        return ApiResponseClass::success(new TicketResource($ticket), 'Ticket created successfully.');
    }

    /**
     * Update an existing ticket.
     */
    public function update(UpdateTicketRequest $request, string $id)
    {
        $ticket = $this->ticketService->updateTicket($id, $request->validated());
        return ApiResponseClass::success(new TicketResource($ticket), 'Ticket created successfully.');
    }

    /**
     * Remove a ticket.
     */
    public function destroy(string $id)
    {
        $this->ticketService->deleteTicket($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Ticket deleted successfully.',
        ], 200);
    }
}
