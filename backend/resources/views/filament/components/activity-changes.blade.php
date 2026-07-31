@php($changes = \App\Support\ActivityPresenter::changes($getRecord()))

@if ($changes === [])
    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada perubahan field pada aktivitas ini.</p>
@else
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 font-semibold">Field</th>
                    <th class="px-4 py-3 font-semibold">Sebelum</th>
                    <th class="px-4 py-3 font-semibold">Sesudah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($changes as $change)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $change['field'] }}</td>
                        <td class="max-w-md whitespace-pre-wrap break-words px-4 py-3">{{ $change['old'] }}</td>
                        <td class="max-w-md whitespace-pre-wrap break-words px-4 py-3">{{ $change['new'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
