@extends('layouts.app',[
'activePage' => 'manageProgramme',
])
@section('title','Manage Programme')
@section('content')

<!-- /.content-header -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Manage Programme</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Manage Programme</li>
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
                <h3 class="card-title">Title</h3>

                <div class="card-tools">
                  <a class="btn btn-info btn-sm" href="javascript:;" data-toggle="modal" data-target="#manageModal">Add new</a>
                </div>
              </div>
              <div class="card-body">
                <table class="table table-striped table-bordered table-hover datatable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($programmes as $key=>$programme)
                    <tr>
                      <td>{{ $key+1}}</td>
                      <td>{{ $programme['title']}}</td>
                      <td class="d-flex">
                        <a href="{{ route('admin.programme.edit', $programme->id) }}" class="btn btn-info">Edit</a>
                        <a href="{{ route('admin.programme.destroy', $programme->id) }}" class="btn btn-danger ml-2">Delete</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>

                  </tfoot>
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
  <!-- /.content-header -->

  <!-- Modal -->

  <div class="modal fade" id="manageModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add new Programme</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="manage_form">
            @csrf
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="">Enter title</label>
                  <input type="text" name="title" placeholder="Enter title" class="form-control" id="title">
                  <span class="title_error font-weight-bold invalid-feedback" style="display: block;" role="alert"></span>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Add</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>


    <script>
      const manageAction = "{{ route('admin.programme.store') }}";
      const method = 'POST';
    </script>
    @endsection