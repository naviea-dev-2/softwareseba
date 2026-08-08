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

class LoanExport implements  FromArray , WithHeadings , WithStyles, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $data;
    private $startDate;
    private $endDate;
    private $company;
    public function __construct($reports , $startDate, $endDate, $company)
    {
        $formattedData = [];

        $debit=0;
        $credit=0;
        $balance=0;


        foreach($reports as $key=>$report)
        {
             $dr=0;
            $cr=0;
            if( $report->type == "Loan"){
                $dr=$report->amount;
                $debit+=$report->amount;
                $balance=$balance+$debit;
            }else{
                $cr=$report->amount;
                $credit+=$report->amount;
                $balance=$balance-$credit;
            }


            $formattedData[] = [
                'Employee'         => $report->employee?->employee_name,
                'Date'       =>date('Y-m-d',strtotime($report->loan_date)),
                'Payment Method'  =>$report?->method?->name,
                'Bank Account'   =>$report?->bank_account?->account_name,
                'Type'         => $report->type,
                'Debit'       =>  auth()->user()->currency_symbol.' '.round($dr,2),
                'Credit'  =>auth()->user()->currency_symbol.' '.round($cr,2),
                'Balance'  =>auth()->user()->currency_symbol.' '.round($balance,2),
            ];

        }

        if($formattedData != [])
        {
            $formattedData[] = [
                'Employee'         => 'Total',
                'Date'       =>'',
                'Payment Method'  =>'',
                'Bank Account'   => '',
                'Type'         => '',
                'Debit'   =>auth()->user()->currency_symbol.round($debit, 2),
                'Credit'  =>auth()->user()->currency_symbol.round($credit, 2),
                'Balance'  =>auth()->user()->currency_symbol.round($balance, 2),
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
            'H' => 20,
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
        $sheet->getStyle('H6')->getFont()->setBold(true);
    }

    public function array(): array
    {
        return $this->data;
    }
    public function headings(): array
    {

            return [
                'Employee',
                'Date',
                'Payment Method',
                'Bank Account' ,
                'Type',
                'Debit',
                'Credit',
                'Balance',
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
                $event->sheet->getDelegate()->setCellValue('A5', 'Employee Loan Report')->getStyle('A5')->getFont()->setBold(true);
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
