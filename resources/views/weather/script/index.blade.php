<script>
    var table = $("#the-data-table").DataTable({
        pageLength: 10,
        scrollX: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: `{{ route('show-weather') }}`,
            type: "POST",
            data: function(d) {
                // d.search = $("#search").val();
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [{
                data: 'location_name',
                name: 'location_name',
                orderable: false,
                searchable: false
            },
            {
                data: 'latlong',
                name: 'latlong',
                orderable: true,
                searchable: true
            },
            {
                data: 'timezone',
                name: 'timezone',
                orderable: true,
                searchable: true
            },
            {
                data: 'pressure',
                name: 'pressure',
                orderable: true,
                searchable: true
            },
            {
                data: 'humidity',
                name: 'humidity',
                orderable: true,
                searchable: true
            },
            {
                data: 'wind_speed',
                name: 'wind_speed',
                orderable: true,
                searchable: true
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                orderable: true,
                searchable: true
            },
        ],
        order: [
            [6, 'desc']
        ],
        dom: "rtip",
        createdRow: function(row, data, dataIndex) {
            $('td', row).css('vertical-align', 'middle');
        },
        // "language": {
        //     "lengthMenu": "Menampilkan _MENU_ data per halaman",
        //     "zeroRecords": "Data tidak ditemukan - maaf",
        //     "info": "Menampilkan halaman _PAGE_ dari total _PAGES_ halaman",
        //     "infoEmpty": "Tidak ada data tersedia",
        //     "infoFiltered": "(Disaring dari _MAX_ total data)"
        // }
    });
</script>
