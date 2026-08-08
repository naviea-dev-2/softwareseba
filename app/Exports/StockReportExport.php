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

class StockReportExport implements  FromArray , WithHeadings , WithStyles, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $data;
    private $company;
    public function __construct($reports, $company)
    {
        $formattedData = [];
        $total = 0;


        $row=0;
        $total_inQty = 0;
        $total_purchase = 0;
        $total_outQty = 0;
        $total_sale = 0;
        $total_qty = 0;
        foreach($reports as $key=>$report)
        {
            $row++;
            $total_inQty += $report->inQty;
            $total_purchase += $report->purchase_total;
            $total_outQty += $report->outQty;
            $total_sale += $report->sale_total;
            $total_qty += ($report->inQty - $report->outQty);




            $formattedData[] = [
                'SL'         => $key+1,
                'Category'         => $report->cat_name,
                'Brand'         => $report->brand_name,
                'Product'       => $report->product_name,
                'Purchase Qty'  =>$report->inQty,
                'Purchase Amount'   =>auth()->user()->currency_symbol.round($report->purchase_total, 2),
                'Sale Qty'  =>$report->outQty,
                'Sale Amount'  =>auth()->user()->currency_symbol.round($report->sale_total, 2),
                'Current Qty'  =>($report->inQty - $report->outQty),
            ];

        }
        if($formattedData != [])
        {
            $formattedData[] = [
                'SL'         => 'Total',
                'Category'         => '',
                'Brand'         => '',
                'Product'       =>'',
                'Purchase Qty'  =>$total_inQty,
                'Purchase Amount'   =>auth()->user()->currency_symbol.round($total_purchase, 2),
                'Sale Qty'  =>$total_outQty,
                'Sale Amount'  =>auth()->user()->currency_symbol.round($total_sale, 2),
                'Current Qty'  =>$total_qty,
            ];
        }

        $this->data         = $formattedData;
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
                'SL',
                'Category',
                'Brand',
                'Product',
                'Purchase Qty',
                'Purchase Amount',
                'Sale Qty',
                'Sale Amount',
                'Current Qty',
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



                $event->sheet->getDelegate()->setCellValue('A4', 'Stock Report')->getStyle('A4')->getFont()->setBold(true);
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
