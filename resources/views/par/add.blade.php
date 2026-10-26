@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        @media (min-width: 992px) {
            .content-body > .container {
                max-width: none;
                width: 100%;
                padding-left: 30px;
                padding-right: 30px;
            }
        }

        .par-item-table { table-layout: fixed; min-width: 900px; }
        .par-item-table th, .par-item-table td { padding: .35rem .25rem; vertical-align: middle; }
        .par-item-table .par-id-cell { width: 5%; }
        .par-item-table .par-stock-cell { width: 7%; }
        .par-item-table .par-description-cell { width: 28%; }
        .par-item-table .par-serial-cell { width: 13%; }
        .par-item-table .par-qty-cell { width: 5%; }
        .par-item-table .par-uom-cell { width: 5%; }
        .par-item-table .par-cost-cell { width: 9%; }
        .par-item-table .book-value-column, .par-item-table .book-value-cell { width: 10%; }
        .par-item-table .aging-column, .par-item-table .par-aging-cell { width: 15%; }
        .par-item-table .par-action-cell { width: 3%; }
        .par-item-table input { width: 100%; min-width: 0; }
        .par-search-table { table-layout: fixed; min-width: 900px; }
        .par-search-table td:nth-child(1) { width: 5%; }
        .par-search-table td:nth-child(2) { width: 7%; }
        .par-search-table td:nth-child(3) { width: 28%; }
        .par-search-table td:nth-child(4) { width: 13%; }
        .par-search-table td:nth-child(5) { width: 5%; }
        .par-search-table td:nth-child(6) { width: 5%; }
        .par-search-table td:nth-child(7) { width: 9%; }
        .par-search-table td:nth-child(8) { width: 10%; }
        .par-search-table td:nth-child(9) { width: 15%; }
        .par-search-table td:nth-child(10) { width: 3%; }
        .par-aging-cell, .aging-search-cell { white-space: normal; line-height: 1.2; font-size: .8rem; }
        .par-aging-created { display: block; margin-top: 2px; color: #6c757d; font-size: .7rem; }
    </style>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mg-b-10 mg-lg-b-25 mg-xl-b-10">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Par Management</li>
            </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">PAR Issuance Form</h4>
    </div>
</div>

<form autocomplete="off" role="form" action="/par/store" method="POST" id="selectForm2" class="parsley-style-1" data-parsley-validate novalidate>
    @csrf
    <div class="row row-xs">
        <div class="col-lg-12 mg-t-10">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" id="total_items" name="total_items" value="0">
                    <div class="divider-text bg-gray-400 text-white">PAR Details</div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Type <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="par_type" id="par_type">
                                <option value="">Select One</option>
                                <option selected value="new">New</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        
                        <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">Doc Date <i class="tx-danger">*</i></label>
                        <div class="col-sm-3">
                            <input required type="text" id="datepicker4" name="doc_date" value="{{ \Carbon\Carbon::today()->format('m/d/Y') }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" id="issuance-date-row">
                        <label for="issuance_date" class="col-sm-2 col-form-label">Issuance Date <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <input required type="text" id="issuance_date" name="issuance_date" value="{{ old('issuance_date', \Carbon\Carbon::today()->format('m/d/Y')) }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">                            
                        <label class="col-sm-2 col-form-label">Location <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="location" id="location">
                                <option value="">Select One</option>
                                <option value="davao">Davao</option>
                                <option value="agusan">Agusan</option>
                            </select>
                        </div>

                        <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">PO # <i class="tx-danger">*</i></label>
                        <div class="col-sm-3">
                            <input required type="text" id="po_no" name="po_no" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">                            
                        <label class="col-sm-2 col-form-label">Site <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="site" id="site">
                                <option value="">Select One</option>
                                <option value="mcd mine">MCD Mine</option>
                                <option value="mcd main">MCD Main</option>
                            </select>
                        </div>

                        <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">CIS/S.I # <i class="tx-danger">*</i></label>
                        <div class="col-sm-3">
                            <input required type="text" id="cis_si_no" name="cis_si_no" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">                            
                        <label class="col-sm-2 col-form-label">Serial # <i class="tx-danger"></i></label>
                        <div class="col-sm-4">
                            <input type="text" id="header_serial_no" name="header_serial_no" class="form-control">
                        </div>
                    </div>

                    <div class="divider-text bg-gray-400 text-white">End-User Information</div>
                    <div class="form-group row">                            
                        <label class="col-sm-2 col-form-label">End-User Type <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="e_type" id="e_type">
                                <option value="">Select One</option>
                                <option value="1">Personal</option>
                                <option value="2">Common</option>
                                <option value="3">Contractor</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="empdiv">                            
                        <label class="col-sm-2 col-form-label">Personal <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <input required type="search" name="emp" id="employees" class="form-control" placeholder="Search employee id, employee lastname....">
                            <span><img style="display: none;" id="emp_spinner" class="wd-15p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                            <div id="employee_list"></div>
                        </div>
                    </div>
                    
                    <div class="form-group row" id="contractordiv" style="display: none;">                            
                        <label class="col-sm-2 col-form-label">Contractor <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <input required type="search" name="cont" id="contractor" class="form-control" placeholder="Search contractor's ID or Lastname....">
                            <span><img style="display: none;" id="cont_spinner" class="wd-15p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                            <div id="contractor_list"></div>
                        </div>
                    </div>

                    <input type="hidden" id="dept" name="emp_dept">

                    <div class="form-group row" id="deptdiv" style="display: none;">                            
                        <label class="col-sm-2 col-form-label">Common <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <input type="search" name="dept" id="department" class="form-control" placeholder="Enter department name to search">
                            <span><img style="display: none;" id="dept_spinner" class="wd-15p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                            <div id="department_list"></div>
                        </div>
                    </div>

                    <div class="divider-text bg-gray-400 text-white">Item Details</div>
                    <div class="form-row mg-b-10">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                 <table class="table table-sm mg-b-0 par-item-table">
                                    <thead class="thead-secondary">
                                        <tr>
                                          <th class="par-id-cell">ID</th>
                                          <th class="par-stock-cell">Stock Code</th>
                                          <th class="par-description-cell">Description</th>
                                          <th class="par-serial-cell">Serial #</th>
                                          <th class="par-qty-cell">Qty</th>
                                          <th class="par-uom-cell">UoM</th>
                                          <th class="par-cost-cell">Cost</th>
                                          <th class="wd-10p book-value-column" style="display:none;">Book Value</th>
                                           <th class="aging-column" style="display:none;">Aging</th>
                                           <th class="par-action-cell"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="addedItems">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                    <div class="form-row">
                        <div class="col-md-12">
                            <input type="search" name="search" id="search" class="form-control" placeholder="Search item via serial number or item description...">
                            <span><img style="display: none;" id="item_spinner" class="wd-10p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                            <div id="items_tbl"></div>
                        </div>
                    </div>


                    <div class="divider-text bg-gray-400 text-white">Reference</div>
                    <div class="form-row mg-b-20">
                        <div class="col-md-4">
                            <label for="department">Issuance Doc Reference(Batch#/MWIS)</label>
                            <input type="text" name="doc_ref" class="form-control">
                        </div>
                    </div> 

                    <div class="form-row">
                        <div class="col-md-12 mg-t-30 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Submit </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('pagejs')
    <script src="{{ asset('scripts/par.js') }}?v=20261026"></script>
    <script src="{{ asset('assets/lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/lib/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/lib/select2/js/select2.min.js') }}"></script>
    <script>
        $(function(){
            'use strict'
            $('.select2-no-search').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose One'
            });

            $('#datepicker4').datepicker();
            $('#issuance_date').datepicker();

            function toggleIssuanceDate() {
                var isFirstUser = $('#par_type').val() === 'new';
                $('#issuance-date-row').toggle(isFirstUser);
                $('#issuance_date').prop('required', isFirstUser);
            }

            $('#par_type').on('change', toggleIssuanceDate);
            toggleIssuanceDate();

            function toggleBookValue() {
                var isTransfer = $('#par_type').val() === 'transfer';
                $('.book-value-column, .book-value-cell, .aging-column, .aging-cell').toggle(isTransfer);
                $('input[name="book_value[]"]').prop('required', isTransfer);
            }

            $('#par_type').on('change', toggleBookValue);
            toggleBookValue();

            window.calculateBookValue = function(cost, purchaseDate) {
                var startingValue = parseFloat(cost) || 0;
                var purchase = window.parseDepreciationDate(purchaseDate);

                if (!purchase) {
                    return startingValue;
                }

                var elapsedDays = window.calculateElapsedDays(purchaseDate);
                var annualDepreciation = startingValue / 5;

                return Math.max(0, startingValue - ((elapsedDays / 365) * annualDepreciation));
            };

            window.calculateItemAging = function(purchaseDate) {
                var purchase = purchaseDate ? new Date(String(purchaseDate).replace(' ', 'T')) : new Date();
                var today = new Date();
                var months = window.calculateElapsedMonths(purchaseDate, today);

                return { months: months, years: Math.floor(months / 12), remainingMonths: months % 12 };
            };

            window.toggleTransferSearchFields = function() {
                var isTransfer = $('#par_type').val() === 'transfer';
                $('#items_tbl tr[id^="id"]').each(function() {
                    var $row = $(this);
                    var $agingCell = $row.children('td').eq(8);
                    var purchaseDate = $row.children('td').eq(6).data('purchase-date');
                    var aging = window.calculateItemAging(purchaseDate);

                    if (isTransfer) {
                        $agingCell.html(window.formatItemAgingHtml(aging, $row.children('td').eq(6).data('purchase-date')));
                    } else {
                        $agingCell.empty();
                    }
                });
            };

            toggleTransferSearchFields();
        });
    </script>

    <script>
        $('#location').change(function(){
            var value = $(this).val();
            if(value == 'davao'){
                $('#site').attr("disabled", true); 
                $('#site').removeAttr('required');
            } else {
                $('#site').attr("disabled", false);  
            }   
        });

        $(document).ready(function(){

            $('form').submit(function(){
                if($('#total_items').val() == 0){
                    alert('Please add items to par before submitting the transaction!');
                    return false;
                } else {
                    $(this).find('button[type=submit]').prop('disabled', true);
                }
                
            });

            $.ajaxSetup({ 
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                } 
            });

            var typingTimer;
            $('#employees').keydown(function(){
                $('#emp_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingEmployee, 2000);
            });

            function doneTypingEmployee(){
                var query = $('#employees').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('employee.fetch.add-par') }}",
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


            $('#department').keydown(function(){
                $('#dept_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingDepartment, 2000);
            });

            function doneTypingDepartment(){
                var query = $('#department').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('api.dept') }}",
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

            $('#contractor').keydown(function(){
                $('#cont_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingContractor, 2000);
            });

            function doneTypingContractor(){
                var query = $('#contractor').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('api.contractor') }}",
                    method: "POST",
                    data: { query :query, _token:_token },
                    success: function(data)
                    {
                        $('#cont_spinner').hide();
                        $('#contractor_list').fadeIn();
                        $('#contractor_list').html(data);
                    }
                })
            }

            $('#search').keydown(function(){
                $('#item_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingItem, 2000);
            });

            function doneTypingItem(){
                var search = $('#search').val();
                var p_type = $('#par_type').val();
                if(search == ""){
                    $('#item_spinner').hide();
                    $("#items_tbl").html('');
                } else {
                    $.ajax({
                        url : "/search/items",
                        method : "GET",
                        data : { 
                            search:search,
                            ptype:p_type
                        },
                        success : function(data){
                            $('#item_spinner').hide();
                            console.log(data);
                            $('#items_tbl').empty().html(data);
                            toggleTransferSearchFields();
                        }

                    })
                }
            }
        }); 

        $(document).on('click','.emp_li',function(){
            var emp = $(this).text();
            var i = emp.split("=");
            console.log(i);
            $('#employees').val(i[0]);
            $('#dept').val(i[1]);

            $('#employees').prop('readonly', true);
            $('#employee_list').fadeOut();
        });  

        $(document).on('click','.dept_li',function(){
            $('#department').val($(this).text());
            $('#department').prop('readonly', true);
            $('#department_list').fadeOut();
        });

        $(document).on('click','.cont_li',function(){
            $('#contractor').val($(this).text());
            $('#contractor').prop('readonly', true);
            $('#contractor_list').fadeOut();
        });

        $('#par_type').on('change', function(){
            $('#search').val('');
            toggleTransferSearchFields();
        });

    </script>
@endsection
