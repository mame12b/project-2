@extends('pages.admin.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Admin | Staff | Edit'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Staff</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Staff</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.staff.list') }}">List</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Staff Information</h3>
                            <div class="card-tools mr-5">
                                <a href="{{ route('admin.staff.list') }}"><button type="button" class="btn btn-tool"><i
                                            class="fas fa-arrow-left"></i>
                                            Back
                                    </button></a>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
                            @csrf
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
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>First Name</label> <i class="text-danger font-weight-bold">*</i>
                                            <input id="first_name" placeholder="Enter First Name" type="text"
                                                class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                                value="{{ $staff->information->first_name }}" required autocomplete="first_name">
                                            @error('first_name')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Middle Name</label> <i class="text-danger font-weight-bold">*</i>
                                            <input id="middle_name" placeholder="Enter Middle Name" type="text"
                                                class="form-control @error('middle_name') is-invalid @enderror" name="middle_name"
                                                value="{{ $staff->information->middle_name }}" required autocomplete="middle_name">
                                            @error('middle_name')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input id="last_name" placeholder="Enter Last Name" type="text"
                                                class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                                value="{{ $staff->information->last_name }}" autocomplete="last_name">
                                            @error('last_name')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>

                                    <div class="col-md-3"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label> <i class="text-danger font-weight-bold">*</i>
                                            <input id="email" placeholder="Enter Email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ $staff->email }}" required autocomplete="email">
                                            @error('email')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer ">
                                <p class=" float-left"><i class="text-danger font-weight-bold">*</i> are
                                    required fields</p>
                                <button type="submit" class="btn btn-primary float-right">Update</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
