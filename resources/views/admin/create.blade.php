@extends('admin.home')

@section('name-page')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ml-2">
                <div class="col-sm-6">
                    <h4 class="text-semibold mb-2" style="color: black">Create User</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('container')
    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.store') }}" method="POST">
                                @csrf
                                <div class="form-selected mb-3">
                                    <div class="form-group">
                                        <label for="name">
                                            Name
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">
                                            Lastname
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                                        @error('lastname') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">
                                            Email
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        value="{{ old('email') }}" required>
                                        @error('email') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">
                                            Password
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                                        value="{{ old('password') }}" required>
                                        @error('password') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-1">Simpan</button>
                                    <a href="{{ route('admin.index') }}" class="btn btn-default mt-1">Batal</a>
                                </div>
                            </form>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection