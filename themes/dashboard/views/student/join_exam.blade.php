@extends('layouts.student')
@section('title', 'Exams')
@section('content')
    <style type="text/css">
        .question_options>li {
            list-style: none;
            height: 40px;
            line-height: 40px;
        }
    </style>
    <!-- /.content-header -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Exams</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Exam</li>
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
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h3 class="text-center">Time : {{ $exam->exam_duration }} min</h3>
                                        </div>
                                        <div class="col-sm-4">
                                            <h3><b>Time Left</b> : <span
                                                    id="timer">{{ $exam['exam_duration'] - $usedTime }}:00</span>
                                            </h3>
                                        </div>

                                        <div class="col-sm-4">
                                            <h3 class="text-right text-success"><b>Status</b> :Running</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                            <div class="card mt-4">

                                <div class="card-body">

                                    <form action="{{ url('student/submit_questions') }}" method="POST"
                                        name="examination_form">
                                        <input type="hidden" name="exam_id" value="{{ Request::segment(3) }}">
                                        {{ csrf_field() }}
                                        <div class="row">

                                            @foreach ($question as $key => $q)
                                                <div class="col-sm-12 mt-4">
                                                    <p>{{ $key + 1 }}. {{ $q->questions }}</p>
                                                    <?php
                                                    $options = json_decode(json_decode(json_encode($q->options)), true);
                                                    ?>
                                                    <input type="hidden" name="question{{ $key + 1 }}"
                                                        value="{{ $q['id'] }}">
                                                    <ul class="question_options">
                                                        <li><input type="radio" value="{{ $options['option1'] }}"
                                                                name="ans{{ $key + 1 }}"> {{ $options['option1'] }}
                                                        </li>
                                                        <li><input type="radio" value="{{ $options['option2'] }}"
                                                                name="ans{{ $key + 1 }}"> {{ $options['option2'] }}
                                                        </li>
                                                        <li><input type="radio" value="{{ $options['option3'] }}"
                                                                name="ans{{ $key + 1 }}"> {{ $options['option3'] }}
                                                        </li>
                                                        <li><input type="radio" value="{{ $options['option4'] }}"
                                                                name="ans{{ $key + 1 }}"> {{ $options['option4'] }}
                                                        </li>

                                                        <li style="display: none;"><input value="0" type="radio"
                                                                checked="checked" name="ans{{ $key + 1 }}">
                                                            {{ $options['option4'] }}</li>
                                                    </ul>
                                                </div>
                                            @endforeach



                                            <div class="col-sm-12">
                                                <input type="hidden" name="index" value="{{ $key + 1 }}">
                                                <button type="submit" class="btn btn-primary"
                                                    id="myCheck">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /.content-header -->

        <!-- Modal -->
    @endsection

    @push('scripts')
        <script>
            var warn = 0;

            const startTest = () => {
                const timer = document.getElementById('timer');
                $(timer).addClass('js-timeout');
                openFullscreen();
            }

            const showWarning = (e) => {

                if (warn === 4) {
                    Swal.fire({
                        title: 'Violation!',
                        text: `Test submitted due to repeated violations`,
                        icon: 'error',
                        backdrop: `rgba(0,0,0,0.95)`,
                        confirmButtonText: 'Okay',
                        allowOutsideClick: false,
                        position: 'center',
                        timer: 5000
                    });
                    const form = $('[name="examination_form"]');
                    setTimeout(() => {
                        form.submit();
                    }, 5000);
                } else {

                    Swal.fire({
                        title: 'Violation!',
                        text: `You are in violation of exam rules. Please DO NOT exit fullscreen or changed tabs.
                        Your test may be automatically submitted if you keep on violating the rules. Warning Count: ${warn}`,
                        icon: 'error',
                        backdrop: `rgba(0,0,0,0.95)`,
                        confirmButtonText: 'Okay',
                        preConfirm: () => {
                            openFullscreen();
                        },
                        allowOutsideClick: false
                    })
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }


            }

            const openFullscreen = () => {

                // const elem = document.documentElement;

                const elem = document.querySelector('div.content-wrapper > div > section.content');

                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) {
                    /* Safari */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    /* IE11 */
                    elem.msRequestFullscreen();
                }
                console.log('opened fs')
                console.log(elem)

            }

            // document
            const handleVisibilityChange = async (e) => {
                if (document.hidden) {
                    warn++;
                }
                showWarning(e);
            }

            const handleFullscreenChange = async (e) => {
                //    await checkWarningCount()
                if (!document.fullscreenElement) {
                    warn++;
                }
                showWarning(e);
            }



            document.addEventListener('visibilitychange', handleVisibilityChange)
            document.addEventListener('fullscreenchange', handleFullscreenChange)

            Swal.fire({
                title: 'Welcome',
                text: 'Do you want to continue',
                icon: 'info',
                confirmButtonText: 'START',
                backdrop: `rgba(0,0,0,0.8)`,
                allowOutsideClick: false,
                preConfirm: async () => {
                    try {
                        const url = `/student/start-exam/{{ $exam->id }}`;
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            body: JSON.stringify({
                                exam_id: '{{ $exam->id }}'
                            })
                        });
                        if (!response.ok) {
                            return Swal.showValidationMessage(`
                            ${JSON.stringify(await response.json())}
                            `);
                        }
                        startTest();
                        Swal.close();

                    } catch (error) {
                        Swal.showValidationMessage(`
                            Request failed: ${error}
                        `);
                    }
                },
            })
        </script>
    @endpush
