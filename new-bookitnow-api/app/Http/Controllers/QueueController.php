<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Ensure the base Controller is imported
use App\Models\QueueItem;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;

class QueueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $queue = QueueItem::with('patient')
            ->orderBy('queue_number')
            ->get();

        $patients = Patient::orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => QueueItem::count(),
            'waiting' => QueueItem::where('status', 'waiting')->count(),
            'called' => QueueItem::where('status', 'called')->count(),
            'completed' => QueueItem::where('status', 'completed')->count(),
        ];

        return view('queue.index', compact('queue', 'patients', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if patient is already in queue
        $existingQueueItem = QueueItem::where('patient_id', $request->patient_id)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($existingQueueItem) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient is already in the queue.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Patient is already in the queue.');
        }

        // Get next queue number
        $nextQueueNumber = QueueItem::max('queue_number') + 1;

        $queueItem = QueueItem::create([
            'patient_id' => $request->patient_id,
            'queue_number' => $nextQueueNumber,
            'status' => 'waiting',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient added to queue successfully!',
                'queue_item' => $queueItem->load('patient')
            ]);
        }

        return redirect()->route('queue.index')->with('success', 'Patient added to queue successfully!');
    }

    public function updateStatus(Request $request, QueueItem $queueItem)
    {
        $request->validate([
            'status' => 'required|in:waiting,called,completed'
        ]);

        $queueItem->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Queue status updated successfully!',
                'queue_item' => $queueItem->load('patient')
            ]);
        }

        return redirect()->route('queue.index')->with('success', 'Queue status updated successfully!');
    }

    public function destroy(QueueItem $queueItem)
    {
        $queueItem->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient removed from queue successfully!'
            ]);
        }

        return redirect()->route('queue.index')->with('success', 'Patient removed from queue successfully!');
    }

    public function callNext()
    {
        $nextPatient = QueueItem::where('status', 'waiting')
            ->orderBy('queue_number')
            ->first();

        if ($nextPatient) {
            $nextPatient->update(['status' => 'called']);

            return response()->json([
                'success' => true,
                'message' => 'Next patient called successfully!',
                'queue_item' => $nextPatient->load('patient')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No patients waiting in queue.'
        ]);
    }

    public function reset()
    {
        QueueItem::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Queue reset successfully!'
        ]);
    }
}
