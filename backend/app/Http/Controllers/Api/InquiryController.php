<?php

namespace App\Http\Controllers\Api;

use App\Enums\InquiryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $attachmentPath = $request->file('attachment')?->store('inquiries', 'local');

        Inquiry::query()->create([
            'name' => $validated['name'],
            'company' => $validated['company'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'project_type' => $validated['projectType'],
            'estimated_area' => $validated['estimatedArea'] ?? null,
            'budget_range' => $validated['budgetRange'] ?? null,
            'message' => $validated['message'],
            'attachment_path' => $attachmentPath,
            'status' => InquiryStatus::New,
            'source' => 'website',
            'ip_hash' => hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')),
        ]);

        return response()->json([
            'message' => 'Permintaan telah diterima.',
        ], 201);
    }
}
