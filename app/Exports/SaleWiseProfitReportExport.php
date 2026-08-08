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

class SaleWiseProfitReportExport implements  FromArray , WithHeadings , WithStyles, WithCustomStartCell, WithColumnWidths, WithEvents
{
    private $data;
    private $startDate;
    private $endDate;
    private $company;
    public function __construct($reports , $startDate, $endDate, $company)
    {
        $formattedData = [];

        $total_sale = 0;
        $total_tax = 0;
        $total_shipping = 0;
        $total_discount = 0;
        $total_amount = 0;
        $total_purchase = 0;
        $total_profit = 0;
        foreach($reports as $key=>$report)
        {
            $total_sale += $report->total_cost;
            $total_tax += $report->total_tax;
            $total_shipping += $report->shipping_cost;
            $total_discount += $report->order_discount;
            $total_amount += $report->grand_total;
            $total_purchase +=  $report->total_p_cost;
            $total_profit +=  $report->grand_total - $report->total_p_cost;

            $formattedData[] = [
                'SL'         => $key+1,
                'Date'       => date('Y-m-d', strtotime($report->invoice_date)),
                'Invoice No'  =>$report->reference_no,
                'Customer'  =>$report->customer_name,
                'Product Price'   =>auth()->user()->currency_symbol. ' '.round($report->total_cost, 2),
                'Tax'  =>auth()->user()->currency_symbol.' '.round($report->total_tax, 2),
                'Shipping'  =>auth()->user()->currency_symbol.' '.round($report->shipping_cost, 2),
                'Discount'  =>auth()->user()->currency_symbol.' '.round($report->order_discount, 2),
                'Total Amount'  =>auth()->user()->currency_symbol.' '.round($report->grand_total, 2),
                'Purchase Price'  =>auth()->user()->currency_symbol.' '.round($report->total_p_cost, 2),
                'Profit'  =>auth()->user()->currency_symbol.' '.round($report->grand_total - $report->total_p_cost, 2),
            ];

        }
        if($formattedData != [])
        {
            $formattedData[] = [
                'SL'         => 'Total',
                'Date'       =>'',
                'Invoice No'       =>'',
                'Customer'       =>'',
                'Product Price'  =>auth()->user()->currency_symbol.' '.round($total_sale, 2),
                'Tax'  =>auth()->user()->currency_symbol.' '.round($total_tax, 2),
                'Shipping'  =>auth()->user()->currency_symbol.' '.round($total_shipping, 2),
                'Discount'  =>auth()->user()->currency_symbol.' '.round($total_discount, 2),
                'Total Amount'  =>auth()->user()->currency_symbol.' '.round($total_amount, 2),
                'Purchase Price'  =>auth()->user()->currency_symbol.' '.round($total_purchase, 2),
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
                'Date',
                'Invoice No',
                'Customer',
                'Product Price',
                'Tax',
                'Shipping',
                'Discount',
                'Total Amount',
                'Purchase Price',
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
                $event->sheet->getDelegate()->setCellValue('A5', 'Sale Wise Profit Report')->getStyle('A5')->getFont()->setBold(true);
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
