<?php

namespace App\Http\Controllers\Api;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $attachmentPath = $request->file('attachment')?->store('inquiries', 'local');

        $inquiry = Inquiry::query()->create([
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

        $recipients = User::query()
            ->where('is_active', true)
            ->get()
            ->filter(fn (User $user): bool => $user->role->canViewInquiries());

        if ($recipients->isNotEmpty()) {
            Notification::make()
                ->title('Permintaan penawaran baru')
                ->body($inquiry->name.' mengirim kebutuhan proyek '.$inquiry->project_type.'.')
                ->icon('heroicon-o-envelope-open')
                ->iconColor('warning')
                ->actions([
                    Action::make('view')
                        ->label('Buka permintaan')
                        ->url(InquiryResource::getUrl('edit', ['record' => $inquiry]))
                        ->markAsRead(),
                ])
                ->sendToDatabase($recipients);
        }

        return response()->json([
            'message' => 'Permintaan telah diterima.',
        ], 201);
    }
}
