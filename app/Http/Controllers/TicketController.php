<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets =Ticket::all();
        return response()->json([
            'status' => 'success',
            'data' => TicketResource::collection($tickets),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(TicketRequest $request)
    {
        try {
            
            $ticket = Ticket::create($request->validated()); 
            
            return response()->json([
                'status' => 'success',
                'message' => 'Ticket created successfully.',
                'data' => new TicketResource($ticket), 
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while creating the ticket.',
                'error' => $e->getMessage(), 
            ], 500);  
        }
    }
    

    /**  
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }
        return new TicketResource($ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $ticket->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket updated successfully.',
            'data' => new TicketResource($ticket),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }
        $ticket->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Ticket deleted successfully.',
        ], 200);
    }
}
