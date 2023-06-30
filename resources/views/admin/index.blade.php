@extends('admin.home')

@section('css')
    <!-- CSS Alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('name-page')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row ml-2">
            <div class="col-sm-6 mb-1">
                <h4 class="text-semibold" style="color: black">Daftar User</h4>
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
                            <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Opsi</th>
                                    </tr>
                                </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $user->name }} {{ $user->lastname }}</td>
                                        <td>{{ $user->email }}</td> 
                                        <td>
                                            <a href="{{ route('admin.edit', $user->id) }}" class="btn btn-info">Update</a>
                                            <a href="#" class="btn btn-danger delete" data-method="DELETE" data-id="{{ $user->id }}">Delete</a>
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
    </div>
@endsection

@section('script')
    @if (Session::has('success'))
        <script>
            toastr.success('{!! Session::get('success') !!}');
        </script>
    @endif

    <script>
        $('.delete').click(function () {

        var deleteid = $(this).attr('data-id');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Setelah dihapus, Anda tidak dapat memulihkan data ini lagi!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            window.location = "/admin/delete/"+deleteid+""
            swal("Data berhasil dihapus!", {
                icon: "success",
            });
            } else {
            swal("Data tidak jadi dihapus!");
            }
        });
        });
    </script>
@endsection