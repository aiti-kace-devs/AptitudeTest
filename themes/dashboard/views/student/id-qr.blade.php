@extends('layouts.student')
@section('title', 'Portal dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">My Details</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">My Details</li>
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
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @php
                    function detailsUpdated($user)
                    {
                        return $user->user_updated != $user->user_created && $user->ghcard;
                    }
                @endphp
                <!-- Small boxes (Stat box) -->
                <form action="{{ route('student.updateDetails') }}" method="POST" name="student-details">
                    @csrf
                    {{-- @method('PATCH') --}}
                    <div class="row g-3 flex mb-2 align-items-center">
                        <div class="col-12 mb-2">
                            <label class="form-label col-12">Fullname (as appears on your Ghana Card/ any National ID)
                            </label>
                            <input id="name" type="text" required value=" {{ $user->student_name }}" name="name"
                                class="form-control col-12" @if (detailsUpdated($user)) disabled @endif>
                        </div>
                        <div class="input-group col-12 mb-2">
                            <label class="form-label col-12">Ghana Card Number</label>
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">GHA-</span>
                            </div>
                            <input id="ghcard" type="text" required value=" {{ $user->ghcard }}" name="ghcard"
                                placeholder="123456789-1" @if (detailsUpdated($user)) disabled @endif
                                class="form-control  @error('ghcard') is-invalid @enderror col-12 mr-2">
                        </div>
                        @error('ghcard')
                            <div role="alert" class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="col-12">
                            @if (detailsUpdated($user))
                                <p class="text-sm text-danger">You have already updated your details</p>
                            @else
                                <button onclick="confirmUpdateDetails()" type="button"
                                    class="btn btn-primary">Update</button>
                                <p class="text-sm text-danger">You can only update your details once, make sure you verify
                                    all
                                    details before submitting</p>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="text-md">Location : {{ $user->location }} </div>
                <div class="text-md">Course : {{ $user->course_name }}</div>
                <div class="text-md">Session : {{ $user->selected_session }}</div>
                <div class="text-lg font-bold mt-2">Student ID for Attendance</div>
                <div id="qrcode"></div>
                <button type="button" class="btn btn-primary" onclick="downloadQRCode()">Download</button>
                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection


@push('scripts')
    <script src="{{ asset('assets/js/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/easy.qrcode.min.js') }}"></script>
    <script>
        const innerWidth = Math.floor(window.innerWidth * (7 / 9));
        const width = innerWidth > 400 ? 400 : innerWidth
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ Auth::user()->userId }}",
            width: width,
            height: width,
            colorDark: "blue",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
        });

        function downloadQRCode() {
            qrcode.download("StudentID-{{ Auth::user()->userId }}")
        }


        $("#ghcard").inputmask({
            mask: "555555555-5",
            definitions: {
                '5': {
                    validator: "[0-9]"
                },
            }
        });

        function confirmUpdateDetails() {
            Swal.fire({
                title: 'Confirm Submission',
                text: `Are you sure you want to submit this update. This cannot be undone. Make sure all details are correct`,
                icon: 'info',
                backdrop: `rgba(0,0,0,0.95)`,
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'No, Cancel',
                showCancelButton: true,
                allowOutsideClick: false,
                preConfirm: async () => {
                    $('[name="student-details"]').submit()
                }
            })
        }
    </script>
@endpush
