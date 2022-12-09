@extends('pages.department.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Department | Internship | Edit'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Internship</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Internship</li>
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
                    <form method="POST" action="{{ route('department.internship.update', $internship->id) }}">
                        @csrf
                        <input type="hidden" name="department_id" value="{{ (int) Auth::user()->department->id }}">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Internship Information</h3>
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
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title</label> <i class="text-danger font-weight-bold">*</i>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $internship->title }}" required>
                                        </div>
                                        @error('title')
                                            <span class="text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        <div class="form-group">
                                            <label>Minimum CGPA</label>
                                            <input type="text" name="minimum_cgpa" class="form-control"
                                                value="{{ $internship->minimum_cgpa }}">
                                        </div>
                                        @error('minimum_cgpa')
                                            <span class="text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control"></textarea>
                                        </div>
                                        @error('description')
                                            <span class="text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <div class="input-group date" id="reservationdatetime"
                                                data-target-input="nearest">
                                                <input type="text" name="start_date"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#reservationdatetime"
                                                    value="{{ $internship->start_date }}" />
                                                <div class="input-group-append" data-target="#reservationdatetime"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('start_date')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <div class="input-group date" id="reservationdatetime2"
                                                data-target-input="nearest">
                                                <input type="text" name="end_date"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#reservationdatetime2"
                                                    value="{{ $internship->end_date }}" />
                                                <div class="input-group-append" data-target="#reservationdatetime2"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('end_date')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Quota</label>
                                            <input type="number" name="quota" class="form-control"
                                                value="{{ $internship->quota }}">
                                        </div>
                                        @error('quota')
                                            <span class="text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>DeadLine</label> <i class="text-danger font-weight-bold">*</i>
                                            <div class="input-group date" id="reservationdatetime3"
                                                data-target-input="nearest">
                                                <input type="text" name="deadline"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#reservationdatetime3"
                                                    value="{{ $internship->deadline }}" required />
                                                <div class="input-group-append" data-target="#reservationdatetime3"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('deadline')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <p class=" float-left"><i class="text-danger font-weight-bold">*</i> are
                                    required fields</p>
                                <button type="submit" class="btn btn-primary float-right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-12">
                    <form action="{{ route('department.internship.updatePre', $internship->id) }}" method="POST">
                        @csrf
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Internship Prerequistes</h3>
                            </div>
                            <div class="card-body">
                                <p>Add prerequisite question and/or requirments.
                                    <button id="inputRowAdder" type="button" class="btn btn-dark">
                                        <i class="fas fa-plus">
                                        </i> ADD
                                    </button>
                                </p>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6" id="inputDiv">
                                        @foreach ($internship->prerequisites as $prerequisites)
                                            <div class="input-group mt-2" id="inputGroupDiv{{ $prerequisites->id }}">
                                                <input type="text" value="{{ $prerequisites->pre_key }}"
                                                    name="prerequisite[{{ $loop->iteration }}][pre_key]"
                                                    class="form-control" required />
                                                <input type="hidden" name="prerequisite[{{ $loop->iteration }}][id]" value="{{ $prerequisites->id }}">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger" onclick="deleteOriginal({{ $loop->iteration }}, {{ $prerequisites->id }})">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <button type="submit" class="btn btn-primary float-right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <script>
        nextHandler = () => {
            stepper.next();
            $('.prevBtn').removeAttr('hidden');
            if (stepper._currentIndex == (stepper._steps.length - 1)) {
                $('.submitBtn').removeAttr('hidden');
                $('.nextBtn').attr('hidden', true);
            }
        }

        previousHandler = () => {
            stepper.previous();
            $('.nextBtn').removeAttr('hidden');
            $('.submitBtn').attr('hidden', true);
            if (stepper._currentIndex == 0) {
                $('.prevBtn').attr('hidden', true);
            }
        }
    </script>
@endsection
