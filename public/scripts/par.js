    window.parseDepreciationDate = function(value) {
        if (value instanceof Date) {
            return value;
        }

        if (value === null || value === undefined || value === '') {
            return null;
        }

        var normalized = String(value).trim().replace(' ', 'T');
        var parsed = new Date(normalized);

        return isNaN(parsed.getTime()) ? null : parsed;
    };

    window.calculateElapsedMonths = function(purchaseDate, endDate) {
        var purchase = window.parseDepreciationDate(purchaseDate);
        var end = endDate ? window.parseDepreciationDate(endDate) : new Date();

        if (!purchase || !end || end.getTime() < purchase.getTime()) {
            return 0;
        }

        if (!purchaseDate) {
            return 0;
        }

        var months = (end.getFullYear() - purchase.getFullYear()) * 12;
        months += end.getMonth() - purchase.getMonth();

        if (end.getDate() < purchase.getDate()) {
            months--;
        }

        return Math.max(0, months);
    };

    window.calculateTransferValue = function(cost, purchaseDate) {
        var startingValue = parseFloat(cost) || 0;
        var monthlyDepreciation = startingValue / 60;
        var elapsedMonths = window.calculateElapsedMonths(purchaseDate);

        return Math.max(0, startingValue - (elapsedMonths * monthlyDepreciation));
    };

    window.calculateItemAging = function(purchaseDate) {
        var purchase = window.parseDepreciationDate(purchaseDate) || new Date();
        var today = new Date();
        var days = isNaN(purchase.getTime()) ? 0 : Math.max(0, Math.floor((today - purchase) / 86400000));
        var months = window.calculateElapsedMonths(purchaseDate, today);

        return {
            days: days,
            months: months,
            years: (months / 12).toFixed(2)
        };
    };

    $('#e_type').on('change', function(){
        if($(this).val() == 1){
            $('#empdiv').show('slow');
            $('#deptdiv').hide('slow');
            $('#contractordiv').hide('slow');

            $('#department').val('');
            $('#contractor').val('');

            $("#employees").attr("required",true);
            $("#contractor").attr("required",false);
            $("#department").attr("required",false);
        }

        if($(this).val() == 2){
            $('#deptdiv').show('slow');
            $('#empdiv').hide('slow');
            $('#contractordiv').hide('slow');

            $('#employees').val('');
            $('#contractor').val('');

            $("#department").attr("required",true);
            $("#contractor").attr("required",false);
            $("#employees").attr("required",false);
        }

        if($(this).val() == 3){
            $('#contractordiv').show('slow');
            $('#empdiv').hide('slow');
            $('#deptdiv').hide('slow');

            $('#employees').val('');
            $('#department').val('');

            $("#contractor").attr("required",true);
            $("#department").attr("required",false);
            $("#employees").attr("required",false);
        }

    });
    
    function removeItem(i){
        var new_value = $('#total_items').val();
        $('#total_items').val(parseInt(new_value)-1);

        $('#id'+i+'').show();
        $('#tr'+i+'').remove();

    }

    function addToItem(id,stockcode,desc,uom,serial,cost,qty){

        var old_value = $('#total_items').val();

        $('#id'+id+'').hide();

        var item_id = parseInt(old_value)+1;
        $('#total_items').val(parseInt(old_value)+1);
        var transferValue = (parseFloat(cost) || 0) / 60;
        var sourceRow = document.getElementById('id'+id);
        if (sourceRow && window.calculateTransferValue) {
            var purchaseDate = $(sourceRow).find('.transfer-cost').data('purchase-date');
            transferValue = window.calculateTransferValue(cost, purchaseDate);
        }
        var transferValueCell = '<td class="wd-10p transfer-value-cell" '+($('#par_type').val() === 'transfer' ? '' : 'style="display:none;"')+'><input type="number" step="0.01" min="0" name="transfer_value[]" class="form-control input-xs text-right" value="'+transferValue.toFixed(2)+'"></td>';
        var aging = window.calculateItemAging ? window.calculateItemAging($(sourceRow).find('.transfer-cost').data('purchase-date')) : { years: '0.00', days: 0 };
        var agingCell = '<td class="wd-10p aging-cell" '+($('#par_type').val() === 'transfer' ? '' : 'style="display:none;"')+'>'+aging.months+' mos ('+aging.days+' days)</td>';

        if(serial == ''){
            $('#addedItems').append('<tr id="tr'+id+'">'+
                '<td style="display:none;" ><input type="text" class="form-control" name="item_id[]" value="'+id+'"></td>'+ 
                '<td class="wd-10p">'+id+'</td>'+
                '<td class="wd-10p">'+stockcode+'</td>'+
                '<td class="wd-30p">'+desc+'</td>'+
                '<td class="wd-20p"><input required type="text" name="item_serial_no[]" id="item_serial_no'+id+'" value="" class="form-control input-xs text-right"></td>'+
                '<td class="wd-10p"><input required type="text" name="qty[]" id="qty'+id+'" value="" class="form-control input-xs text-right"></td>'+
                '<td class="wd-10p">'+uom+'</td>'+
                '<td class="wd-10p"><input type="text" id="cost_'+id+'" name="cost[]" class="form-control input-xs text-right" value="'+cost+'"></td>'+
                transferValueCell+
                agingCell+
                '<td class="wd-10p"><button class="btn btn-danger btn-sm" onclick=\"removeItem('+id+');\"><i class="fa fa-trash"></i></button></td>'+
                '</tr>');
        } else {
            $('#addedItems').append('<tr id="tr'+id+'">'+
                '<td style="display:none;" ><input type="text" class="form-control" name="item_id[]" value="'+id+'"></td>'+ 
                '<td class="wd-10p">'+id+'</td>'+
                '<td class="wd-10p">'+stockcode+'</td>'+
                '<td class="wd-30p">'+desc+'</td>'+
                '<td class="wd-20p"><input type="text" name="item_serial_no[]" id="item_serial_no'+id+'" value="' + serial + '" class="form-control input-xs text-right"></td>'+
                '<td class="wd-10p"><input type="text" name="qty[]" id="qty'+id+'" value="'+qty+'" class="form-control input-xs text-right"></td>'+
                '<td class="wd-10p">'+uom+'</td>'+
                '<td class="wd-10p"><input type="text" id="cost_'+id+'" name="cost[]" class="form-control input-xs text-right" value="'+cost+'"></td>'+
                transferValueCell+
                agingCell+
                '<td class="wd-10p"><button class="btn btn-danger btn-sm" onclick=\"removeItem('+id+');\"><i class="fa fa-trash"></i></button></td>'+
                '</tr>');
        }

        if ($('#par_type').val() === 'transfer') {
            $('#tr'+id+' .transfer-value-cell').show();
            $('#tr'+id+' input[name="transfer_value[]"]').prop('required', true);
        }

    }

