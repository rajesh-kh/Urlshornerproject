<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Short URLs</h2>
            <div>
                <a href="/short-urls/create" class="btn">Create</a>
                <a href="/short-urls/download" class="btn">Download CSV</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="text-green-600">{{ session('success') }}</div>
                    @endif
                                <div class="flex items-center gap-3 mb-4">
                                    <a href="/short-urls/create" class="btn btn-primary">Create</a>
                                    <a href="/short-urls/download" class="btn border px-3 py-1 rounded">Download CSV</a>
                                    <a href="/short-urls" class="btn border px-3 py-1 rounded">Refresh</a>
                                    <form method="GET" action="/short-urls" class="ml-auto">
                                        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search slug or URL" class="border p-1" />
                                        <button class="btn btn-outline">Search</button>
                                    </form>
                                </div>

                                    <!-- DataTables-enabled table (inline CSS fallback if Tailwind not built) -->
                                    <style>
                                        /* Inline fallback styles to ensure table layout without Tailwind */
                                        #shortUrlsTable{border-collapse:collapse;width:100%;font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,"Helvetica Neue",Arial}
                                        #shortUrlsTable thead th{background:#f9fafb;color:#6b7280;font-size:12px;padding:10px;border:1px solid #e5e7eb;text-align:left}
                                        #shortUrlsTable tbody td{padding:10px;border:1px solid #e5e7eb;color:#111827;vertical-align:top}
                                        #shortUrlsTable tr:nth-child(even){background:#ffffff}
                                        #shortUrlsTable tr:nth-child(odd){background:#ffffff}
                                        a.btn, .btn, .btn-primary, .btn-outline{display:inline-block;padding:6px 10px;border-radius:6px;text-decoration:none}
                                        .btn-primary{background:#f53003;color:#fff}
                                        .btn-outline{border:1px solid #d1d5db;color:#111827;background:#fff}
                                        .text-sm{font-size:0.875rem}
                                    </style>

                                    <table id="shortUrlsTable" class="min-w-full divide-y divide-gray-200 stripe hover" style="width:100%">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach($shortUrls as $s)
                            <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><a href="/s/{{ $s->slug }}">{{ $s->slug }}</a></td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 break-words max-w-xl">{{ $s->original_url }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($s->company)->name ?? $s->company_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($s->creator)->name ?? $s->created_by }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="/short-urls/{{ $s->id }}" class="text-sm text-blue-600">Details</a>
                                        <a href="/s/{{ $s->slug }}" target="_blank" class="text-sm text-green-600 ml-2">Open</a>
                                        <form method="POST" action="/short-urls/{{ $s->id }}" style="display:inline-block" onsubmit="return confirm('Delete this short URL?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-sm text-red-600 ml-2">Delete</button>
                                        </form>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- DataTables CSS/JS (CDN) -->
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
                    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
                    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
                    <!-- Vanilla-DataTables fallback CSS/JS (pure JS, no jQuery needed) -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.css">
                    <script src="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.js"></script>
                    <script>
                        (function(){
                            function loadScript(src){
                                return new Promise(function(resolve, reject){
                                    var s = document.createElement('script');
                                    s.src = src;
                                    s.onload = resolve;
                                    s.onerror = reject;
                                    document.head.appendChild(s);
                                });
                            }

                            function ensureJquery(){
                                if(window.jQuery) return Promise.resolve();
                                return loadScript('https://code.jquery.com/jquery-3.7.1.min.js');
                            }

                            function ensureDataTables(){
                                if(window.jQuery && jQuery.fn && jQuery.fn.DataTable) return Promise.resolve();
                                return loadScript('https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js')
                                    .then(function(){
                                        return loadScript('https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js');
                                    })
                                    .then(function(){
                                        return loadScript('https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js');
                                    });
                            }

                            function initTable(){
                                try{
                                    if(! (window.jQuery && jQuery.fn && jQuery.fn.DataTable)){
                                        console.warn('DataTables not available — applying simple fallback styling');
                                        return;
                                    }

                                    jQuery(function($){
                                        var table = $('#shortUrlsTable').DataTable({
                                            dom: 'Bfrtip',
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                            order: [[4, 'desc']],
                                            columnDefs: [ { targets: 4, type: 'date' } ],
                                            buttons: [
                                                { extend: 'copyHtml5', className: 'btn border px-3 py-1 rounded' },
                                                { extend: 'csvHtml5', className: 'btn border px-3 py-1 rounded' },
                                                { extend: 'excelHtml5', className: 'btn border px-3 py-1 rounded' },
                                                { extend: 'pdfHtml5', className: 'btn border px-3 py-1 rounded' },
                                                { extend: 'print', className: 'btn border px-3 py-1 rounded' }
                                            ]
                                        });
                                        // move buttons into header area
                                        table.buttons().container().appendTo('#shortUrlsTable_wrapper .col-md-6:eq(0)');
                                    });
                                }catch(e){
                                    console.error('Failed to initialize DataTables', e);
                                }
                            }

                            // sequentially ensure scripts then init DataTables (jQuery) or fallback to Vanilla-DataTables
                            ensureJquery()
                                .then(ensureDataTables)
                                .then(function(){ initTable(); })
                                .catch(function(err){
                                    console.warn('jQuery DataTables failed — falling back to Vanilla-DataTables', err);
                                    // try vanilla (already loaded via CDN link above)
                                    setTimeout(function(){
                                        if(window.DataTable){
                                            // initialize vanilla-datatables
                                            try{
                                                new DataTable('#shortUrlsTable', { perPage:25, searchable:true, sortable:true });
                                            }catch(e){
                                                console.error('Vanilla-DataTables init failed', e);
                                            }
                                        } else {
                                            console.error('No DataTables implementation available');
                                        }
                                    }, 200);
                                });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
