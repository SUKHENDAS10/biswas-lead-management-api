<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminLeadController extends Controller
{
    // Get all leads and filter by status
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => [
                'sometimes',
                'required',
                Rule::in(['New', 'Contacted', 'Closed']),
            ],
        ]);

        $query = Lead::query()->latest();

        // Filter leads by status
        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $leads = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Leads retrieved successfully.',
            'data' => $leads,
        ], 200);
    }

    // Update lead status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['New', 'Contacted', 'Closed']),
            ],
        ]);

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        $lead->status = $validated['status'];
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully.',
            'data' => $lead,
        ], 200);
    }
}
