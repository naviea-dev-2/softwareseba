<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LedgerSummaryExport implements  FromArray , WithHeadings , WithStyles, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $data;
    private $startDate;
    private $endDate;
    private $company;
    public function __construct($transactions , $startDate, $endDate, $company)
    {
        $formattedData = [];
        $total = 0;


        $i=1; $total_dr=0; $total_cr=0;
        foreach($transactions as $transaction)
        {
            if($transaction->type == "credit"){
                $total_cr += $transaction->amount;
                $dr = '--';
                $cr =  $transaction->amount;
            }else{
                $total_dr += $transaction->amount;
                $dr = $transaction->amount;
                $cr =  '--';
            }
            $reference="";
            if($transaction->sub_type == "Expense"){
                $reference=$transaction->expense?->reference_no;
            }else if($transaction->sub_type == "Sales"){
                $reference=$transaction->invoice?->reference_no;
            
            }else if($transaction->sub_type == "Sales Payment"){
                $reference=$transaction->invoice?->reference_no;
            
            }else if($transaction->sub_type == "Purchase"){
                $reference=$transaction->purchase?->reference_no;
            }
            else if($transaction->sub_type == "Purchase Payment"){
                $reference=$transaction->purchase?->reference_no;
            }
            else if($transaction->sub_type == "Sales Return"){
                $reference=$transaction->invoice_return?->reference_no;
            }
            else if($transaction->sub_type == "Sales Return Payment"){
                $reference=$transaction->invoice_return?->reference_no;
            }
            else if($transaction->sub_type == "Purchase Return"){
                $reference=$transaction->purchase_return?->reference_no;
            }
            else if($transaction->sub_type == "Purchase Return Payment"){
                $reference=$transaction->purchase_return?->reference_no;
            }
            else if($transaction->sub_type == "Salary"){
                $reference='Salary';
            }
            else if($transaction->sub_type == "Salary Payment"){
                $reference='Salary Payment';
            }
            else if($transaction->sub_type == "Bonus"){
                $reference=$transaction->bonus?->reference_no;
            }
            else if($transaction->sub_type == "Bonus Pay"){
                $reference=$transaction->bonus?->reference_no;
            }
            else if($transaction->sub_type == "Employee Loan"){
                $reference=$transaction->emp_loan?->reference_no;
            }
            else if($transaction->sub_type == "Employee Loan Pay"){
                $reference=$transaction->emp_loan?->reference_no;
            }
            else if($transaction->sub_type == "Employee Loan Return"){
                $reference=$transaction->emp_loan?->reference_no;
            }
            else if($transaction->sub_type == "Employee Loan Return Pay"){
                $reference=$transaction->emp_loan?->reference_no;
            }
            $formattedData[] = [
                '#'         =>$i,
                'Reference No' =>$reference,
                'Account Name'  => $transaction->account?->title,
                'Transaction Type'   =>$transaction->sub_type,
                'Reason'   =>$transaction->reason,
                'Debit'   => auth()->user()->currency_symbol.round($dr,2),
                'Credit'   => auth()->user()->currency_symbol.round($cr,2)
            ];

             $i++;

        }
        if($formattedData != [])
        {
            $formattedData[] = [
                '#'         => 'Total',
                'Reference No'       =>'',
                'Account Name'  =>'',
                'Transaction Type'  =>'',
                'Reason'  =>'',
                'Debit'   => auth()->user()->currency_symbol.round($total_dr, 2),
                'Credit'   => auth()->user()->currency_symbol.round($total_cr, 2)
            ];
        }

        $this->data         = $formattedData;
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
        $this->company  = $company;
    }
     public function startCell(): string
    {
        return 'A6';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('B6')->getFont()->setBold(true);
        $sheet->getStyle('C6')->getFont()->setBold(true);
        $sheet->getStyle('D6')->getFont()->setBold(true);
        $sheet->getStyle('E6')->getFont()->setBold(true);
        $sheet->getStyle('F6')->getFont()->setBold(true);
        $sheet->getStyle('G6')->getFont()->setBold(true);

    }

    public function array(): array
    {
        return $this->data;
    }
    public function headings(): array
    {

            return [
               '#',
                'Reference No',
                'Account Name',
                'Transaction Type',
                'Reason',
                'Debit',
                'Credit'
            ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->mergeCells('A2:K2');
                $event->sheet->getDelegate()->mergeCells('A3:K3');
                $event->sheet->getDelegate()->mergeCells('A4:K4');
                $event->sheet->getDelegate()->mergeCells('A5:K5');

                $event->sheet->getDelegate()->setCellValue('A1',$this->company->business_name)->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue('A2', $this->company->moible_number)->getStyle('A2')->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue('A3', $this->company->email)->getStyle('A3')->getFont()->setBold(true);


                $event->sheet->getDelegate()->setCellValue('A4', 'Date : ' . $this->startDate . ' - ' . $this->endDate)->getStyle('A4')->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue('A5', 'Ledger Summary')->getStyle('A5')->getFont()->setBold(true);
                $startRow = 1;
                $lastRow = $event->sheet->getHighestRow();

                $event->sheet->getStyle('A' . $startRow . ':Z' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                $data = $this->data;
                foreach ($data as $index => $row) {
                    if (isset($row['SL']) && ($row['SL'] == 'Total')) {
                        $rowIndex = $index + 7; // Adjust for 1-based indexing and header row
                        $event->sheet->getStyle('A' . $rowIndex . ':D' . $rowIndex)
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                ],
                            ]);
                    }
                }
            },
        ];
    }
}
