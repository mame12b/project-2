<form method="POST" action="{{ route('admin.profile.setting') }}">
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
        </div>

        <hr>

        <div class="col-md-12">
            <p class=" float-left"><i class="text-danger font-weight-bold">*</i> are
                required fields</p>
            <button class="btn btn-primary float-right">Update</button>
        </div>
    </div>
</form>
