<form action="{{ route('department.profile.password', Auth::user()->id) }}" method="POST">
    @csrf
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
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password">Old Password <i class="text-danger font-weight-bold">*</i></label>
                <input type="password" class="form-control" id="password" name="old_password"
                    placeholder="Enter Old Password" required>
                @error('old_password')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password <i class="text-danger font-weight-bold">*</i></label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter New Password" required>
                @error('password')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password <i class="text-danger font-weight-bold">*</i></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm Password" required>
                @error('password_confirmation')
                    <span class="text-danger" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3"></div>

        <hr>
        <div class="col-md-12">
            <p class=" float-left"><i class="text-danger font-weight-bold">*</i> are
                required fields</p>
            <button type="submit" class="btn btn-primary float-right">Update</button>
        </div>
    </div>
</form>
