@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/css/dashforge.profile.css') }}">
@endsection

@section('content')
<div class="container">
<div class="row">
    <div class="mg-t-10">
        <form method="GET" class="card mg-b-10">
            <div class="card-body pd-15 d-flex align-items-end">
                <div class="mg-r-20">
                    <label for="sort" class="tx-12 tx-medium">Sort document date</label>
                    <select id="sort" name="sort" class="form-control">
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest to latest</option>
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest to oldest</option>
                    </select>
                </div>
                <div class="mg-r-20">
                    <label for="status" class="tx-12 tx-medium">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="open" {{ $statusFilter === 'open' ? 'selected' : '' }}>Hide closed (default)</option>
                        <option value="closed" {{ $statusFilter === 'closed' ? 'selected' : '' }}>Closed only</option>
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Show all</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
        </form>

        @php
            $grouped = $datas->groupBy('employee_id');
        @endphp

        @foreach($grouped as $d)
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h4 class="mg-b-5">{{ $d[0]['employee_id'] }} {{ $d[0]['emp_name']}}</h4>
                        <p>{{ $d[0]['dept'] }}</p>
                    </div>
                </div>
              
                <div class="table-responsive">
                    <table class="table table-dashboard mg-b-0">
                  
                        <thead>
                            <tr class="group">
                                <div class="col">
                                <th>Document Date</th>
                                  </div>
                                 <div class="col"> 
                                <th>Par ID</th>
                                  </div>
                                  <div class="col">
                                <th>Description</th>
                                    </div>
                                  <div class="col">  
                                <th class="serial">Serial #</th>
                                 </div>
                                 <div class="col">
                                <th>Item Status</th>
                                 </div>
                                  <div class="col">  
                                <th>Document Status</th>
                                    </div>
                                  <div class="col">  
                                <th>Qty</th>
                                    </div>
                                  <div class="col">  
                                <th class="text-right">Cost</th>
                                   </div>
                                  <div class="col">  
                                <th class="text-right">Total Cost</th>
                                   </div>
                            </tr>
                        </thead>
                        </div>
                        
                        <tbody>
                             @php
                                $total = 0;
                            @endphp
                            @foreach($d as $i)
                                @php $total += $i->qty * $i->cost @endphp
                            <tr>
                                <td class="tx-color-03 tx-normal">{{ $i->document_date }}</td>
                                <td class="tx-medium">{{ App\accountabilityHeaders::getNewRefCode($i->refcode) ?? $i->refcode }}</td>
                                {{-- <td class="tx-medium">{{ $i->refcode }}</td> --}}
                                <td class="tx-medium">{{ $i->description }}</td>
                                <td class="tx-color-03 tx-normal">{{ $i->serial_no }}</td>
                                <td class="tx-color-03 tx-normal">{{ $i->status }}</td>
                                <td class="tx-color-03 tx-normal">{{ strtoupper($i->doc_status) }}</td>
                                <td class="tx-color-03 tx-normal text-right">{{ $i->qty }}</td>
                                <td class="tx-color-03 tx-normal text-right">{{ $i->cost }}</td>
                                <td class="tx-color-03 tx-normal text-right">{{ number_format($i->qty * $i->cost, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="8" class="tx-medium">Grand Total</td>
                                <td class="tx-medium text-right">{{number_format($total,2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- table-responsive -->
            </div>
        @endforeach
    </div>
</div>
</div>

@endsection


