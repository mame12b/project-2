@extends('pages.department.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Department | Intern | List'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Intern List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Intern</li>
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
                            <h3 class="card-title">Intern List</h3>
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
                                        <th>Full Name</th>
                                        <th>Internship</th>
                                        <th>Start Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($interns as $intern)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucwords($intern->user->getName()) }}</td>
                                            <td><a href="{{ route('department.internship.view', $intern->internship->id) }}">{{ $intern->internship->title }}</a></td>
                                            <td
                                                @class([
                                                    'text-warnign' => $intern->internship->isStarted(),
                                                    'text-danger' => $intern->internship->isEnded(),
                                                    'text-success' => !$intern->internship->isDeadlinePassed()
                                                ])
                                            >
                                                {{ \Carbon\Carbon::parse($intern->internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('department.intern.view', $intern->id) }}">
                                                    <button class="btn btn-info btn-xs btn-flat">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </button>
                                                </a>
                                                <a href="{{ route('department.intern.delete', $intern->id) }}" onclick="if(confirm('Are you sure, you want to delete {{ $intern->user->getName() }}? This action will also deletes user\'s application!') == false){event.preventDefault()}">
                                                    <button class="btn btn-danger btn-xs btn-flat">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </a>
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
