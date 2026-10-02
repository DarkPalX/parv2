@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .content {
            overflow: hidden;
        }

        .content-body > .container {
            max-width: none;
            width: 100%;
            padding-left: 24px;
            padding-right: 24px;
        }

        .report-table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .report-table {
            width: 100%;
            min-width: 1650px;
            table-layout: auto;
        }

        .report-table th,
        .report-table td {
            padding: 8px 6px;
            font-size: 11px;
            line-height: 1.25;
            vertical-align: top;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .report-table .report-number,
        .report-table .report-aging {
            white-space: nowrap;
            text-align: right;
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
            <h4 class="mg-b-0 tx-spacing--1">Department Summary Report<br><small>Select option to check PAR</small></h4>
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
                                <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 d-flex flex-column flex-md-row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ">
                                        <label for="par-status" class="align-bottom mr-1">Select PAR to query: </label>
                                        <select name="name" id="name" class="form-control mr-3">
                                            <option value="ALL"> ALL PAR </option>
                                            <option value="DEPT" @if(isset($_GET['name']) && $_GET['name'] == 'DEPT') selected @endif> DEPARTMENT PAR </option>
                                            @foreach($personnel as $pe)
                                                <option value="{{$pe->FullName}}" 
                                                    @if(isset($_GET['name']) && $_GET['name'] == $pe->FullName) selected @endif
                                                    >{{$pe->FullName}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="item-status" class="align-middle mr-1">Status: </label>
                                        <select id="item-status" name="item_status" class="form-control mr-3">
                                            <option value="OPEN" @if(isset($_GET['item_status']) && $_GET['item_status'] == 'OPEN') selected @endif> OPEN </option>
                                            <option value="CLOSED" @if(isset($_GET['item_status']) && $_GET['item_status'] == 'CLOSED') selected @endif> CLOSED </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 d-flex flex-column justify-content-end">
                                    <div class="row justify-content-end w-100">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mg-t-2">
                                            <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" type="submit">Generate</button>
                                        </div>
                                        @if(false)
                                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 mg-t-2">
                                                <a class="btn btn-sm pd-x-15 btn-success btn-uppercase mg-l-5 text-white text-truncate d-block" href="{{route('report.export_individual', $_GET['name'])}}">Export to Excel</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>          
        </div>
        <div class="col-12">
            <div class="report-table-wrap">
            <table class="table table-hover report-table">
                <thead>
                    <tr>
                        <th>Dept</th>
                        <th>Accountable</th>
                        <th>Refcode</th>
                        <th>Date</th>
                        <th>Aging</th>
                        <th>Serial#</th>
                        <th>Ref Doc</th>
                        <th>Stock Code</th>
                        <th>Description</th>
                        <th>PAR Status</th>
                        <th>Item Status</th>
                        <th>Qty</th>
                        <th>Cost</th>
                        <th>50% Purchase Cost</th>
                        <th>Book Value</th>
                        <th>Chargeable Cost</th>
                        <th>Added by</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($qry))
                    @forelse($qry as $d)
                        @php
                            $financialValues = \App\parDetails::financialValues($d->cost, $d->qty, $d->created_at ?? $d->document_date);
                        @endphp
                        <tr class="tx-13">
                            <td>{{$d->dept}}</td>
                            <td>{{$d->accountable}}</td>
                            <td>{{$d->refcode}}</td>                    
                            <td>{{$d->document_date}}</td>
                            <td class="report-aging">{{$financialValues['elapsed_months']}} mos</td>
                            <td>{{$d->detail_serial_no}}</td>
                            <td>{{$d->doc_ref}}</td>
                            <td>{{$d->stock_code}}</td>
                            <td>{{$d->description}}</td>                    
                            <td>{{strtoupper($d->doc_status)}}</td>
                            <td>{{strtoupper($d->status)}}</td>
                            <td class="report-number">{{$d->qty}}</td>
                            <td class="report-number">{{$d->cost}}</td>
                            <td class="report-number">{{number_format($financialValues['purchase_cost_50'], 2)}}</td>
                            <td class="report-number">{{number_format($financialValues['book_value'], 2)}}</td>
                            <td class="report-number">{{number_format($financialValues['chargeable_cost'], 2)}}</td>
                            <td>{{$d->added_by}}</td>
                        </tr>
                    @empty
                    @endforelse
                    @endif
                </tbody>
            </table>
            </div>
            @if(isset($qry)) {{ $qry->links() }} @endif
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
