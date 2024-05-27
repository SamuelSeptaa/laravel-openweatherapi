<script>
    function deleteWeather(id) {
        if (confirm("Hapus data?") == true) {
            debugger;
            $.ajax({
                type: "delete",
                dataType: "json",
                url: "{{ route('destroy-weather') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                },
                beforeSend: function() {
                    showLoading();
                },
                success: function(response) {
                    $("#alert-message-success").find(".alert-body").html(response
                        .message);
                    $("#alert-message-success").fadeIn(200);

                },
                error: function(xhr, status, error) {
                    const responseJson = xhr.responseJSON;
                    $("#alert-message-error").find(".alert-body").html(responseJson
                        .message);
                    $("#alert-message-error").fadeIn(200)
                    switch (xhr.status) {
                        case 422:
                            const errors = Object.entries(responseJson.errors);
                            errors.forEach(([field, message]) => {
                                field = field.replace('.', '_');
                                $(`div.invalid-feedback[for="${field}"]`).html(
                                    message);
                                $(`#${field}`).addClass('is-invalid');
                            });
                            setTimeout(
                                function() {
                                    $("#alert-message-error").fadeOut(300)
                                }, 2000);
                            break;
                    }
                },
                complete: function() {
                    hideLoading();
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                    setTimeout(
                        function() {
                            $("#alert-message-error").fadeOut(300)
                            $("#alert-message-success").fadeOut(300)
                        }, 3000);
                    $("#the-data-table").DataTable().ajax.reload();
                }
            });
        }

    }

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
                orderable: true,
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
            {
                data: 'action',
                name: 'action',
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
