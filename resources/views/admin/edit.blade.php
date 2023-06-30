@extends('admin.home')

@section('name-page')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row ml-2">
            <div class="col-sm-6">
                <h4 class="text-semibold mb-2" style="color: black">Edit User</h4>
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
                            <form action="{{ route('admin.update', $user) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-selected mb-3">
                                    <div class="form-group">
                                        <label for="name">
                                            Name
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" required>
                                        @error('name') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">
                                            Lastname
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ $user->lastname }}" required>
                                        @error('lastname') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">
                                            Email
                                            <span class="req" style="color: red">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        value="{{ $user->email }}" required>
                                        @error('email') <span class="text-danger">{{ 'This field is required.' }}</span> @enderror
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