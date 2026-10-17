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
    .par-item-table .transfer-value-column, .par-item-table .transfer-value-cell { width: 10%; }
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

    <form autocomplete="off" role="form" action="/par/update" method="POST" id="selectForm2" class="parsley-style-1" data-parsley-validate novalidate>
        @csrf
        <div class="row row-xs">
            <div class="col-lg-12 mg-t-10">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" id="total_items" name="total_items" value="{{$count_items}}">
                        <div class="divider-text bg-gray-400 text-white">PAR Details</div>

                        <div class="form-group row">
                            <input type="hidden" id="par_type" name="par_type" value="{{ $par->ptype }}">
                            <input type="hidden" name="hid" value="{{ $par->id }}">
                            <label class="col-sm-2 col-form-label">Location <i class="tx-danger">*</i></label>
                            <div class="col-sm-4">
                                <select required class="form-control select2-no-search" name="location">
                                    <option value="">Select One</option>
                                    <option @if($par->p_location == 'davao') selected @endif value="davao">Davao</option>
                                    <option @if($par->p_location == 'agusan') selected @endif value="agusan">Agusan</option>
                                </select>
                            </div>
                            
                            <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">Doc Date <i class="tx-danger">*</i></label>
                            <div class="col-sm-3">
                                <input required type="text" id="datepicker4" name="doc_date" value="{{ date('m/d/Y', strtotime($par->document_date)) }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row" id="issuance-date-row">
                            <label for="issuance_date" class="col-sm-2 col-form-label">Issuance Date <i class="tx-danger">*</i></label>
                            <div class="col-sm-4">
                                <input type="text" id="issuance_date" name="issuance_date" value="{{ $par->issuance_date ? date('m/d/Y', strtotime($par->issuance_date)) : date('m/d/Y', strtotime($par->document_date)) }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">                            
                            <label class="col-sm-2 col-form-label">Site <i class="tx-danger">*</i></label>
                            <div class="col-sm-4">
                                <select class="form-control select2-no-search" name="site" @if($par->p_location=='davao') disabled @else required @endif>
                                    <option value="">Select One</option>
                                    <option @if($par->p_site == 'mcd mine') selected @endif value="mcd mine">MCD Mine</option>
                                    <option @if($par->p_site == 'mcd main') selected @endif value="mcd main">MCD Main</option>
                                </select>
                            </div>

                            <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">PO # <i class="tx-danger">*</i></label>
                            <div class="col-sm-3">
                                <input required type="text" id="po_no" name="po_no" value="{{ $par->po_no }}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">                            
                            <label class="col-sm-2 col-form-label">Serial #</label>
                            <div class="col-sm-4">
                                <input type="text" name="serial_no" id="serial_no" value="{{ $par->serial_no }}" class="form-control">
                            </div>

                            <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">CIS/S.I # <i class="tx-danger">*</i></label>
                            <div class="col-sm-3">
                                <input required type="text" id="cis_si_no" name="cis_si_no" value="{{ $par->cis_si_no }}" class="form-control">
                            </div>
                        </div>

                        <div class="divider-text bg-gray-400 text-white">End-User Information</div>
                        <div class="form-group row">                            
                            <label class="col-sm-2 col-form-label">End-User Type <i class="tx-danger">*</i></label>
                            <div class="col-sm-4">
                                <select required class="form-control select2-no-search" name="e_type" id="e_type">
                                    <option value="">Select One</option>
                                    <option @if($par->is_dept == 0) selected @endif value="1">Personal</option>
                                    <option @if($par->is_dept == 1) selected @endif value="2">Common</option>
                                </select>
                            </div>
                        </div>
                        

                        <div @if($par->is_dept == 1) style="display:none;" @endif class="form-group row" id="empdiv">                            
                            <label class="col-sm-2 col-form-label">Personal <i class="tx-danger">*</i></label>
                            <div class="col-sm-4">
                                <input type="search" name="emp" id="employees" class="form-control emp" value="@if($par->is_dept == 0) {{ $par->employee_id }} - {{ $par->emp_name }} @endif">
                                <span><img style="display: none;" id="emp_spinner" class="wd-15p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                                <div id="employee_list"></div>
                            </div>
                        </div>

                        <input type="hidden" id="dept" name="emp_dept" value="{{$par->dept}}">

                        <div @if($par->is_dept == 0) style="display:none;" @endif class="form-group row" id="deptdiv">                            
                            <label class="col-sm-2 col-form-label">Common <i class="tx-danger">*</i></label>
                            <div class="col-sm-4">
                                <input type="search" name="dept" id="department" class="form-control dept" value="@if($par->is_dept == 1) {{ $par->dept_id }} @endif">
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
                                              <th class="transfer-value-column" @if($par->ptype != 'transfer') style="display:none;" @endif>Transfer Value</th>
                                              <th class="aging-column" @if($par->ptype != 'transfer') style="display:none;" @endif>Aging</th>
                                              <th class="par-action-cell"></th>
                                            </tr>
                                        </thead>
                                        @php
                                            $grouped = $items->groupBy('header_id');
                                            $grouped->toArray();
                                        @endphp
                                        <tbody id="addedItems">
                                            @foreach($grouped as $item)
                                                @foreach($item as $i)
                                                    @php
                                                        $itemCreatedAt = $itemCreatedDates[$i->item_id] ?? ($i->created_at ?? null);
                                                        $savedTransferValue = $transferValues[$i->id] ?? ($i->transfer_value ?? null);
                                                    @endphp
                                                    <tr id="tr{{$i->item_id}}">
                                                        <td style="display:none;" ><input type="text" class="form-control" name="item_id[]" value="{{$i->item_id}}"></td>
                                                        <td class="par-id-cell">{{ $i->item_id }}</td>
                                                        <td class="par-stock-cell">{{ $i->stock_code }}</td>
                                                        <td class="par-description-cell">{{ $i->description }}</td>
                                                        <td class="par-serial-cell"><input type="text" @if($i->serial_no != '') @endif name="item_serial_no[]" value="{{$i->detail_serial_no}}" class="form-control input-xs text-right"></td>
                                                        <td class="par-qty-cell"><input type="text" @if($i->serial_no != '') @endif name="qty[]" value="{{$i->qty}}" class="form-control input-xs text-right"></td>
                                                        <td class="par-uom-cell">{{ $i->uom }}</td>
                                                        <td class="par-cost-cell" data-purchase-date="{{ $itemCreatedAt ?? '' }}"><input type="text" name="cost[]" class="form-control input-xs text-right" value="{{$i->cost}}"></td>
                                                        <td class="wd-10p transfer-value-cell" @if($par->ptype != 'transfer') style="display:none;" @endif><input type="number" step="0.01" min="0" name="transfer_value[]" class="form-control input-xs text-right" data-saved-value="{{ $savedTransferValue !== null ? '1' : '0' }}" value="{{ $savedTransferValue !== null ? $savedTransferValue : number_format($i->cost / 60, 2, '.', '') }}"></td>
                                                        <td class="par-aging-cell aging-cell" @if($par->ptype != 'transfer') style="display:none;" @endif data-purchase-date="{{ $itemCreatedAt ?? '' }}">@if($itemCreatedAt){{ number_format(\Carbon\Carbon::parse($itemCreatedAt)->diffInMonths(\Carbon\Carbon::today()) / 12, 1) }} years<small class="par-aging-created">Created: {{ \Carbon\Carbon::parse($itemCreatedAt)->format('Y-m-d') }}</small>@endif</td>
                                                        <td class="par-action-cell"><a class="btn btn-danger btn-sm item_delete" data-did="{{$i->details_id}}" data-toggle="modal" href="#close-par-item"><i class="fa fa-trash"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search item item id, serial number, items description...">
                                <span><img style="display: none;" id="item_spinner" class="wd-10p mg-t-4" src="{{ asset('assets/img/spinner/spinner5.gif') }}" alt=""></span>
                                <div id="items_tbl"></div>
                            </div>
                        </div>


                        <div class="divider-text bg-gray-400 text-white">Reference</div>
                        <div class="form-row mg-b-20">
                            <div class="col-md-4">
                                <label for="department">Issuance Doc Reference(Batch#/MWIS) <i class="tx-danger">*</i></label>
                                <input required type="text" name="doc_ref" class="form-control" value="{{ $par->doc_ref }}">
                            </div>
                            @if($par->safety != '')
                            <div class="col-md-4">
                                <label for="department">Safety Control #</label>
                                <input type="text" name="safety" class="form-control" value="{{ $par->safety }}">
                            </div>
                            @endif
                        </div> 

                        <div class="form-row">
                            <div class="col-md-12 mg-t-30 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal effect-scale" id="close-par-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/par-item/delete" method="post" role="form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="details_id" name="id">
                        <p>Please state your reason for deleting this transaction. <i class="text-danger">*</i></p>
                        <textarea required name="remarks" class="form-control" cols="3" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Delete!</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('scripts/par.js') }}?v=20261016"></script>
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
        });
    </script>

    <script>

        $(function(){
            'use strict'
            $('#datepicker4').datepicker();
            $('#issuance_date').datepicker();

            var isFirstUser = $('#par_type').val() === 'new';
            $('#issuance-date-row').toggle(isFirstUser);
            $('#issuance_date').prop('required', isFirstUser);

            var isTransfer = $('#par_type').val() === 'transfer';
            $('.transfer-value-column, .transfer-value-cell, .aging-column, .aging-cell').toggle(isTransfer);
            $('input[name="transfer_value[]"]').prop('required', isTransfer);

            if (isTransfer && window.calculateTransferValue && window.calculateItemAging) {
                $('#addedItems tr').each(function(){
                    var $row = $(this);
                    var $cost = $row.find('input[name="cost[]"]');
                    var purchaseDate = $row.children('td').eq(7).data('purchase-date');
                    var aging = window.calculateItemAging(purchaseDate);

                    if ($row.find('input[name="transfer_value[]"]').data('saved-value') !== 1) {
                        $row.find('input[name="transfer_value[]"]').val(window.calculateTransferValue($cost.val(), purchaseDate).toFixed(2));
                    }
                    $row.find('.aging-cell').html(window.formatItemAgingHtml(aging, purchaseDate));
                });
            }

            window.toggleTransferSearchFields = function() {
                var showAging = $('#par_type').val() === 'transfer';

                $('#items_tbl tr[id^="id"]').each(function(){
                    var $row = $(this);
                    var $agingCell = $row.children('td').eq(8);
                    var purchaseDate = $row.children('td').eq(6).data('purchase-date');

                    if (showAging && window.calculateItemAging && window.formatItemAgingHtml) {
                        $agingCell.html(window.formatItemAgingHtml(window.calculateItemAging(purchaseDate), purchaseDate));
                    } else {
                        $agingCell.empty();
                    }
                });
            };

            window.toggleTransferSearchFields();
        });
    </script>
    <script>

        $(document).on('click','.item_delete',function(){
            $(".modal-body #details_id").val($(this).data('did'));
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
                    url: "{{ route('employee.fetch') }}",
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
                    url: "{{ route('api.department.fetch') }}",
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

            $('#employees').val(i[0]);
            $('#dept').val(i[1]);

            $('#employee_list').fadeOut();
        });  

        $(document).on('click','.dept_li',function(){
            $('#department').val($(this).text());
            $('#department_list').fadeOut();
        });

        $('#par_type').on('change', function(){
            $('#search').val('');
        });

        $('#e_type').on('change', function(){
            if($(this).val() == 1){
                $('#empdiv').show('slow');
                $('#deptdiv').hide('slow');
                $('.dept').val('');
            } else {
                $('#deptdiv').show('slow');
                $('#empdiv').hide('slow');
                $('.emp').val('');
            }
        });
    </script>
@endsection
