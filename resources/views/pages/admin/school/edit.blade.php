@extends('pages.admin.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Admin | School | Edit'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit School</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">School</li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.school.list') }}">List</a></li>
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
                            <h3 class="card-title">School Information</h3>
                            <div class="card-tools mr-5">
                                <a href="{{ route('admin.school.list') }}"><button type="button" class="btn btn-tool"><i
                                            class="fas fa-arrow-left"></i>
                                            Back
                                    </button></a>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.school.update', $school->id) }}">
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
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select School Head</label>
                                            <select name="head_id" id="head_id"
                                                class="form-control @error('head_id') is-invalid @enderror select2"
                                                autofocus>
                                                @if (!empty($school->head_id))
                                                    <option value="{{ $school->head_id }}">{{ $school->head->getName() }}</option>
                                                    <option value="">None for now</option>
                                                @else
                                                    <option value="">None for now</option>
                                                @endif
                                                @foreach ($school_head_list as $school_head)
                                                    @continue($school_head->id == $school->head_id)
                                                    <option value="{{ $school_head->id }}">
                                                        {{ $school_head->getName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('head_id')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>School Name</label> <i class="text-danger font-weight-bold">*</i>
                                            <input id="name" placeholder="Enter School Name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ $school->name }}" required autocomplete="name">
                                            @error('name')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ $school->description }}</textarea>
                                            @error('description')
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
