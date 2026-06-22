<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="text-lg font-semibold">Dashboard</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 border border-red-600 text-red-600 rounded hover:bg-red-50">Logout</button>
            </form>
        </div>
        <hr class="mt-2" />
    </x-slot>

    <style>
        table#member-generated-table, table.min-w-full {
            display: table; width:100%; border-collapse:collapse; font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }
        table thead th { padding:8px; background:#f9fafb; color:#374151; border:1px solid #e5e7eb; text-align:left }
        table tbody td { padding:8px; border:1px solid #e5e7eb; color:#111827 }
        .overflow-x-auto { overflow-x:auto }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <h3 class="font-semibold">Generate short url</h3>
                    {{-- <div class="font-semibold">Generate short url</div> --}}
                    <a href="/short-urls/create" class="btn btn-blue px-4 py-2 ml-4">Generate</a>
                    <div class="ml-auto flex items-center gap-2">
                        <form method="GET">
                            <select name="range" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                                <option value="this_month" {{ $range==='this_month' ? 'selected' : '' }}>This month</option>
                                <option value="last_month" {{ $range==='last_month' ? 'selected' : '' }}>Last month</option>
                                <option value="last_week" {{ $range==='last_week' ? 'selected' : '' }}>Last week</option>
                                <option value="today" {{ $range==='today' ? 'selected' : '' }}>Today</option>
                            </select>
                        </form>
                        <a href="/short-urls/download?range={{ $range }}" class="btn btn-blue px-4 py-2">Download</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="member-generated-table" class="min-w-full divide-y divide-gray-200 border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Short url</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Long url</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Hits</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Created on</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($generated as $s)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->original_url }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->hits ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
            <div class="mt-3 text-right">
                <a href="/dashboard" class="btn btn-outline text-sm">View all</a>
            </div>
        </div>
    </div>

    <!-- Vanilla-DataTables for Member dashboard -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.js"></script>
<script>
        document.addEventListener('DOMContentLoaded', function(){
            try{ if(document.querySelector('#member-generated-table')) new DataTable('#member-generated-table', { perPage:25, searchable:true, sortable:true }); }catch(e){console.error(e)}
        });
    </script>
</x-app-layout>
