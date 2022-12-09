@extends('pages.school.inc.app')

@section('header')
    @include('layout.header', ['title' => 'School | Internship | List'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Internship List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Report</li>
                        <li class="breadcrumb-item">Internship</li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary @if ($isFilterActivated) collapsed-card @endif">
                        <div class="card-header">
                            <h4 class="card-title">
                                Advanced Filter
                                @if ($isFilterActivated)
                                    <span class="badge badge-success">Activated</span>
                                @endif
                            </h4>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    @if ($isFilterActivated)
                                        <i class="fas fa-plus"></i>
                                    @else
                                        <i class="fas fa-minus"></i>
                                    @endif
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('school.reports.internship') }}" method="get">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="0">Closed</option>
                                                <option value="1">Accepting Applicants</option>
                                                <option value="2">Started</option>
                                                <option value="3">Waiting to Start</option>
                                                <option value="4">Aborted</option>
                                            </select>
                                            @error('status')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Start Date:</label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                    data-target="#reservationdate" name="start_date" required/>
                                                <div class="input-group-append" data-target="#reservationdate"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @error('start_date')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="form-group">
                                            <label>Date</label>
                                            <select name="date" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="desc">Newest First</option>
                                                <option value="asc">Oldest First</option>
                                            </select>
                                            @error('date')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>End Date:</label>
                                            <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                    data-target="#reservationdate2" name="end_date" />
                                                <div class="input-group-append" data-target="#reservationdate2"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @error('end_date')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        @if ($isFilterActivated)
                                            <p><b>Applied Filters:</b></p>
                                        @endif
                                        <ul id="appliedFilters">
                                        </ul>
                                    </div>
                                </div>
                                <button class="btn btn-info float-right">
                                    <i class="fas fa-filter mr-2"></i>
                                    Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Internship List</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="icon fas fa-ban"></i>
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">×</button>
                                    <i class="icon fas fa-check"></i>
                                    {!! session('success') !!}
                                </div>
                            @endif
                            <table id="dataTableReports" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Department</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>DeadLine</th>
                                        <th>Qouta</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($internships as $internship)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($internship->title) }}</td>
                                        <td>{{ ucwords($internship->department->name) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($internship->deadline)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}</td>
                                        <td>{{ $internship->quota }}</td>
                                        <td>
                                            @if ($internship->status == 0)
                                                <span class="text-danger">Ended</span>
                                            @elseif ($internship->status == 1)
                                                <span class="text-success">Accepting Applicants</span>
                                            @elseif ($internship->status == 2)
                                                <span class="text-warning">Ongoing</span>
                                            @elseif ($internship->status == 3)
                                                <span class="text-info">Waiting</span>
                                            @elseif ($internship->status == 4)
                                                <span class="text-danger">Aborted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <script>
        var pdf_config = {
            extend: 'pdf',
            messageBottom: 'temp',
            messageTop: 'temp',
            filename: 'Internship List ' + Date.now(),
            title: 'Internship List ',
            exportOptions: {
                columns: ':visible',
            },
            customize: function(doc) {

                doc.content[1] = [{
                        alignment: 'justify',
                        columns: [{
                                text: 'Jimma University',
                                fontSize: 25,
                                bold: true
                            },
                            {
                                text: 'Date: ' + moment().format('MMM DD, YYYY'),
                                alignment: 'right'
                            }
                        ]
                    },
                    {
                        text: 'Informations',
                        margin: [0, 15, 0, 0],
                        fontSize: 18
                    },
                    {
                        alignment: 'justify',
                        fontSize: 15,
                        margin: [0, 20, 0, 0],
                        columns: [{
                                text: 'Issue Date:',
                                alignment: 'left'
                            },
                            {
                                text: moment().format('MMM DD, YYYY'),
                                alignment: 'right'
                            },
                            {
                                text: '',
                                alignment: ''
                            }
                        ]
                    },
                    {
                        alignment: 'justify',
                        fontSize: 15,
                        margin: [0, 5, 0, 0],
                        columns: [{
                                text: 'Start Date:',
                                alignment: 'left'
                            },
                            {
                                text: @if(isset($_GET['start_date'])) moment(`{{$_GET['start_date']}}`).format('MMM DD, YYYY') @else '--' @endif,
                                alignment: 'right'
                            },
                            {
                                text: '',
                                alignment: ''
                            }
                        ]
                    },
                    {
                        alignment: 'justify',
                        fontSize: 15,
                        margin: [0, 5, 0, 0],
                        columns: [{
                                text: 'End Date:',
                                alignment: 'left'
                            },
                            {
                                text: @if(isset($_GET['end_date'])) moment(`{{$_GET['end_date']}}`).format('MMM DD, YYYY') @else '--' @endif,
                                alignment: 'right'
                            },
                            {
                                text: '',
                                alignment: ''
                            }
                        ]
                    },
                    @if (isset($_GET['status']) && $_GET['status']!= null)
                    {
                        alignment: 'justify',
                        fontSize: 15,
                        margin: [0, 5, 0, 0],
                        columns: [{
                                text: 'Internship Status:',
                                alignment: 'left'
                            },
                            {
                                text: $(`select[name=status] option[value="{{$_GET['status']}}"]`).html(),
                                alignment: 'right'
                            },
                            {
                                text: '',
                                alignment: ''
                            }
                        ]
                    },
                    @endif
                    {
                        alignment: 'justify',
                        fontSize: 15,
                        margin: [0, 5, 0, 30],
                        columns: [{
                                text: 'Report For:',
                                alignment: 'left'
                            },
                            {
                                text: '{{auth()->user()->school->name}} - School',
                                alignment: ''
                            }
                        ]
                    }
                ];

                doc.content[2]._minWidth = 100
                doc.content[2]._maxWidth = 100

                doc.content[3] = [{
                    text: 'Name: _____________________________',
                    margin: [0, 30, 0, 0]
                }, {
                    text: 'Signature: _____________________________',
                    margin: [0, 10, 0, 0]
                }];
                doc.defaultStyle = {
                    fontSize: 12,
                }
                console.log(doc);
            }
        }
    </script>
@endsection
