<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketIssueType;
use Illuminate\Http\Request;

class TicketIssueTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketIssueTypes = TicketIssueType::latest('id')->paginate(10);

        return view('admin.ticket-issue.index', compact('ticketIssueTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        TicketIssueType::create([
            'name' => $request->name,
        ]);

        return back()->withSuccess('Ticket issue type created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketIssueType $ticketIssueType)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $ticketIssueType->update(['name' => $request->name]);

        return back()->withSuccess('Ticket issue type updated successfully');
    }

    /**
     * toggle the specified resource in storage.
     */
    public function toggleStatus(TicketIssueType $ticketIssueType)
    {
        $ticketIssueType->update(['is_active' => ! $ticketIssueType->is_active]);

        return back()->withSuccess('Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketIssueType $ticketIssueType)
    {
        $ticketIssueType->delete();

        return back()->withSuccess('Issue type deleted successfully');
    }
}
