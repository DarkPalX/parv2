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
            <h4 class="mg-b-0 tx-spacing--1">All PAR Transaction</h4>
        </div>
    </div>

    <div class="row filter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
            <div class="card">
                <div class="card-body">
                     <a href="#" onclick="download_all()" class="btn btn-primary">Download All Transactions</a><br><br>
                     <div class="alert alert-success" role="alert"  style="display:none;>
                         <span style="color:green;" id="success_remark">Success. Please come back after 1-2 hours for the generated file.</span>
                    </div>
                     <table class="table">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @php
                                    foreach (glob("download/*.xlsx") as $filename) {
                                        echo "<tr><td>".str_replace('download/','',$filename).".xlsx</td>
                                            <td align='center'><a class='btn btn-info btn-sm' href='../".$filename."'><i class='fa fa-arrow-down'></i></a></td>
                                        </tr>";
                                    }
                                 @endphp
                            </tbody>
                     </table>
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
        function download_all(){
            setTimeout(
              function() 
              {
                $('#success_remark').show();
              }, 2000);
        }
    </script>
@endsection
