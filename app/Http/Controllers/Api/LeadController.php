<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        try {

            // 1. Validate incoming data
            $validated = $request->validate([
                'name' => 'required|string|max:100',

                'email' => 'required|email|max:150',

                  'phone' => [
                    'required',
                     'string',
                     'regex:/^01[3-9]\d{8}$/',
                 ],

                'message' => 'required|string|max:2000',
            ]);

            // 2. Save lead in database
            $lead = Lead::create($validated);

            // 3. Log successful request
            Log::info('Lead submitted successfully', [
                'lead_id' => $lead->id,
                'ip_address' => $request->ip(),
            ]);

            // 4. Return success response
            return response()->json([
                'success' => true,
                'message' => 'Your message has been submitted successfully.',
                'data' => [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'message' => $lead->message,
                ]
            ], 201);

        } catch (ValidationException $e) {

            // 5. Log validation failure
            Log::warning('Lead validation failed', [
                'errors' => $e->errors(),
                'ip_address' => $request->ip(),
            ]);

            // Return Laravel's default 422 JSON response
            throw $e;

        } catch (Throwable $e) {

            // 6. Log server or database error
            Log::error('Lead submission failed', [
                'exception' => $e::class,
                'ip_address' => $request->ip(),
            ]);

            // 7. Return error response
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
