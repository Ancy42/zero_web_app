<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\WhatsAppContact;
use App\Http\Controllers\Controller;
use App\Repositories\WhatsAppMessageRepository;

class WhatsAppChatController extends Controller
{
    public function index(Request $request)
    {
        $contactsQuery=WhatsAppContact::query();
        $search=$request->search;
        if($search){
            $contactsQuery->where('phone_number','like',"%$search%");
            $contacts = $contactsQuery->orderBy('last_message_at', 'desc')->get();
            return view('admin.whatsApp.userlist',compact('contacts'));
        }
        $contacts = $contactsQuery->orderBy('last_message_at', 'desc')->get();
        return view('admin.whatsApp.index',compact('contacts'));
    }
    public function phoneList()
    {
        $contacts = WhatsAppContact::orderBy('last_message_at', 'desc')->get();
        return view('admin.whatsApp.userlist',compact('contacts'));
    }


    public function sendMessage(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'message' => 'required',
        ]);
        WhatsAppMessageRepository::sendMessage($request);
        $contact = WhatsAppContact::where('phone_number', $request->phone_number)->first();
        return view('admin.whatsApp.messagebox', compact('contact'));
    }

    public function messageShow($phone_number)
    {
        $contact = WhatsAppContact::where('phone_number', $phone_number)->first();
        return view('admin.whatsApp.messagebox', compact('contact'));
    }


    public function incomingMessage(Request $request)
    {
        $request->validate([
            'From' => 'required',
            'MessageType' => 'required',
        ]);
        $message = WhatsAppMessageRepository::incomingMessage($request);
        return $this->json('message sent successfully', [
            'message' => $message
        ], 200);
    }

}
