@extends('layouts.app')
@section('title', 'Manage Admins')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!-- <h1 class="m-0">Manage Admins</h1> -->
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Manage Admins</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <!-- <h3 class="card-title">Admin List</h3> -->
                                    <div class="card-tools">
                                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add New Admin
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <table class="table table-striped table-bordered table-hover datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($admins as $key => $admin)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $admin->name }}</td>
                                                    <td>{{ $admin->email }}</td>
                                                    <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.admins.delete', $admin->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this admin?');">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush