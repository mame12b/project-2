@extends('pages.school.inc.app')

@section('header')
    @include('layout.header', ['title' => 'School | Application | List'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Application List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Report</li>
                        <li class="breadcrumb-item">Application</li>
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
                            <form action="{{ route('school.reports.application') }}" method="get">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Accepted</option>
                                                <option value="2">Rejected</option>
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
                                            <label>Internship</label>
                                            <select name="internship" class="form-control select2bs4">
                                                <option value="">-- select --</option>
                                                @foreach ($internships as $internship)
                                                    <option value="{{ $internship->id }}">{{ $internship->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('internship')
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
                            <h3 class="card-title">Application List</h3>
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
                                        <th>Applicant Name</th>
                                        <th>Internship</th>
                                        <th>Internship Status</th>
                                        <th>Applied Date</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Application Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucwords($application->user->getName()) }}</td>
                                            <td>{{ ucwords($application->internship->title) }}</td>
                                            <td>
                                                @if ($application->internship->status == 0)
                                                    <span class="text-danger">Ended</span>
                                                @elseif ($application->internship->status == 1)
                                                    <span class="text-success">Accepting Applicants</span>
                                                @elseif ($application->internship->status == 2)
                                                    <span class="text-warning">Ongoing</span>
                                                @elseif ($application->internship->status == 3)
                                                    <span class="text-info">Waiting</span>
                                                @elseif ($application->internship->status == 4)
                                                    <span class="text-danger">Aborted</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($application->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($application->internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($application->internship->end_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}
                                            </td>
                                            <td>
                                                @if ($application->status == 0)
                                                    <span class="text-warning">Pending</span>
                                                @elseif ($application->status == 1)
                                                    <span class="text-success">Accepted</span>
                                                @elseif ($application->status == 2)
                                                    <span class="text-danger">Rejected</span>
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
            filename: 'Application List ' + Date.now(),
            title: 'Application List ',
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
                                text: 'Application Status:',
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
                    @if (isset($_GET['internship']) && $_GET['internship']!= null)
                    {
                        alignment: 'justify',
                        fontSize: 15,
                        margin: [0, 5, 0, 0],
                        columns: [{
                                text: 'Internship:',
                                alignment: 'left'
                            },
                            {
                                text: $(`select[name=internship] option[value="{{$_GET['internship']}}"]`).html(),
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
                doc.styles.message = {
                    fontSize: 12,
                }
                console.log(doc);
            }
        }
    </script>
@endsection
