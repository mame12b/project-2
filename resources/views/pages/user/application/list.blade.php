@extends('pages.user.inc.app')

@section('header')
    @include('layout.header', ['title' => 'User | Application | List'])
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

                @if (session('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-ban"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
                @if (session('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-check"></i>
                            {!! session('success') !!}
                        </div>
                    </div>
                @endif
            </div>
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
                        <form action="{{ route('user.application.list') }}" method="get">
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
                                <div class="col-md-4"></div>
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
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Internship</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applications as $application)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><a
                                                href="{{ route('user.internship.view', $application->internship->id) }}">{{ $application->internship->title }}</a>
                                        </td>
                                        <td>
                                            @if ($application->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($application->status == 1)
                                                <span class="badge badge-success">Accepted</span>
                                            @elseif ($application->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($application->created_at)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}
                                        <td>{{ \Carbon\Carbon::parse($application->internship->start_date)->setTimezone('Africa/Addis_Ababa')->format('M d, Y') }}
                                        </td>
                                        <td>
                                            @if ($application->status != 1)
                                                <a href="{{ route('user.application.delete', $application->id) }}"
                                                    onclick="if(confirm('Are you sure, you want to revoke this Application?') == false){event.preventDefault()}">
                                                    <button class="btn btn-danger btn-xs btn-flat">
                                                        <i class="fas fa-trash"></i>
                                                        Revoke
                                                    </button>
                                                </a>
                                            @else
                                                No action required
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
