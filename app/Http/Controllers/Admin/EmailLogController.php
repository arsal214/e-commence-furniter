<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = EmailLog::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q')->trim() . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('to_email', 'like', $term)
                        ->orWhere('to_name', 'like', $term)
                        ->orWhere('subject', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('type'), fn ($q) => $q->where('mailable', $request->input('type')))
            ->latest('sent_at')
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.email-logs.index', [
            'logs'     => $logs,
            'types'    => EmailLog::query()->whereNotNull('mailable')->distinct()->orderBy('mailable')->pluck('mailable'),
            'sentCount'   => EmailLog::where('status', EmailLog::STATUS_SENT)->count(),
            'failedCount' => EmailLog::where('status', EmailLog::STATUS_FAILED)->count(),
        ]);
    }
}
