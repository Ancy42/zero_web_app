<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketIssueTypeResource;
use App\Models\TicketIssueType;

class TicketIssueTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketIssueTypes = TicketIssueType::where('is_active', true)->get();

        return $this->json('ticket issue types', [
            'issue_types' => TicketIssueTypeResource::collection($ticketIssueTypes),
        ]);
    }
}
