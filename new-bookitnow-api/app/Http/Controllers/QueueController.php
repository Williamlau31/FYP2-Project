<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QueueItem;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $queue = QueueItem::with('patient')
            ->orderBy('queue_number')
            ->get();
        
        return response()->json($queue);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'status' => 'sometimes|in:waiting,called,completed',
        ]);

        // Check if patient is already in queue
        $existingItem = QueueItem::where('patient_id', $validated['patient_id'])
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($existingItem) {
            return response()->json([
                'message' => 'Patient is already in the queue'
            ], 422);
        }

        $queueItem = QueueItem::create($validated);
        $queueItem->load('patient');

        return response()->json($queueItem, 201);
    }

    public function show(QueueItem $queueItem)
    {
        $queueItem->load('patient');
        return response()->json($queueItem);
    }

    public function update(Request $request, QueueItem $queueItem)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting,called,completed',
        ]);

        $queueItem->update($validated);
        $queueItem->load('patient');

        return response()->json($queueItem);
    }

    public function destroy(QueueItem $queueItem)
    {
        $queueItem->delete();

        return response()->json([
            'message' => 'Patient removed from queue successfully'
        ]);
    }
}