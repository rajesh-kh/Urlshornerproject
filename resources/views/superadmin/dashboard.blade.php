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
        /* Ensure tables render correctly even without Tailwind build */
        table[min-w-full], table.min-w-full, table#super-clients-table, table#super-generated-table {
            display: table; width: 100%; border-collapse: collapse; font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }
            table thead th { display: table-cell; padding: 8px; background: #f9fafb; color:#374151; border:1px solid #e5e7eb; text-align:left }
            table tbody td { display: table-cell; padding: 8px; border:1px solid #e5e7eb; color:#111827; vertical-align:top }
        .overflow-x-auto { overflow-x:auto }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">Clients</h3>
                    <a href="{{ route('clients.invite') }}" class="btn btn-blue px-4 py-2">Invite</a>
                </div>

                    <div class="mb-6">
                    <div class="overflow-x-auto">
                        <table id="super-clients-table" class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Client name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Users count</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Total generated url</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Total url hits</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($companies as $c)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $c->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $c->users_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $c->total_urls }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $c->total_hits }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex items-center mb-3">
                        <h3 class="font-semibold">Generated Short URLs</h3>
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
                        <table id="super-generated-table" class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Short url</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Long url</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Hits</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Client name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Created at</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($generated as $s)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->slug }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->original_url }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->hits ?? 0 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b">{{ $s->company?->name }}</td>
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
        </div>
    </div>

    <!-- Vanilla-DataTables (fallback, no build required) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            try{ if(document.querySelector('#super-clients-table')) new DataTable('#super-clients-table', { perPage:10, searchable:true, sortable:true }); }catch(e){console.error(e)}
            try{ if(document.querySelector('#super-generated-table')) new DataTable('#super-generated-table', { perPage:25, searchable:true, sortable:true }); }catch(e){console.error(e)}
        });
    </script>
</x-app-layout>
