<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    /**
     * Download sick certificate file
     */
    public function downloadSickCertificate($leaveId)
    {
        // Check if user is authenticated
        if (! Auth::check()) {
            abort(401, 'Unauthorized');
        }

        // Find the leave record
        $leave = Leave::findOrFail($leaveId);

        // Check authorization - users can only download their own certificates, or admins/HRD can download any
        $user = Auth::user();
        if ($leave->user_id !== $user->id && ! $user->hasAnyRole(['super_admin', 'hrd', 'Hrd'])) {
            abort(403, 'Forbidden');
        }

        // Check if sick certificate exists
        if (! $leave->sick_certificate) {
            abort(404, 'File not found');
        }

        // Check if file exists in storage
        if (! Storage::disk('public')->exists($leave->sick_certificate)) {
            abort(404, 'File not found in storage');
        }

        // Get file path and name
        $filePath = $leave->sick_certificate;
        $fileName = basename($filePath);

        // Determine proper filename for download
        $downloadName = "surat_sakit_{$leave->user->name}_{$leave->start_date->format('Y-m-d')}.{$this->getFileExtension($fileName)}";

        // Return file download response
        return Storage::disk('public')->download($filePath, $downloadName, [
            'Content-Type' => $this->getMimeType($fileName),
        ]);
    }

    /**
     * Get file extension
     */
    private function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($filename)
    {
        $extension = strtolower($this->getFileExtension($filename));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };
    }
}
