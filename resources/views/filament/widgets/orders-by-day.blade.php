<div class="filament-widget-orders-by-day ">
    @if ($itemsByDay->isEmpty())
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm">
                🛒 No orders scheduled for this week
            </div>
        </div>
    @else
        @foreach ($itemsByDay as $day => $items)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Day Header -->
                <div class="px-6 py-4 border-b dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-blue-100 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ ucfirst($day) }}
                    </h2>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Item</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Required</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Stock</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Needed</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Notes</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($items as $item)
                            @php
                                $needed = max(0, $item->must_have - $item->count);
                                $isUrgent = $needed > ($item->must_have * 0.5);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $item->name }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-600 dark:text-gray-300">
                                    {{ $item->must_have }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-600 dark:text-gray-300">
                                    {{ $item->count }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $isUrgent ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' }}">
                                            {{ $needed }}
                                            @if($isUrgent)
                                                <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $item->unit }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 max-w-xs">
                                    {{ $item->note ?: '–' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>
