@extends('layouts.app')
@section('title', 'Shorlisted Applicants')
@section('content')

    <!-- /.content-header -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">View Shorlisted Students For {{ $selectedCourse?->course_name }},
                            {{ $selectedCourse?->centre?->title }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Shortlisted Students</li>
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
                                <div class="card-header row align-items-end">
                                    <span class="col-12 col-md-6">
                                        <p>Select Course</p>
                                        <select name="course_id" id="course_id" class="form-control">
                                            <option value="">Select Course</option>

                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"
                                                    @if ($course->id == $selectedCourse?->id) selected @endif>
                                                    {{ $course->location }} -
                                                    {{ $course->course_name }}</option>
                                            @endforeach
                                        </select>

                                    </span>
                                    <div class="col-12 col-md-6">
                                        <button class="btn btn-primary">Admit All</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Card Type</th>
                                            <th>Card Number</th>
                                            <th>Verification By (On)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student['name'] }}</td>
                                                <td>{{ $student['email'] }}</td>
                                                <td class="text-uppercase">{{ $student['card_type'] }}</td>
                                                <td>{{ $student['ghcard'] }}</td>
                                                <td>
                                                    @if ($student['verification_date'])
                                                        {{ $student['verified_by_name'] }}
                                                        ({{ $student['verification_date'] }})
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info">Action</button>
                                                        <button type="button"
                                                            class="btn btn-info dropdown-toggle dropdown-icon"
                                                            data-toggle="dropdown">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            <a class="dropdown-item" href="#">Action</a>
                                                            <a class="dropdown-item" href="#">Another action</a>
                                                            <a class="dropdown-item" href="#">Something else here</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="#">Separated link</a>
                                                        </div>
                                                    </div>



                                                    @if (!$student->ghcard)
                                                        <span class="badge badge-danger">Ask student to update
                                                            details</span>
                                                    @elseif (!$student->verification_date && !$student->verified_by)
                                                        <button type="button" onclick="verifyStudent(this)"
                                                            data-id="{{ $student->id }}" data-name="{{ $student->name }}"
                                                            class="btn btn-success btn-sm">Verify</button>
                                                        <a href="{{ route('admin.reset-verify', $student['id']) }}"
                                                            class="btn btn-danger btn-sm">Reset</a>
                                                    @else
                                                        <span class="badge badge-success">Verified</span>
                                                        <a href="{{ route('admin.reset-verify', $student['id']) }}"
                                                            class="btn btn-danger btn-sm">Reset</a>
                                                    @endif
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
@endsection
@push('scripts')
    <script>
        $('#course_id').on('change', function(e) {
            const course_id = $('#course_id').val()
            window.location.href = `{{ route('admin.shortlisted.get') }}?course_id=${course_id}`;
        })

        async function verifyStudent(ele) {
            try {
                const id = $(ele).attr('data-id');
                const name = $(ele).attr('data-name');
                Swal.fire({
                    title: 'Confirm ' + name,
                    text: `Are you sure you want to confirm identity of student: ${name}?`,
                    icon: 'info',
                    backdrop: `rgba(0,0,0,0.95)`,
                    confirmButtonText: 'Yes, Submit',
                    cancelButtonText: 'No, Cancel',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    preConfirm: async () => {
                        const id = $(ele).attr('data-id');
                        const name = $(ele).attr('data-name');

                        const url = `/admin/verify-student/${id }`;
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        });
                        const respData = await response.json();
                        if (respData.success) {
                            Swal.fire({
                                title: 'Success',
                                text: respData.message,
                                toast: true,
                                icon: 'info',
                                position: 'top-end'
                            });

                            const parent = $($(ele).parent('td'))
                            parent.html('<span class="badge badge-success">Verified</span>');
                            const tdBefore = $(parent.prev('td'));
                            tdBefore.html(`
                            ${respData.student.verified_by_name} (${respData.student.verification_date})
                            `)
                        } else {
                            return Swal.fire({
                                title: 'Error',
                                message: respData.message,
                                toast: true,
                                key: 'error'
                            })
                        }
                    }
                })


            } catch (error) {
                return Swal.fire({
                    title: 'Error',
                    message: 'Unable to verify student',
                    toast: true,
                    key: 'error'
                });
            }
        }
    </script>
@endpush
