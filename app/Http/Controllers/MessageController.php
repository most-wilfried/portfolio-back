<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index()
    {
        return Message::orderByDesc('created_at')->get();
    }

    public function show(Message $message)
    {
        return $message;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:180',
            'subject' => 'required|string|max:180',
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create($data);

        Mail::to(config('mail.from.address'))->send(new ContactMessage($message));

        return response()->json([
            'message' => 'Votre message a bien été envoyé.',
            'data' => $message,
        ]);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json(['message' => 'Message supprimé.']);
    }
}
