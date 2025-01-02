<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'required|string|max:500'
        ]);

        $msguser = Faq::create([
            'email' => $validated['email'],
            'message' => $validated['message']
        ]);

        if (!$msguser) {
            return response()->json(['error' => 'Failed to submit the message'], 500);
        }

        return response()->json([
            'success' => 'Message has been sent',
        ]);
    }
}
