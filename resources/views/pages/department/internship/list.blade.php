@extends('pages.department.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Department | Internship | List'])
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

                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Internship List</h3>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">×</button>
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
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Qouta</th>
                                        <th>DeadLine</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($internships as $internship)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $internship->title }}</td>
                                            <td>{{ $internship->quota }}</td>
                                            <td class="@if ($internship->isDeadlinePassed())->isPast()) text-danger @else text-success @endif">{{ \Carbon\Carbon::parse($internship->deadline)->setTimezone('Africa/Addis_Ababa')->format('d/m/Y \a\t H:i a') }}</td>
                                            <td>
                                                @if ($internship->status == 0)
                                                    <span class="badge badge-danger">Ended</span>
                                                @elseif ($internship->status == 1)
                                                    <span class="badge badge-success">Accepting Applicants</span>
                                                @elseif ($internship->status == 2)
                                                    <span class="badge badge-warning">Ongoing</span>
                                                @elseif ($internship->status == 3)
                                                    <span class="badge badge-info">Waiting</span>
                                                @elseif ($internship->status == 4)
                                                    <span class="badge badge-danger">Aborted</span>
                                                @endif
                                            </td>
                                            <td>@if (!$internship->isEnded() && $internship->isStarted() && $internship->status != '2')
                                                <a href="{{ route('department.internship.start', $internship->id) }}">
                                                    <button class="btn btn-success btn-xs btn-flat">
                                                        <i class="fas fa-check"></i>
                                                        Start
                                                    </button>
                                                </a>
                                                @endif
                                                <a href="{{ route('department.internship.view', $internship->id) }}">
                                                    <button class="btn btn-info btn-xs btn-flat">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </button>
                                                </a>
                                                <a href="{{ route('department.internship.edit', $internship->id) }}">
                                                    <button class="btn btn-primary btn-xs btn-flat">
                                                        <i class="fas fa-edit"></i>
                                                        Edit
                                                    </button>
                                                </a>
                                                @if ($internship->isEnded() && $internship->status == '2')
                                                <a href="{{ route('department.internship.delete', $internship->id) }}" onclick="if(confirm('Are you sure, you want to End {{ $internship->title }}?') == false){event.preventDefault()}">
                                                    <button class="btn btn-danger btn-xs btn-flat">
                                                        <i class="fas fa-times"></i>
                                                        End
                                                    </button>
                                                </a>
                                                @else
                                                <a href="{{ route('department.internship.delete', $internship->id) }}" onclick="if(confirm('Are you sure, you want to delete {{ $internship->title }}?') == false){event.preventDefault()}">
                                                    <button class="btn btn-danger btn-xs btn-flat">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </a>
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
@endsection
