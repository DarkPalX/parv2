@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .content {
            overflow: hidden;
        }
        @media print{
            .filter { display: none; }
            .b-head { display: none; }
            .content-footer { display: none; }
            .btnCSV { display: none; }
            .dept_header { display: none; }
            .p_header { display: block !important; }
            .btnPrint { display: none; }
        }

        .datepickerlabel {
            position: absolute; 
            z-index: 1;

            margin: 0px; 
            padding: 0px 15px;
            
            left: 5px; 
            bottom: 100%; 

            color: #596882;
        }
        
        .docdate {
            color: #c0ccda;
        }
    </style>
@endsection

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div class="b-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Par Summary Report</h4>
        </div>
    </div>

    <div class="row filter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
            <div class="card">
                <div class="card-body">
                    <form autocomplete="off" id="par_individual_form">
                        @csrf
                        <div class="form-group-inner">

                            <div class="row pt-3">

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 d-flex align-items-center" id="personal">
                                    <input type="search" name="emp" id="employees" class="form-control emp" placeholder="Search employee lastname" value="{{ auth()->user()->fullName }}" hidden/>
                                    <h5 class="m-0">{{ auth()->user()->fullName }}</h5>
                                </div>

                                <!-- Testing adding "serial no to filter" -->
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="personal">
                                    <input type="search" name="serial_no" id="serial_no" class="form-control dept" placeholder="Enter serial no. to search">
                                    <span><img style="display: none;" id="serial_no_spinner" class="wd-15p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                                    <div id="serial_no_list"></div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="datepickerlabel">Date From </label>
                                    <input type="date" name="date_from" placeholder="From" class="form-control docdate"> <!-- id="docdatefrom" -->
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label class="datepickerlabel"> Date To </label>
                                    <input type="date" name="date_to" placeholder="To" class="form-control docdate"> <!-- id="docdateto"  -->
                                </div> 
                                
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 mg-t-2">
                                    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 w-100" type="submit">Generate</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>          
        </div>
    </div>

    <center><img id="loader" src="{{ asset('assets/img/spinner/spinner10.gif') }}"  style="display:none;height:100px;"></center>
        
    <div style="display: none;" class="row mg-t-10 p_header">
        <div class="col-md-12">
            <div class="d-flex flex-row justify-content-start bg-gray-200 mg-b-10">
                <div class="pd-10"><img style="height: 80px;" src="{{ asset('images/logo_default.jpg') }}" alt=""></div>
                <div class="pd-10 mg-t-20"><h2>Philsaga Mining Corporation</h2><span>Purok 1-A Bayugan, Rosario, Agusan Del Sur</span></div>
                <div class="pd-10"></div>
            </div>
            <div class="d-flex justify-content-end">{{ Carbon\Carbon::now()->format('F d, Y') }}</div>
        </div>
    </div>

    <div class="row">
        <div id="department_tbl"> </div>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('assets/lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('scripts/report.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $('#par_individual_form').submit(function(e){
                e.preventDefault();
                $('#loader').show();

                $.ajax({
                    type: "GET",
                    url: "/parv2/public/ajax/par_individual_report",
                    data: $('#par_individual_form').serialize(),
                    success: function( response ) {
                        $('#loader').hide();
                        $('#department_tbl').html(response);     
                    }
                });
            });


            var typingTimer;
            $('#employees').keydown(function(){
                $('#emp_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingEmployee, 1500);
            });

            function doneTypingEmployee(){
                var query = {
                    "employee": $('#employees').val(),
                    "serial_no": $('#serial_no').val()
                };
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('accountable.fetch') }}",
                    method: "POST",
                    data: { query :query, _token:_token },
                    success: function(data)
                    {
                        $('#emp_spinner').hide();
                        $('#employee_list').fadeIn();
                        $('#employee_list').html(data);
                    }
                })
            }

            $('#serial_no').keydown(function(){
                $('#serial_no_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingSerialNo, 1500);
            });

            function doneTypingSerialNo(){
                var query = {
                    "employee": $('#employees').val(),
                    "serial_no": $('#serial_no').val()
                };
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('serial_no.fetch') }}",
                    method: "POST",
                    data: { query :query, _token:_token },
                    success: function(data)
                    {
                        $('#serial_no_spinner').hide();
                        $('#serial_no_list').fadeIn();
                        $('#serial_no_list').html(data);
                    }
                })
            }

            $('#department').keydown(function(){
                $('#dept_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingDepartment, 1500);
            });

            function doneTypingDepartment(){
                var query = {
                    "department": $('#department').val(),
                    "serial_no": $('#serial_no').val()
                };
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('department.fetch') }}",
                    method: "POST",
                    data: { query :query, _token:_token },
                    success: function(data)
                    {
                        $('#dept_spinner').hide();
                        $('#department_list').fadeIn();
                        $('#department_list').html(data);
                    }
                })
            }

            $('.docdate').on('change', function () {
                $(this).css('color', 'black');
            });
        });

    </script>
@endsection
