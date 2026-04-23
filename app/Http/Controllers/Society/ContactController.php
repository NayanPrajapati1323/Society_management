<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        // In production: send email via Mail:: or store in DB
        // For now just flash success message
        return redirect()->route('society.landing', '#contact')
            ->with('contact_success', 'Thank you, ' . $request->name . '! We\'ve received your message and will contact you within 24 hours.');
    }
}
