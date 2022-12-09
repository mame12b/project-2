<!-- Jquery -->
<script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
<!-- Jquery UI -->
<script src="{{ asset('assets/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap -->
<script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Data Tables -->
<script src="{{ asset('assets/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- Overlay Scrollbars -->
<script src="{{ asset('assets/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('assets/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- Moment JS -->
<script src="{{ asset('assets/moment/moment.min.js') }}"></script>
<!-- Chart JS -->
<script src="{{ asset('assets/chart.js/Chart.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Admin LTE -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<!-- Socket.io -->
<script src="https://cdn.socket.io/4.5.0/socket.io.min.js"></script>
<!-- Custom Script -->
<script>
    var pdf_config = (typeof pdf_config !== 'undefined') ? pdf_config : 'pdf';
    $(function() {
        //Initialize toaster
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000
        });
        //Initialize datatable
        $("#dataTable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        // Intializing DataTable for reports
        $("#dataTableReports").DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching": false,
            "buttons": ["csv", "excel", pdf_config, "print", "colvis"]
        }).buttons().container().appendTo('#dataTableReports_wrapper > div.row > div.col-sm-12:eq(1)');
        // customize the buttons
        $('#dataTable_wrapper > div.row > div.col-sm-12:eq(1) > div.dt-buttons').addClass('float-right');
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: moment().startOf('month').format('YYYY-MM-DD')
        });

        $('#reservationdate2').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: moment().format('YYYY-MM-DD')
        });

        ///Date and time picker
        $('#reservationdatetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                time: 'far fa-clock'
            }
        });
        $('#reservationdatetime2').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                time: 'far fa-clock'
            }
        });
        $('#reservationdatetime3').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                time: 'far fa-clock'
            }
        });
        @if (request()->is('*/reports*'))
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: {!! "'" . session('success') . "'" !!}
                });
            @endif

            @if (session('error'))
            $('#modal-request-report').modal('show');
            @endif
            @error('end_date')
            $('#modal-request-report').modal('show');
            @enderror
            @error('start_date')
            $('#modal-request-report').modal('show');
            @enderror
        @endif

        @if (request()->is('department/application/view*'))
        async function fireAlertForMailing(is_messaged, is_mailed) {

            await Toast.fire({
                icon: (is_messaged == '1') ? 'success' : 'error',
                title: (is_messaged == '1') ? 'Confirmation Message sented to the user' : 'Failed to send Confirmation Message to the user'
            });

            await Toast.fire({
                icon: (is_mailed == '1') ? 'success' : 'error',
                title: (is_mailed == '1') ? 'Confirmation Mail sented to the user' : 'Failed to send Confirmation Mail to the user'
            });
        }
        @if (isset($_GET['is_messaged']) && isset($_GET['is_mailed']))
        fireAlertForMailing('{{ $_GET["is_messaged"] }}', '{{ $_GET["is_mailed"] }}');
        @endif
        @endif

        @if (request()->is('department/internship/list*'))
        async function fireAlertForMailing(is_messaged, is_mailed) {

            await Toast.fire({
                icon: (is_messaged == '1') ? 'success' : 'error',
                title: (is_messaged == '1') ? 'Confirmation Message sented to all interns' : 'Failed to send Confirmation Message to all interns'
            });

            await Toast.fire({
                icon: (is_mailed == '1') ? 'success' : 'error',
                title: (is_mailed == '1') ? 'Confirmation Mail sented to all interns' : 'Failed to send Confirmation Mail to all interns'
            });
        }
        @if (isset($_GET['is_messaged']) && isset($_GET['is_mailed']))
        fireAlertForMailing('{{ $_GET["is_messaged"] }}', '{{ $_GET["is_mailed"] }}');
        @endif
        @endif

        @if (!request()->is('user/home') && request()->is('*/home'))
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: {!! "'" . session('error') . "'" !!}
                })
            @endif
            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true
            var $applicationsChart = $('#applications-chart')
            // eslint-disable-next-line no-unused-vars
            var applicationsChart = new Chart($applicationsChart, {
                data: {
                    labels: {!! json_encode(array_keys($application_count['lastWeek'])) !!},
                    datasets: [{
                            type: 'line',
                            data: {!! json_encode(array_values($application_count['thisWeek'])) !!},
                            backgroundColor: 'transparent',
                            borderColor: '#007bff',
                            pointBorderColor: '#007bff',
                            pointBackgroundColor: '#007bff',
                            fill: false
                            // pointHoverBackgroundColor: '#007bff',
                            // pointHoverBorderColor    : '#007bff'
                        },
                        {
                            type: 'line',
                            data: {!! json_encode(array_values($application_count['lastWeek'])) !!},
                            backgroundColor: 'tansparent',
                            borderColor: '#ced4da',
                            pointBorderColor: '#ced4da',
                            pointBackgroundColor: '#ced4da',
                            fill: false
                            // pointHoverBackgroundColor: '#ced4da',
                            // pointHoverBorderColor    : '#ced4da'
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        @endif

        @if (request()->is('admin/profile*'))
        @if ($is_node_on->value == '1')
            $.get('{{ env('APP_URL') }}/api/node/get', function(data) {
                $('#tbodyNode').html(`
                <tr>
                    <td>1</td>
                    <td>${data.name}</td>
                    <td>${data.mode}</td>
                    <td>${data.status}</td>
                    <td>${data.date}</td>
                </tr>`);
            })
        @endif
        @endif
    });
    @if (request()->is('department/internship/add'))
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'));
        })
    @endif

    @if (request()->is('department/internship/add') || request()->is('department/internship/edit*'))
        @if (request()->is('department/internship/add'))
            var counter = 2;
        @else
            var counter = {{ $internship->prerequisites->count() + 1 }};
        @endif
        $('#inputRowAdder').click(function() {
            newRow =
                '<div class="input-group mt-2" id="inputGroupDiv">' +
                '<input placeholder="Enter key" name="prerequisite[' + counter +
                '][pre_key]" type="text" class="form-control" required/>' +
                '<div class="input-group-append">' +
                '<button class="btn btn-danger" id="inputRowDelete">' +
                '<i class="fa fa-minus"></i>' +
                '</button>' +
                '</div>' +
                '</div>';
            $('#inputDiv').append(newRow);
            counter++;
        });

        $("body").on("click", "#inputRowDelete", function() {
            $(this).parents("#inputGroupDiv").remove();
            counter--;
        })
        @if (request()->is('department/internship/edit*'))
            deleteOriginal = (it, id) => {
                inp = '<input type="hidden" name="prerequisite[' + it + '][deleted]" value="' + id + '">';
                if (confirm('Are you sure, you want to delete ?') == true) {
                    $("#inputGroupDiv" + id).append(inp)
                    $("#inputGroupDiv" + id).css('display', 'none');
                }
            }
        @endif
    @endif

    @if (request()->is('user/home'))
        document.addEventListener('DOMContentLoaded', function() {
            loadListOfInternships();
        })

        document.getElementById("searchQuerySubmitBtn").addEventListener("click", function(event) {
            event.preventDefault();
            var ras = $('#searchQuery').val();
            if (ras != undefined && ras != null && ras != '') {
                let dom =
                    '<div class="col-md-12 mt-5">' +
                    '<center>' +
                    '<i class="fas fa-3x fa-sync-alt fa-spin"></i>' +
                    '</center>' +
                    '</div>'
                $('#searchResultDiv').html(dom);
                searchInternship(ras);
            } else {
                refreshIntervalId = setInterval(function() {
                    $('#searchQuery').toggleClass('is-invalid');
                }, 300);

                setTimeout(function() {
                    clearInterval(refreshIntervalId)
                    $('#searchQuery').removeClass('is-invalid');
                }, 1500);
            }
        });

        function loadListOfInternships() {
            $.get('{{ env('APP_URL') }}/api/internship', function(data) {
                if (data.length == 0) {
                    let dom =
                        '<div class="col-md-12 mt-5">' +
                        '<center>' +
                        'Oops, we couldn\'t find any data!' +
                        '</center>' +
                        '</div>'
                    $('#searchResultDiv').html(dom);
                } else {
                    $('#searchResultDiv').html('');
                    for (var i = 0; i < data.length; i++) {
                        let dom =
                            '<div class="col-md-12">' +
                            '<div class="card">' +
                            '<div class="card-header">' +
                            '<h5 class="card-title m-0">' + data[i].department_name + '</h5>' +
                            '</div>' +
                            '<div class="card-body">' +
                            '<h6 class="card-title">' + data[i].title + '</h6>' +

                            '<p class="card-text msgTextTimp">' + data[i].description + '</p>' +
                            '<a  style="width:15%;" href="{{ env('APP_URL') }}/user/internship/view/' + data[i]
                            .id + '" class="btn btn-info float-right">View</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        $('#searchResultDiv').append(dom);
                    }
                    $(".msgTextTimp").each(function() {
                        $(this).text($(this).text().substr(0, 150));
                        $(this).append('...');
                    });
                }
            });
        }

        function searchInternship(ras) {
            $.get('{{ env('APP_URL') }}/api/internship/' + ras, function(data) {
                if (data.length == 0) {
                    let dom =
                        '<div class="col-md-12 mt-5">' +
                        '<center>' +
                        'Oops, we couldn\'t find any data!<a style="cursor:pointer;" class="fas fa-sync-alt ml-2" onClick="searchInternship(\'\')"></a>' +
                        '</center>' +
                        '</div>'
                    $('#searchResultDiv').html(dom);
                } else {
                    $('#searchResultDiv').html('');
                    for (var i = 0; i < data.length; i++) {
                        let dom =
                            '<div class="col-md-12">' +
                            '<div class="card">' +
                            '<div class="card-header">' +
                            '<h5 class="card-title m-0">' + data[i].department_name + '</h5>' +
                            '</div>' +
                            '<div class="card-body">' +
                            '<h6 class="card-title">' + data[i].title + '</h6>' +

                            '<p class="card-text">' + data[i].description + '</p>' +
                            '<a style="width:15%;"  href="{{ env('APP_URL') }}/user/internship/view/' + data[i]
                            .id + '" class="btn btn-info float-right">View</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        $('#searchResultDiv').append(dom);
                    }
                    $(".msgTextTimp").each(function() {
                        $(this).text($(this).text().substr(0, 150));
                        $(this).append('...');
                    });
                }
            });
        }
    @endif

    @if (request()->is('department/application/filter*') || request()->is('user/application/list*') || request()->is('*/reports*'))
        @if (isset($_GET['status']))
            $('select[name=status] option').each(function() {
                if ($(this).val() == @php echo '"'.$_GET['status'].'"' @endphp) {
                    $(this).attr('selected', true);
                    if ($(this).val() != '') {
                        $('#appliedFilters').append(`<li>${$(this).html()} application status</li>`);
                    }
                }
            });
        @endif

        @if (isset($_GET['cgpa_action']))
            $('select[name=cgpa_action] option').each(function() {
                if ($(this).val() == @php echo '"'.$_GET['cgpa_action'].'"' @endphp) {
                    $(this).attr('selected', true);
                    if (@php echo '"'.$_GET['cgpa_value'].'"' @endphp != '') {
                        let xd = @php echo '"'.$_GET['cgpa_value'].'"' @endphp;
                        $('#appliedFilters').append(`<li>CGPA ${$(this).html()} ${xd}</li>`);
                    }
                }
            });
        @endif

        @if (isset($_GET['internship']))
            $('select[name=internship] option').each(function() {
                if ($(this).val() == @php echo '"'.$_GET['internship'].'"' @endphp) {
                    $(this).attr('selected', true);
                    if ($(this).val() != '') {
                        $('#appliedFilters').append(`<li>only ${$(this).html()}</li>`);
                    }
                }
            });
        @endif

        @if (isset($_GET['date']))
            $('select[name=date] option').each(function() {
                if ($(this).val() == @php echo '"'.$_GET['date'].'"' @endphp) {
                    $(this).attr('selected', true);
                    if ($(this).val() != '') {
                        $('#appliedFilters').append(`<li>orderd by ${$(this).html()}</li>`);
                    }
                }
            });
        @endif

        @if (isset($_GET['cgpa_list']))
            $('select[name=cgpa_list] option').each(function() {
                if ($(this).val() == @php echo '"'.$_GET['cgpa_list'].'"' @endphp) {
                    $(this).attr('selected', true);
                    if ($(this).val() != '') {
                        $('#appliedFilters').append(`<li>orderd by ${$(this).html()} CGPA</li>`);
                    }
                }
            });
        @endif

        @if (isset($_GET['start_date']) && isset($_GET['end_date']))
            $('input[name=start_date]').val(`{{$_GET['start_date']}}`);
            $('input[name=end_date]').val(`{{$_GET['end_date']}}`);
            $('#appliedFilters').append(`<li>between {{$_GET['start_date']}} and {{$_GET['end_date']}}</li>`);
        @endif

        @if (isset($_GET['cgpa_value']) && $_GET['cgpa_value']!=null)
            $('input[name=cgpa_value]').val(`{{$_GET['cgpa_value']}}`);
        @endif

    @endif

    @if (request()->is('admin/profile*'))

    function nodeStopServer(e){
        $(e).html(`<i class="fas fa-sync-alt fa-spin"></i>`);
        let api = '{{ env('APP_URL') }}/api/node/stop';
        let mes = $('#nodeServerMessageViewr');
        let main = $('#nodeServerBodyViewr');
        $.post(api, function(data){
            if(data.status == 'success'){
                mes.addClass('alert alert-success alert-dismissible');
                main.html(`<div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="callout callout-danger">
                        <p>The Node server is currently Down!</p>
                    </div>
                    <button onclick="nodeStartServer(this)" class="btn btn-success float-right">Start Server</button>
                </div>
                <div class="col-md-3"></div>`);
            }else{
                mes.addClass('alert alert-danger alert-dismissible');
            }
            mes.html(`
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            ${data.message}
            `);
        });
    }

    function nodeStartServer(e){
        $(e).html(`<i class="fas fa-sync-alt fa-spin"></i>`);
        let api = '{{ env('APP_URL') }}/api/node/start';
        let mes = $('#nodeServerMessageViewr');
        let main = $('#nodeServerBodyViewr');
        $.post(api, function(data){
            if(data.status == 'success'){
                mes.addClass('alert alert-success alert-dismissible');
                main.html(`<div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="callout callout-success">
                        <p>The Node server is Up and Running!</p>
                    </div>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyNode">
                            <tr>
                                <td>1</td>
                                <td>${data.data.name}</td>
                                <td>${data.data.mode}</td>
                                <td>${data.data.status}</td>
                                <td>${data.data.date}</td>
                            </tr>
                        </tbody>
                    </table>
                    <button onclick="nodeStopServer(this)" class="btn btn-danger float-right">Stop Server</button>
                </div>
                <div class="col-md-2"></div>`);
            }else{
                mes.addClass('alert alert-danger alert-dismissible');
            }
            mes.html(`
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            ${data.message}
            `);
        });
    }
    @endif
</script>
