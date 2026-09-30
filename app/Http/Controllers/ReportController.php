<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class ReportController extends Controller
{
    /**
     * Display all reports for an event (staff view).
     */
    public function index(Event $event)
    {
        $reports = $event->reports()->with('generatedBy')->latest()->get();
        return view('reports.index', compact('event', 'reports'));
    }

    /**
     * Display reports for clients (read-only).
     */
    public function clientIndex(Event $event)
    {
        $user = Auth::user();

        // Security: client can only access their own event's reports
        if ($user->client_id && $event->client_id !== $user->client_id) {
            abort(403, 'You do not have access to this event.');
        }

        $reports = $event->reports()->with('generatedBy')->latest()->get();

        return view('reports.client', compact('event', 'reports'));
    }

    /**
     * Display all reports (admin view).
     */
    public function allReports()
    {
        $reports = Report::with(['event', 'generatedBy'])->latest()->paginate(15);
        return view('reports.all', compact('reports'));
    }

    /**
     * Show form to generate a new report.
     */
    public function create(Event $event)
    {
        return view('reports.create', compact('event'));
    }

    /**
     * Generate a new report.
     */
    public function generate(Request $request, Event $event)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:event_summary,financial,sustainability,iso,final',
            'report_title' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
        ]);

        $reportType = $validated['report_type'];
        $reportTitle = $validated['report_title'] ?? $this->getDefaultTitle($event, $reportType);

        $pdf = $this->generatePdf($event, $reportType, $reportTitle);

        $fileName = 'reports/' . $event->id . '_' . $reportType . '_' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $report = Report::create([
            'event_id' => $event->id,
            'report_type' => $reportType,
            'report_title' => $reportTitle,
            'file_path' => $fileName,
            'generated_by' => Auth::id(),
            'generated_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        // ✅ Log report generation
        ActivityLogger::log(
            'create',
            Auth::user()->full_name . ' generated report: "' . $reportTitle . '" for event "' . $event->event_name . '"',
            'Report',
            $report->id
        );

        return redirect()->route('reports.index', $event)
            ->with('success', 'Report generated successfully!');
    }
    /**
     * Download a report.
     */
    public function download(Report $report)
    {
        if (!$report->file_exists) {
            return redirect()->back()->with('error', 'Report file not found.');
        }

        $user = Auth::user();

        // Security: client can only download their own reports
        if ($user->role && $user->role->name === 'client_view') {
            if ($user->client_id && $report->event->client_id !== $user->client_id) {
                abort(403, 'You do not have access to this report.');
            }

            $report->update([
                'client_viewed' => true,
                'client_viewed_at' => now(),
            ]);
        }

        // ✅ Log report download
        ActivityLogger::log(
            'download',
            ($user->full_name ?? $user->name) . ' downloaded report: "' . $report->report_title . '" for event "' . ($report->event->event_name ?? 'N/A') . '"',
            'Report',
            $report->id
        );

        $filePath = storage_path('app/public/' . $report->file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return response()->download($filePath, $report->report_title . '.pdf');
    }

    /**
     * View a report in browser.
     */
    public function view(Report $report)
    {
        if (!$report->file_exists) {
            return redirect()->back()->with('error', 'Report file not found.');
        }

        $user = Auth::user();

        // Security: client can only view their own reports
        if ($user->role && $user->role->name === 'client_view') {
            if ($user->client_id && $report->event->client_id !== $user->client_id) {
                abort(403, 'You do not have access to this report.');
            }

            $report->update([
                'client_viewed' => true,
                'client_viewed_at' => now(),
            ]);
        }

        // ✅ Log report view
        ActivityLogger::log(
            'view',
            ($user->full_name ?? $user->name) . ' viewed report: "' . $report->report_title . '" for event "' . ($report->event->event_name ?? 'N/A') . '"',
            'Report',
            $report->id
        );

        $filePath = storage_path('app/public/' . $report->file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return response()->file($filePath);
    }

    /**
     * Delete a report.
     */
    public function destroy(Report $report)
    {
        $reportTitle = $report->report_title;
        $reportId = $report->id;
        $eventName = $report->event->event_name ?? 'N/A';

        if ($report->file_exists) {
            Storage::disk('public')->delete($report->file_path);
        }

        $event = $report->event;
        $report->delete();

        // ✅ Log report deletion
        ActivityLogger::log(
            'delete',
            Auth::user()->full_name . ' deleted report: "' . $reportTitle . '" for event "' . $eventName . '"',
            'Report',
            $reportId
        );

        return redirect()->route('reports.index', $event)
            ->with('success', 'Report deleted successfully!');
    }
    /**
     * Get default title based on report type.
     */
    private function getDefaultTitle(Event $event, $type)
    {
        $typeLabel = match($type) {
            'event_summary' => 'Event Summary Report',
            'financial' => 'Financial Report',
            'sustainability' => 'Sustainability Report',
            'iso' => 'ISO Compliance Report',
            'final' => 'Final Event Report',
            default => 'Event Report',
        };

        return $typeLabel . ' - ' . $event->event_name;
    }

    /**
     * Generate PDF content.
     */
    private function generatePdf(Event $event, $type, $title)
    {
        $event->load(['client', 'createdBy', 'eventChecklists.checklist', 'finances', 'sustainability', 'eventInventory.inventory']);

        $data = [
            'event' => $event,
            'title' => $title,
            'type' => $type,
            'generated_at' => now()->format('d M Y H:i'),
            'generated_by' => Auth::user()->full_name ?? Auth::user()->name,
            'total_income' => $event->total_income,
            'total_expense' => $event->total_expense,
            'net_profit' => $event->net_profit,
            'total_carbon' => $event->total_carbon,
            'total_electricity' => $event->total_electricity,
            'total_water' => $event->total_water,
            'total_waste' => $event->total_waste,
            'checklist_summary' => $event->getChecklistSummary(),
        ];

        $view = match($type) {
            'financial' => 'reports.templates.financial',
            'sustainability' => 'reports.templates.sustainability',
            'iso' => 'reports.templates.iso',
            'final' => 'reports.templates.final',
            default => 'reports.templates.event_summary',
        };

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }
}