<form method="POST" action="{{ route('user.profile.setting') }}">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-ban"></i>
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-check"></i>
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        @csrf
        <input type="hidden" value="{{ Auth::user()->id }}" name="user_id" />
        <div class="col-md-4">
            <div class="form-group">
                <label>First Name</label> <i class="text-danger font-weight-bold">*</i>
                <input id="first_name" placeholder="Enter First Name" type="text"
                    class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                    value="{{ (Auth::user()->information && Auth::user()->information->first_name)?Auth::user()->information->first_name:'' }}" required autocomplete="first_name">
                @error('first_name')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Student ID</label> <i class="text-danger font-weight-bold">*</i>
                <input id="student_id" placeholder="Enter ID" type="text"
                    class="form-control @error('student_id') is-invalid @enderror" name="student_id"
                    value="{{ (Auth::user()->information && Auth::user()->information->student_id)?Auth::user()->information->student_id:'' }}" required autocomplete="student_id">
                @error('student_id')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input id="phone_number" placeholder="Enter Phone" type="text"
                    class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                    value="{{ (Auth::user()->information && Auth::user()->information->phone_number)?Auth::user()->information->phone_number:'' }}" autocomplete="phone_number">
                @error('phone_number')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>School</label>
                <input id="degree" placeholder="Enter School" type="text"
                    class="form-control @error('degree') is-invalid @enderror" name="degree"
                    value="{{ (Auth::user()->information && Auth::user()->information->degree)?Auth::user()->information->degree:'' }}" autocomplete="degree">
                @error('degree')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Middle Name</label> <i class="text-danger font-weight-bold">*</i>
                <input id="middle_name" placeholder="Enter Middle Name" type="text"
                    class="form-control @error('middle_name') is-invalid @enderror" name="middle_name"
                    value="{{ (Auth::user()->information && Auth::user()->information->middle_name)?Auth::user()->information->middle_name:'' }}" required autocomplete="middle_name">
                @error('middle_name')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Comulative GPA</label> <i class="text-danger font-weight-bold">*</i>
                <input id="cgpa" placeholder="Enter CGPA" type="text"
                    class="form-control @error('cgpa') is-invalid @enderror" name="cgpa" value="{{ (Auth::user()->information && Auth::user()->information->cgpa)?Auth::user()->information->cgpa:'' }}" required
                    autocomplete="cgpa">
                @error('cgpa')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>City</label>
                <input id="city" placeholder="Enter City" type="text"
                    class="form-control @error('city') is-invalid @enderror" name="city" value="{{ (Auth::user()->information && Auth::user()->information->city)?Auth::user()->information->city:'' }}"
                    autocomplete="city">
                @error('city')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Department</label>
                <input id="department" placeholder="Enter Department" type="text"
                    class="form-control @error('department') is-invalid @enderror" name="department"
                    value="{{ (Auth::user()->information && Auth::user()->information->department)?Auth::user()->information->department:'' }}" autocomplete="department">
                @error('department')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Last Name</label>
                <input id="last_name" placeholder="Enter Last Name" type="text"
                    class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                    value="{{ (Auth::user()->information && Auth::user()->information->last_name)?Auth::user()->information->last_name:'' }}" autocomplete="last_name">
                @error('last_name')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>Year of study</label>
                <input id="year_of_study" placeholder="Enter Year" type="text"
                    class="form-control @error('year_of_study') is-invalid @enderror" name="year_of_study"
                    value="{{ (Auth::user()->information && Auth::user()->information->year_of_study)?Auth::user()->information->year_of_study:'' }}" autocomplete="year_of_study">
                @error('year_of_study')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label>University</label> <i class="text-danger font-weight-bold">*</i>
                <input id="university" placeholder="Enter University" type="text"
                    class="form-control @error('university') is-invalid @enderror" name="university"
                    value="{{ (Auth::user()->information && Auth::user()->information->university)?Auth::user()->information->university:'' }}" required autocomplete="university">
                @error('university')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label>About Me</label>
                <textarea id="about_me" type="text"
                    class="form-control @error('about_me') is-invalid @enderror" name="about_me" autocomplete="about_me" placeholder="Type semthing about you...">{{ (Auth::user()->information && Auth::user()->information->about_me)?Auth::user()->information->about_me:''}}</textarea>
            </div>
        </div>

        <hr>

        <div class="col-md-12">
            <p class=" float-left"><i class="text-danger font-weight-bold">*</i> are
                required fields</p>
            <button class="btn btn-primary float-right">Update</button>
        </div>
    </div>
</form>
