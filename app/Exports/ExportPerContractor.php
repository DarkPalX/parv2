<?php

namespace App\Exports;

use App\parDetails;
use App\Contractor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPerContractor implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = parDetails::where('status', 'OPEN')
            ->where('isContractor', 1)
            ->select(
                'header_id', 'emp_name', 'accountable', 'document_date', 'issuance_date',
                'detail_serial_no', 'doc_ref', 'stock_code', 'description',
                'expense_type', 'doc_status', 'status', 'qty',
                't_cost', 'added_by', 'details_id'
            );

        $selectedDepartments = $this->request->input('contractor_dept', []);
        $selectedDepartments = is_array($selectedDepartments) ? $selectedDepartments : [$selectedDepartments];
        $selectedDepartments = array_values(array_filter($selectedDepartments, function ($department) {
            return is_string($department) && $department !== '' && $department !== 'ALL';
        }));

        $contractors = Contractor::all();
        $contractorNames = $contractors->map(function ($contractor) {
            return $contractor->contractor_fname.','.$contractor->contractor_mname.' '.$contractor->contractor_lname;
        });

        if ($selectedDepartments) {
            $contractorNames = $contractors
                ->filter(function ($contractor) use ($selectedDepartments) {
                    return in_array($contractor->contractor_dept, $selectedDepartments, true);
                })
                ->map(function ($contractor) {
                    return $contractor->contractor_fname.','.$contractor->contractor_mname.' '.$contractor->contractor_lname;
                });

            $query->whereIn('emp_name', $contractorNames);
        }

        $selectedContractors = $this->request->input('contractor', []);
        $selectedContractors = is_array($selectedContractors) ? $selectedContractors : [$selectedContractors];
        $selectedContractors = array_values(array_filter($selectedContractors, function ($contractor) {
            return is_string($contractor) && $contractor !== '' && $contractor !== 'ALL';
        }));

        if ($selectedContractors) {
            $query->whereIn('emp_name', $selectedContractors);
        }

        $contractorDepartments = $contractors->mapWithKeys(function ($contractor) {
            $name = $contractor->contractor_fname.','.$contractor->contractor_mname.' '.$contractor->contractor_lname;

            return [$name => $contractor->contractor_dept];
        });

        return $query->orderBy('emp_name')->orderBy('header_id', 'desc')->get()->map(function ($row) use ($contractorDepartments) {
            $financialValues = parDetails::financialValues(
                $row->cost ?? $row->t_cost,
                $row->qty,
                parDetails::agingDate($row)
            );

            return [
                \App\accountabilityHeaders::getNewRefCode($row->header_id) ?? $row->header_id,
                $row->emp_name,
                $row->accountable,
                $contractorDepartments->get($row->emp_name, ''),
                $row->document_date,
                $row->issuance_date ?? $row->document_date,
                $financialValues['aging'],
                $row->detail_serial_no,
                $row->doc_ref,
                $row->stock_code,
                $row->description,
                $row->expense_type ?? '',
                $row->doc_status,
                $row->status,
                $row->qty,
                $row->t_cost,
                number_format($financialValues['purchase_cost_50'], 2, '.', ''),
                number_format($financialValues['book_value'], 2, '.', ''),
                number_format($financialValues['chargeable_cost'], 2, '.', ''),
                $row->added_by,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'PAR #', 'Contractor', 'Accountable', 'Department', 'Document Date',
            'Issuance Date', 'Aging', 'Serial #', 'Batch/QR #', 'Stock Code',
            'Description', 'Expense Type', 'Document Status', 'Item Status', 'Qty', 'Cost',
            '50% Purchase Cost', 'Book Value', 'Chargeable Cost', 'Added By'
        ];
    }
}
