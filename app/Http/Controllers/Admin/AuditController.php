<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request);

        $logs = $query->latest()->paginate(25)->withQueryString();

        // Get unique fields for filters
        $actions = AuditLog::select('action')->distinct()->pluck('action')->all();
        $entityTypes = AuditLog::select('entity_type')->distinct()->pluck('entity_type')->all();

        return view('admin.audit.index', compact('logs', 'actions', 'entityTypes'));
    }

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'auditoria-lms-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'id',
                'fecha',
                'usuario',
                'email',
                'accion',
                'entidad',
                'entidad_id',
                'ip',
                'navegador',
                'valores_anteriores',
                'valores_nuevos',
            ]);

            $this->filteredQuery($request)
                ->latest()
                ->chunk(200, function ($logs) use ($handle): void {
                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->id,
                            optional($log->created_at)->toDateTimeString(),
                            $this->csvCell($log->user?->name ?? 'Sistema'),
                            $this->csvCell($log->user?->email ?? ''),
                            $this->csvCell($log->action),
                            $this->csvCell($log->entity_type ? class_basename($log->entity_type) : ''),
                            $log->entity_id,
                            $this->csvCell($log->ip_address),
                            $this->csvCell($log->user_agent),
                            $this->csvCell(json_encode($log->old_values ?? [], JSON_UNESCAPED_UNICODE)),
                            $this->csvCell(json_encode($log->new_values ?? [], JSON_UNESCAPED_UNICODE)),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    private function filteredQuery(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('entity_type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search): void {
                        $qu->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->input('entity_type'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        return $query;
    }

    private function csvCell(?string $value): string
    {
        $value = (string) $value;

        if ($value !== '' && preg_match('/^[=+\-@]/', $value) === 1) {
            return "'".$value;
        }

        return $value;
    }
}
