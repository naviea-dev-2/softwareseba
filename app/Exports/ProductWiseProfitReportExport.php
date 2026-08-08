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

class ProductWiseProfitReportExport implements  FromArray , WithHeadings , WithStyles, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $data;
    private $startDate;
    private $endDate;
    private $company;
    public function __construct($reports , $startDate, $endDate, $company)
    {
        $formattedData = [];

        $total_qty = 0;
        $total_unit_sale = 0;
        $total_sale = 0;
        $total_unit_purchase = 0;
        $total_purchase = 0;
        $total_profit = 0;
        foreach($reports as $key=>$report)
        {
            $sub_sale_price = $report->per_cost * $report->qty;
            $sub_purchase_price = $report->purchase_price * $report->qty;
            $sub_profit = $sub_sale_price - $sub_purchase_price;

            $total_qty += $report->qty;
            $total_unit_sale += $report->per_cost;
            $total_unit_purchase += $report->purchase_price;
            $total_sale += $sub_sale_price;
            $total_purchase += $sub_purchase_price;
            $total_profit += $sub_profit;




            $formattedData[] = [
                'SL'         => $key+1,
                'Product Name'       => $report->product_name,
                'Sale'  =>$report->qty,
                'Sale Unit Price'   =>auth()->user()->currency_symbol. ' '.round($report->per_cost, 2),
                'Total SP'  =>auth()->user()->currency_symbol.' '.round($sub_sale_price, 2),
                'Purchase Unit Price'  =>auth()->user()->currency_symbol.' '.round($report->purchase_price, 2),
                'Total PP'  =>auth()->user()->currency_symbol.' '.round($sub_purchase_price, 2),
                'Profit'  =>auth()->user()->currency_symbol.' '.round($sub_profit, 2),
            ];

        }
        if($formattedData != [])
        {
            $formattedData[] = [
                'SL'         => 'Total',
                'Product Name'       =>'',
                'Qty'  =>$total_qty,
                'Sale Unit Price'   =>'',
                'Total SP'  =>auth()->user()->currency_symbol.' '.round($total_sale, 2),
                'Purchase Unit Price'   =>'',
                'Total PP'  =>auth()->user()->currency_symbol.' '.round($total_purchase, 2),
                'Profit'  =>auth()->user()->currency_symbol.' '.round($total_profit, 2),
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
            'I' => 20,
            'J' => 20,
            'K' => 20,
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
        $sheet->getStyle('K6')->getFont()->setBold(true);

    }

    public function array(): array
    {
        return $this->data;
    }
    public function headings(): array
    {

            return [
               'SL',
                'Product Name',
                'Sale',
                'Sale Unit Price',
                'Total SP',
                'Purchase Unit Price',
                'Total PP',
                'Profit',
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
                $event->sheet->getDelegate()->setCellValue('A5', 'Invoice Report')->getStyle('A5')->getFont()->setBold(true);
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
