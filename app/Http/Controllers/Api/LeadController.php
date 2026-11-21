<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:100',

            'email' => 'required|email|max:150',

            'phone' => 'required|string|max:20',

            'message' => 'required|string|max:2000',
        ]);

        // Save lead in database
        $lead = Lead::create($validated);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Your message has been submitted successfully.',
            'data' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
            ]
        ], 201);
    }
}
