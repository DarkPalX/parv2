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
            <h4 class="mg-b-0 tx-spacing--1">Par v1 OPEN Transactions<br></h4>
        </div>
    </div>

    <div class="row filter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
            <div class="card">
                <div class="card-body">
                    <form autocomplete="off">
                        @csrf
                        <div class="form-group-inner">
                            <div class="row mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <select name="dept" id="dept" class="form-control">
                                        <option value="ALL"> - ALL dept -</option>
                                        @foreach($dept as $de)
                                            <option value="{{$de->dept}}" 
                                                @if(isset($_GET['dept']) && $_GET['dept'] == $de->dept) selected @endif
                                                >{{$de->dept}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Type Employee ID, Name or Transaction Code" value="@if(isset($_GET['search'])){{$_GET['search']}}@endif">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-4 mg-t-2">
                                            <button class="btn btn-sm btn-primary btn-uppercase w-100" type="submit">
                                                Generate
                                            </button>
                                        </div>

                                        @if(isset($qry))
                                            <div class="col-4 mg-t-2">
                                                <a class="btn btn-sm btn-success btn-uppercase text-white w-100"
                                                    href="{{ route('report.parv1_transactions_export', [
                                                            'dept' => request('dept','ALL'),
                                                            'search' => request('search','ALL')
                                                    ]) }}">
                                                        Export to Excel
                                                </a>

                                                {{-- <a class="btn btn-sm btn-success btn-uppercase text-white w-100"
                                                href="{{ route('report.parv1_transactions_export', ['dept' => request('dept','ALL'), 'search' => request('search','ALL')]) }}">
                                                    Export to Excel
                                                </a> --}}
                                            </div>
                                        @endif

                                        
                                        <div class="col-1 mg-t-2">
                                            <a class="btn btn-sm btn-uppercase text-primary w-100" href="{{ route('report.parv1_transactions') }}">
                                                <i class="fa fa-sync"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>


                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 mg-t-2">
                                            <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" type="submit">Generate</button>
                                        </div>
                                        @if(isset($qry))
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 mg-t-2">
                                                <a class="btn btn-sm pd-x-15 btn-success btn-uppercase mg-l-5 text-white" href="{{ route('report.parv1_transactions_export', ['dept' => request('dept','ALL'), 'search' => request('search','ALL')]) }}">Export to Excel</a>
                                            </div>
                                        @endif
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>          
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Emp ID</th>
                        <th>Accountable</th>
                        <th>Dept</th>
                        <th>PAR#</th>
                        <th>Date</th>
                        <th>Tracking #</th>
                        <th>Description</th>
                        <th>PAR Status</th>
                        <th>Item Status</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Cost</th>
                        <th>Added by</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($qry))
                    @forelse($qry as $d)
                        <tr class="tx-13">
                            <td>{{$d->empid}}</td>
                            <td>{{$d->fullname}}</td>
                            <td>{{$d->dept}}</td>
                            <td>{{$d->id}}</td>                    
                            <td>{{$d->documentDate}}</td>
                            <td>{{$d->item_tracking}}</td>
                            <td>{{$d->item_name}}</td>                    
                            <td>{{strtoupper($d->docStatus)}}</td>
                            <td>{{strtoupper($d->status)}}</td>
                            <td class="text-right">{{$d->item_qty}}</td>
                            <td class="text-right">{{$d->item_price}}</td>
                            <td>{{$d->addedBy}}</td>
                        </tr>
                    @empty
                    @endforelse
                    @endif
                </tbody>
            </table>
            @if(isset($qry)) {{ $qry->appends(request()->query())->links() }} @endif
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


@endsection
