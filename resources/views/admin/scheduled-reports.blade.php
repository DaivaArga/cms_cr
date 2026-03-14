<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Reports - Inventory360</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full">
        
        @include('admin.sidebar')

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center shrink-0">
                <h1 class="text-xl font-bold text-gray-800">Scheduled Reports</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-gray-500 hover:bg-gray-100 rounded-full">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </button>
                    <a href="/dev/reports" class="px-4 py-2 bg-black text-white text-sm rounded-md hover:bg-gray-800 transition">
                        Back to Reports
                    </a>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto space-y-6">

                    <!-- Create New Scheduled Report -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Create New Scheduled Report</h3>
                        
                        <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Report Name</label>
                                <input type="text" placeholder="e.g. Weekly Sales Summary" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]" value="">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    <option value="daily">Daily Report</option>
                                    <option value="weekly">Weekly Report</option>
                                    <option value="monthly">Monthly Report</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Frequency</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]">
                                    <option value="daily">Every Day</option>
                                    <option value="weekly">Every Week</option>
                                    <option value="biweekly">Every Two Weeks</option>
                                    <option value="monthly">Every Month</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Send Time</label>
                                <input type="time" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]" value="09:00">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recipients (Email addresses)</label>
                                <textarea placeholder="admin@inventory360.com&#10;manager@inventory360.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-[#c8d44e]" rows="3"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Separate multiple emails with newlines</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 text-[#c8d44e] rounded" checked>
                                    <span class="text-sm text-gray-700">Include PDF format</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer mt-2">
                                    <input type="checkbox" class="w-4 h-4 text-[#c8d44e] rounded" checked>
                                    <span class="text-sm text-gray-700">Include Excel format</span>
                                </label>
                            </div>

                            <button type="submit" class="md:col-span-2 px-6 py-3 bg-[#c8d44e] text-black font-semibold rounded-lg hover:bg-opacity-90 transition">
                                Create Scheduled Report
                            </button>
                        </form>
                    </div>

                    <!-- Scheduled Reports List -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Active Scheduled Reports</h3>
                        
                        <div class="space-y-4">
                            @foreach($scheduledReports as $report)
                                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition {{ !$report['active'] ? 'opacity-60 bg-gray-50' : 'bg-white' }}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h4 class="text-base font-semibold text-gray-800">{{ $report['name'] }}</h4>
                                                <span class="inline-block {{ $report['active'] ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }} px-3 py-1 rounded-full text-xs font-semibold">
                                                    {{ $report['active'] ? 'Active' : 'Inactive' }}
                                                </span>
                                                <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                    {{ ucfirst($report['type']) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-2">{{ $report['frequency'] }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                                <i data-lucide="edit" class="w-5 h-5"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-600">Recipients</p>
                                            <p class="text-gray-800 font-medium">{{ count($report['recipients']) }} recipient(s)</p>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @foreach($report['recipients'] as $email)
                                                    <div>{{ $email }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Last Sent</p>
                                            <p class="text-gray-800 font-medium">{{ \DateTime::createFromFormat('Y-m-d H:i:s', $report['last_sent'])->format('M d, Y H:i') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Next Send</p>
                                            <p class="text-gray-800 font-medium">{{ \DateTime::createFromFormat('Y-m-d H:i:s', $report['next_send'])->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Report Delivery History -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Report Delivery History</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Report Name</th>
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Sent Date & Time</th>
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Recipients</th>
                                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Status</th>
                                        <th class="text-center py-3 px-4 text-gray-700 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 px-4 text-gray-800 font-medium">Weekly Sales Summary</td>
                                        <td class="py-4 px-4 text-gray-700">March 02, 2026 09:15 AM</td>
                                        <td class="py-4 px-4 text-gray-700">2 recipients</td>
                                        <td class="py-4 px-4">
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Delivered
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 px-4 text-gray-800 font-medium">Monthly Performance Report</td>
                                        <td class="py-4 px-4 text-gray-700">March 01, 2026 08:30 AM</td>
                                        <td class="py-4 px-4 text-gray-700">1 recipient</td>
                                        <td class="py-4 px-4">
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Delivered
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 px-4 text-gray-800 font-medium">Daily Quick Report</td>
                                        <td class="py-4 px-4 text-gray-700">March 03, 2026 06:05 PM</td>
                                        <td class="py-4 px-4 text-gray-700">1 recipient</td>
                                        <td class="py-4 px-4">
                                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Retry</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
