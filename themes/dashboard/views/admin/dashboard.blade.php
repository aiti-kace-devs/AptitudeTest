@extends('layouts.app',[
'activePage' => 'dashboard',
])
@section('title','Dashboard')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Admin</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <a href="{{ url('admin/manage_students') }}" style="text-decoration: none; color: inherit;">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $student}}</h3>

              <p>Total students</p>
            </div>
            {{-- <div class="icon">
              <i class="ion ion-person-add"></i>
            </div> --}}
            <i class="fas fa-user-graduate fa-3x" style="position: absolute; right: 20px; top: 20px; opacity: 0.3;"></i>
             </div>
        </div>
    </a>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <a href="{{ url('admin/manage_admins') }}" style="text-decoration: none; color: inherit;">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $admin}}</h3>

              <p>Total admins</p>
            </div>
            {{-- <div class="icon">
              <i class="ion ion-person-add"></i>

            </div> --}}
            <i class="fas fa-user-shield fa-3x" style="position: absolute; right: 20px; top: 20px; opacity: 0.3;"></i>
          </div>
        </div>
    </a>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <a href="{{ url('/admin/manage_exam') }}" style="text-decoration: none; color: inherit;">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $exam}}</h3>

              <p>Exams</p>
            </div>
            {{-- <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div> --}}

            <i class="fas fa-file-alt fa-3x" style="position: absolute; right: 20px; top: 20px; opacity: 0.3;"></i>
          </div>
        </div>
    </a>
        <!-- ./col -->
        <!-- ./col -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @endsection
