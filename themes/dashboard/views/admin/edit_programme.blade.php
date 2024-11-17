@extends('layouts.app',[
'activePage' => 'manageProgramme',
])
@section('title','Edit Programme')
@section('content')

<!-- /.content-header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Programme</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Programme</li>
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

              <div class="card-body">
                <form id="manage_form">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="">Enter programme title</label>

                        <input type="text" value="{{ $programme->title }}" name="title" placeholder="Enter programme title" class="form-control" id="title">
                        <span class="title_error font-weight-bold invalid-feedback" style="display: block;" role="alert"></span>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <button class="btn btn-primary">Update</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- /.content-header -->
  <script>
    const manageAction = "{{ route('admin.programme.update', $programme) }}";
    const method = 'PUT';
  </script>
  @endsection