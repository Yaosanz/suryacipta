<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Server-side validation is authoritative; client validation is only UX.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'company' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^[+0-9][0-9\s-]{7,19}$/'],
            'projectType' => [
                'required',
                'string',
                Rule::in([
                    'Masjid / Musholla',
                    'Hotel / Resort',
                    'Perumahan',
                    'Perkantoran / Komersial',
                    'Fasilitas Umum',
                    'Lainnya',
                ]),
            ],
            'estimatedArea' => ['nullable', 'string', 'max:30'],
            'budgetRange' => [
                'nullable',
                'string',
                Rule::in([
                    'Di bawah Rp50 juta',
                    'Rp50 - 150 juta',
                    'Rp150 - 500 juta',
                    'Di atas Rp500 juta',
                ]),
            ],
            'message' => ['required', 'string', 'min:20', 'max:3000'],
            'attachment' => [
                'nullable',
                'file',
                'max:5120',
                'mimetypes:application/pdf,image/jpeg,image/png,image/webp',
                'extensions:pdf,jpg,jpeg,png,webp',
            ],
            'website' => ['nullable', 'size:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.size' => 'Permintaan tidak dapat diproses.',
            'projectType.in' => 'Jenis proyek tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitizePlainText($this->name),
            'company' => $this->sanitizePlainText($this->company),
            'email' => is_string($this->email) ? mb_strtolower(trim($this->email)) : $this->email,
            'phone' => $this->sanitizePlainText($this->phone),
            'message' => $this->sanitizePlainText($this->message, preserveNewlines: true),
            'estimatedArea' => $this->sanitizePlainText($this->estimatedArea),
            'budgetRange' => $this->sanitizePlainText($this->budgetRange),
        ]);
    }

    private function sanitizePlainText(mixed $value, bool $preserveNewlines = false): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $withoutActiveContent = preg_replace(
            '#<(script|style)\b[^>]*>.*?</\1>#is',
            '',
            $value,
        ) ?? $value;
        $plainText = trim(strip_tags($withoutActiveContent));

        if ($preserveNewlines) {
            return preg_replace("/[ \t]+/u", ' ', str_replace(["\r\n", "\r"], "\n", $plainText))
                ?? $plainText;
        }

        return preg_replace('/\s+/u', ' ', $plainText) ?? $plainText;
    }
}
