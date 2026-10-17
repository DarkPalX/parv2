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

    window.calculateElapsedDays = function(purchaseDate, endDate) {
        var purchase = window.parseDepreciationDate(purchaseDate);
        var end = window.parseDepreciationDate(endDate) || new Date();

        if (!purchase || !end) {
            return 0;
        }

        var purchaseUtc = Date.UTC(purchase.getFullYear(), purchase.getMonth(), purchase.getDate());
        var endUtc = Date.UTC(end.getFullYear(), end.getMonth(), end.getDate());

        return Math.max(0, Math.round((endUtc - purchaseUtc) / (1000 * 60 * 60 * 24)));
    };

    window.calculateTransferValue = function(cost, purchaseDate) {
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
        var purchase = window.parseDepreciationDate(purchaseDate) || new Date();
        var today = new Date();
        var months = window.calculateElapsedMonths(purchaseDate, today);

        return {
            months: months,
            years: Math.floor(months / 12),
            remainingMonths: months % 12
        };
    };

    window.formatItemAging = function(aging) {
        return (aging.months / 12).toFixed(1) + ' years';
    };

    window.formatItemAgingHtml = function(aging, purchaseDate) {
        var created = window.parseDepreciationDate(purchaseDate);
        var createdText = created ? created.getFullYear() + '-' + String(created.getMonth() + 1).padStart(2, '0') + '-' + String(created.getDate()).padStart(2, '0') : '';
        var createdLine = createdText ? '<small class="par-aging-created">Created: ' + createdText + '</small>' : '';

        return '<span>' + window.formatItemAging(aging) + '</span>' + createdLine;
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
        var aging = window.calculateItemAging ? window.calculateItemAging($(sourceRow).find('.transfer-cost').data('purchase-date')) : { years: 0, remainingMonths: 0 };
        var agingText = window.formatItemAgingHtml ? window.formatItemAgingHtml(aging, $(sourceRow).find('.transfer-cost').data('purchase-date')) : '0.0 years';
        var agingCell = '<td class="par-aging-cell aging-cell" '+($('#par_type').val() === 'transfer' ? '' : 'style="display:none;"')+'>'+agingText+'</td>';

        if(serial == ''){
            $('#addedItems').append('<tr id="tr'+id+'">'+
                '<td style="display:none;" ><input type="text" class="form-control" name="item_id[]" value="'+id+'"></td>'+ 
                '<td class="wd-10p">'+id+'</td>'+
                '<td class="wd-10p">'+stockcode+'</td>'+
                '<td class="wd-30p">'+desc+'</td>'+
                '<td class="par-serial-cell"><input required type="text" name="item_serial_no[]" id="item_serial_no'+id+'" value="" class="form-control input-xs text-right"></td>'+
                '<td class="par-qty-cell"><input required type="text" name="qty[]" id="qty'+id+'" value="" class="form-control input-xs text-right"></td>'+
                '<td class="par-uom-cell">'+uom+'</td>'+
                '<td class="par-cost-cell"><input type="text" id="cost_'+id+'" name="cost[]" class="form-control input-xs text-right" value="'+cost+'"></td>'+
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
                '<td class="par-serial-cell"><input type="text" name="item_serial_no[]" id="item_serial_no'+id+'" value="' + serial + '" class="form-control input-xs text-right"></td>'+
                '<td class="par-qty-cell"><input type="text" name="qty[]" id="qty'+id+'" value="'+qty+'" class="form-control input-xs text-right"></td>'+
                '<td class="par-uom-cell">'+uom+'</td>'+
                '<td class="par-cost-cell"><input type="text" id="cost_'+id+'" name="cost[]" class="form-control input-xs text-right" value="'+cost+'"></td>'+
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

