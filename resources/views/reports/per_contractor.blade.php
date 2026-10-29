@extends('layouts.app')

@section('pagecss')
<link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    .content-body > .container { max-width: none; width: 100%; padding-left: 24px; padding-right: 24px; }
    .contractor-report-wrap { width: 100%; overflow-x: auto; }
    .contractor-report { width: 100%; table-layout: fixed; }
    .contractor-report th, .contractor-report td { font-size: 11px; padding: 8px 6px; vertical-align: top; overflow-wrap: anywhere; }
    .contractor-report .number, .contractor-report .aging { white-space: nowrap; text-align: right; }
    .contractor-dept { color: #8a94a6; font-size: 85%; font-style: italic; }
    .contractor-filter-card { border: 1px solid #e3e8ef; box-shadow: 0 2px 8px rgba(28, 39, 60, .05); }
    .contractor-filter-card .card-body { padding: 20px; }
    .contractor-filter-card label { display: block; margin-bottom: 7px; color: #344054; font-weight: 600; }
    .contractor-filter-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .contractor-filter-actions .btn { min-height: 36px; }
    .contractor-filter-card .select2-container { width: 100% !important; }
    .contractor-filter-card .select2-container--default .select2-selection--multiple,
    .contractor-filter-card .select2-container--default .select2-selection--single {
        min-height: 38px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        background: #fff;
    }
    .contractor-filter-card .select2-container--default .select2-selection--multiple {
        padding: 3px 6px;
    }
    .contractor-filter-card .select2-container--default .select2-selection--multiple .select2-selection__choice {
        margin-top: 3px;
        margin-bottom: 3px;
        padding: 3px 7px;
        border: 0;
        border-radius: 3px;
        background: #eaf2ff;
        color: #24528a;
    }
    .contractor-filter-card .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        margin-right: 5px;
        color: #24528a;
    }
    .contractor-filter-card .select2-container--default.select2-container--focus .select2-selection--multiple,
    .contractor-filter-card .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #5b9bd5;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, .12);
    }
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
        <div class="card contractor-filter-card">
            <div class="card-body">
                <form method="GET">
                    <input type="hidden" name="generate" value="1">
                    <div class="form-row align-items-end">
                        <div class="col-md-4 mg-b-10 mg-md-b-0">
                            <label for="contractor_dept">Contractor</label>
                            @php
                                $selectedDepartments = request()->input('contractor_dept', []);
                                $selectedDepartments = is_array($selectedDepartments) ? $selectedDepartments : [$selectedDepartments];
                            @endphp
                            <select name="contractor_dept[]" id="contractor_dept" class="form-control" multiple>
                                @foreach($contractorDepartments as $department)
                                    <option value="{{ $department }}" @if(in_array($department, $selectedDepartments, true)) selected @endif>{{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mg-b-10 mg-md-b-0">
                            <label for="contractor">Contractor Employee</label>
                            @php
                                $selectedContractors = request()->input('contractor', []);
                                $selectedContractors = is_array($selectedContractors) ? $selectedContractors : [$selectedContractors];
                            @endphp
                            <select name="contractor[]" id="contractor" class="form-control" multiple>
                                @foreach($contractors as $contractor)
                                    @php
                                        // Keep the legacy value for filtering, while showing the requested name format.
                                        $contractorName = $contractor->contractor_fname.','.$contractor->contractor_mname.' '.$contractor->contractor_lname;
                                        $contractorDisplayName = $contractor->contractor_lname.', '.$contractor->contractor_fname.' '.$contractor->contractor_mname.' ('.$contractor->contractor_dept.')';
                                    @endphp
                                    <option value="{{ $contractorName }}" @if(in_array($contractorName, $selectedContractors)) selected @endif>{{ $contractorDisplayName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="contractor-filter-actions">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-filter mg-r-5"></i>Generate</button>
                                @if(isset($qry))
                                    <a class="btn btn-success text-white" href="{{ route('report.export_contractor', request()->query()) }}"><i class="fa fa-file-excel mg-r-5"></i>Export to Excel</a>
                                @endif
                            </div>
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
                <th>Contractor</th><th>Accountable</th><th>Department</th><th>Refcode</th><th>Date</th>
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
                        @else
                            {{ $d->emp_name }}
                        @endif
                    </td><td>{{ $d->accountable }}</td>
                    <td>{{ $contractor ? $contractor->contractor_dept : '' }}</td>
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
                <tr><td colspan="20" class="text-center">No open contractor PARs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $qry->appends(request()->query())->links() }}
@endif
@endsection

@section('pagejs')
<script src="{{ asset('assets/lib/select2/js/select2.min.js') }}"></script>
<script>
    $(function () {
        $('#contractor_dept').select2({
            width: '100%',
            placeholder: 'Select contractors',
            closeOnSelect: false
        });
        $('#contractor').select2({
            width: '100%',
            placeholder: 'Select contractor employees',
            closeOnSelect: false
        });
    });
</script>
@endsection
