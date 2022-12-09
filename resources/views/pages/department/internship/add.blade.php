@extends('pages.department.inc.app')

@section('header')
    @include('layout.header', ['title' => 'Department | Internship | Add'])
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Internship</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Internship</li>
                        <li class="breadcrumb-item active">Add</li>
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
                    <form method="POST" action="{{ route('department.internship.store') }}">
                        @csrf
                        <input type="hidden" name="department_id" value="{{ (int) Auth::user()->department->id }}">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">Internship Information</h3>
                            </div>
                            <div class="card-body p-0">
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
                                <div class="bs-stepper linear">
                                    <div class="bs-stepper-header" role="tablist">
                                        <!-- your steps here -->
                                        <div class="step active" data-target="#first-step">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="first-step" id="first-step-trigger" aria-selected="true">
                                                <span class="bs-stepper-circle">1</span>
                                                <span class="bs-stepper-label">Basic Information</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#second-step">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="second-step" id="second-step-trigger" aria-selected="false"
                                                disabled="disabled">
                                                <span class="bs-stepper-circle">2</span>
                                                <span class="bs-stepper-label">Prerequisites</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content">
                                        <!-- your steps content here -->
                                        <div id="first-step" class="content active dstepper-block" role="tabpanel"
                                            aria-labelledby="first-step-trigger">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Title</label> <i class="text-danger font-weight-bold">*</i>
                                                        <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                                                    </div>
                                                    @error('title')
                                                        <span class="text-danger" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                    <div class="form-group">
                                                        <label>Minimum CGPA</label>
                                                        <input type="text" name="minimum_cgpa" class="form-control" placeholder="Enter CGPA">
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
                                                        <label>Start Date</label> <i class="text-danger font-weight-bold">*</i>
                                                        <div class="input-group date" id="reservationdatetime"
                                                            data-target-input="nearest">
                                                            <input type="text" name="start_date" class="form-control datetimepicker-input"
                                                                data-target="#reservationdatetime" placeholder="Start Date" required />
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
                                                        <label>End Date</label> <i class="text-danger font-weight-bold">*</i>
                                                        <div class="input-group date" id="reservationdatetime2"
                                                            data-target-input="nearest">
                                                            <input type="text" name="end_date" class="form-control datetimepicker-input"
                                                                data-target="#reservationdatetime2" placeholder="End Date" required />
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
                                                        <input type="number" name="quota" class="form-control" placeholder="Enter quota">
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
                                                            <input type="text" name="deadline" class="form-control datetimepicker-input"
                                                                data-target="#reservationdatetime3" placeholder="DeadLine" required/>
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
                                        <div id="second-step" class="content" role="tabpanel"
                                            aria-labelledby="second-step-trigger">
                                            <p>Add prerequisite question and/or requirments.
                                            <button id="inputRowAdder" type="button" class="btn btn-dark">
                                                <i class="fas fa-plus">
                                                </i> ADD
                                            </button>
                                        </p>
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6" id="inputDiv">
                                                    <div class="input-group" id="inputGroupDiv">
                                                        <input type="text" placeholder="Enter key" name="prerequisite[1][pre_key]" class="form-control" required/>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-danger" id="inputRowDelete">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <p class=" float-left"><i class="text-danger font-weight-bold">*</i> are
                                    required fields</p>
                                <button type="button" class="btn btn-primary float-right nextBtn" onclick="nextHandler()"> Next <i class="fas fa-arrow-right ml-1"></i> </button>
                                <button type="submit" hidden class="btn btn-success float-right ml-2 submitBtn">Submit</button>
                                <button type="button" class="btn btn-primary float-right prevBtn" hidden onclick="previousHandler()"> <i class="fas fa-arrow-left mr-1"></i>Previous</button>
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
            if(stepper._currentIndex == (stepper._steps.length - 1)) {
                $('.submitBtn').removeAttr('hidden');
                $('.nextBtn').attr('hidden', true);
            }
        }

        previousHandler = () => {
            stepper.previous();
            $('.nextBtn').removeAttr('hidden');
            $('.submitBtn').attr('hidden', true);
            if(stepper._currentIndex == 0) {
                $('.prevBtn').attr('hidden', true);
            }
        }


    </script>
@endsection
