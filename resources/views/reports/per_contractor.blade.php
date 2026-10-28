@extends('layouts.app')

@section('pagecss')
<style>
    .content-body > .container { max-width: none; width: 100%; padding-left: 24px; padding-right: 24px; }
    .contractor-report-wrap { width: 100%; overflow-x: auto; }
    .contractor-report { width: 100%; table-layout: fixed; }
    .contractor-report th, .contractor-report td { font-size: 11px; padding: 8px 6px; vertical-align: top; overflow-wrap: anywhere; }
    .contractor-report .number, .contractor-report .aging { white-space: nowrap; text-align: right; }
    .contractor-dept { color: #8a94a6; font-size: 85%; font-style: italic; }
    @media print { .filter, .btnPrint, .btnCSV, .content-footer { display: none; } }
</style>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">PAR per Contractor<br><small>Open PAR for each contractor</small></h4>
    </div>
</div>

<div class="row filter">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET">
                    <div class="form-row align-items-end">
                        <div class="col-md-5">
                            <label for="contractor">Contractor</label>
                            <select name="contractor" id="contractor" class="form-control">
                                <option value="ALL">- ALL contractors -</option>
                                @foreach($contractors as $contractor)
                                    @php
                                        // Keep the legacy value for filtering, while showing the requested name format.
                                        $contractorName = $contractor->contractor_fname.','.$contractor->contractor_mname.' '.$contractor->contractor_lname;
                                        $contractorDisplayName = $contractor->contractor_lname.', '.$contractor->contractor_fname.' '.$contractor->contractor_mname.' ('.$contractor->contractor_dept.')';
                                    @endphp
                                    <option value="{{ $contractorName }}" @if(request('contractor') === $contractorName) selected @endif>{{ $contractorDisplayName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mg-t-10">
                            <button class="btn btn-sm btn-primary" type="submit">Generate</button>
                            @if(isset($qry))
                                <a class="btn btn-sm btn-success text-white" href="{{ route('report.export_contractor', request()->query()) }}">Export to Excel</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($qry))
<div class="contractor-report-wrap">
    <table class="table table-hover contractor-report">
        <thead>
            <tr>
                <th>Contractor</th><th>Accountable</th><th>Refcode</th><th>Date</th>
                <th>Issuance Date</th><th>Aging</th><th>Serial #</th><th>Ref Doc</th>
                <th>Stock Code</th><th>Description</th><th>Expense Type</th>
                <th>PAR Status</th><th>Item Status</th><th>Qty</th><th>Cost</th>
                <th>50% Purchase Cost</th><th>Book Value</th><th>Chargeable Cost</th><th>Added by</th>
            </tr>
        </thead>
        <tbody>
            @forelse($qry as $d)
                @php($financialValues = \App\parDetails::financialValues($d->cost, $d->qty, \App\parDetails::agingDate($d)))
                @php($contractor = $contractors->first(function ($candidate) use ($d) {
                    return $candidate->contractor_fname.','.$candidate->contractor_mname.' '.$candidate->contractor_lname === $d->emp_name;
                }))
                <tr>
                    <td>
                        @if($contractor)
                            {{ $contractor->contractor_lname }}, {{ $contractor->contractor_fname }} {{ $contractor->contractor_mname }}
                            <small class="contractor-dept">({{ $contractor->contractor_dept }})</small>
                        @else
                            {{ $d->emp_name }}
                        @endif
                    </td><td>{{ $d->accountable }}</td>
                    <td>{{ $d->refcode }}</td><td>{{ $d->document_date }}</td>
                    <td>{{ $d->issuance_date ?? $d->document_date }}</td>
                    <td class="aging">{{ $financialValues['aging'] }}</td>
                    <td>{{ $d->detail_serial_no }}</td><td>{{ $d->doc_ref }}</td>
                    <td>{{ $d->stock_code }}</td><td>{{ $d->description }}</td>
                    <td>{{ $d->expense_type ?? '' }}</td><td>{{ strtoupper($d->doc_status) }}</td>
                    <td>{{ strtoupper($d->status) }}</td><td class="number">{{ $d->qty }}</td>
                    <td class="number">{{ $d->cost }}</td>
                    <td class="number">{{ number_format($financialValues['purchase_cost_50'], 2) }}</td>
                    <td class="number">{{ number_format($financialValues['book_value'], 2) }}</td>
                    <td class="number">{{ number_format($financialValues['chargeable_cost'], 2) }}</td>
                    <td>{{ $d->added_by }}</td>
                </tr>
            @empty
                <tr><td colspan="19" class="text-center">No open contractor PARs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $qry->appends(request()->query())->links() }}
@endif
@endsection
