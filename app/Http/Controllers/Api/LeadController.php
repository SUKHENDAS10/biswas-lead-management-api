<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        try {

            // Step 1: Input Sanitization
            $sanitizedData = [
                'name' => trim(strip_tags($request->input('name', ''))),
                'email' => trim($request->input('email', '')),
                'phone' => trim(strip_tags($request->input('phone', ''))),
                'message' => trim(strip_tags($request->input('message', ''))),
            ];

            // Step 2: Server-side Validation
            $validator = Validator::make($sanitizedData, [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:150',
                'phone' => [
                    'required',
                    'string',
                    'regex:/^01[3-9][0-9]{8}$/',
                ],
                'message' => 'required|string|max:2000',
            ], [
                'name.required' => 'Name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please provide a valid email address.',
                'phone.regex' => 'Please provide a valid 11-digit Bangladesh phone number.',
                'message.required' => 'Message is required.',
            ]);

            // Step 3: Handle Validation Errors
            if ($validator->fails()) {

                Log::warning('Lead validation failed', [
                    'ip_address' => $request->ip(),
                    'errors' => $validator->errors()->toArray(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Step 4: Get Validated Data
            $validatedData = $validator->validated();

            // Step 5: Store Lead in MySQL Database
            $lead = Lead::create($validatedData);

            // Step 6: Log Successful Submission
            Log::info('Lead submitted successfully', [
                'lead_id' => $lead->id,
                'ip_address' => $request->ip(),
            ]);

            // Step 7: Return Success Response
            return response()->json([
                'success' => true,
                'message' => 'Lead submitted successfully.',
                'data' => $lead,
            ], 201);

        } catch (Throwable $e) {

            // Step 8: Log Unexpected Errors
            Log::error('Lead submission failed due to server error', [
                'error' => $e->getMessage(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
