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

class UserDepositPaymentExport implements  FromArray , WithHeadings , WithStyles, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $data;
    private $search_list;
    private $startDate;
    private $endDate;
    private $company;
    public function __construct($reports,$search_list , $startDate, $endDate, $company)
    {
        $formattedData = [];
        $total = 0;
        foreach($reports as $k=>$deposit_payment)
        {
            $total += $deposit_payment->deposit_amount;
            $payment_status = "";
            if($deposit_payment->payment_status == 1){
                $payment_status = 'Paid';
            }else{
                $payment_status = 'Not Paid';
            }
            $formattedData[] = [
                'sl'         => $k+1,
                'payment_date'       => \Carbon\Carbon::parse($deposit_payment->payment_date)->format("Y-m-d"),
                'p_name'  => $deposit_payment->p_name,
                'm_name'   => $deposit_payment->m_name,
                'payment_status'         => @$payment_status,
                'deposit_amount'         => @$deposit_payment->deposit_amount,
            ];

        }
         $formattedData[] = [
            'sl'         => "Total",
            'payment_date'       => '',
            'p_name'  => '',
            'm_name'   => '',
            'payment_status'         => '',
            'deposit_amount'         => @$total,
        ];


        $this->data         = $formattedData;
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
        $this->search_list      = $search_list;
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
            'I' => 20,
            'J' => 20,
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
        $sheet->getStyle('I6')->getFont()->setBold(true);
        $sheet->getStyle('J6')->getFont()->setBold(true);

    }

    public function array(): array
    {
        return $this->data;
    }
    public function headings(): array
    {

            return [
                'sl'         => "SL.",
                'payment_date'       =>"Payment Date",
                'p_name'  =>"Land Name",
                'm_name'   =>'Payment Method',
                'payment_status'         => "Payment Status",
                'deposit_amount'         => "Deposit Amount",
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
                $event->sheet->getDelegate()->setCellValue('A5', 'Deposit Payment List')->getStyle('A5')->getFont()->setBold(true);
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
