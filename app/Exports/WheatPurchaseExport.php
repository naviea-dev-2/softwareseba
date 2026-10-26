<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class WheatPurchaseExport implements
    FromQuery,
    WithMapping,
    WithHeadings,
    WithCustomStartCell,
    WithEvents,
    WithColumnWidths
{
    protected Builder $query;

    protected float $totalPurchase;
    protected float $totalPaid;
    protected float $totalDue;

    protected int $rowNumber = 0;

    public function __construct(
        Builder $query,
        float $totalPurchase,
        float $totalPaid,
        float $totalDue
    ) {
        $this->query = clone $query;

        $this->totalPurchase = $totalPurchase;
        $this->totalPaid = $totalPaid;
        $this->totalDue = $totalDue;
    }

    public function query(): Builder
    {
        return $this->query
            ->reorder()
            ->orderBy('purchase_date')
            ->orderBy('id');
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'SI NO',
            'Date',
            'Weight ID',
            'Vehicle No',
            'Driver Name',
            "Driver's Mobile",
            'Quantity',
            'Unload Weight (kg)',
            'Load Weight (kg)',
            'Net Weight (kg)',
            'Party Name',
            'Slip Receiver',
            'Monthly Purchase TON',
            'Price per KG',
            'Total Purchase',
            'Paid Amount',
            'Due Amount',
        ];
    }

    public function map($p): array
    {
        $paid = (float) ($p->paid_amount ?? 0);
        $due = (float) $p->purchase_amount - $paid;

        return [
            ++$this->rowNumber,
            $p->purchase_date
                ? Carbon::parse($p->purchase_date)->format('Y-m-d')
                : '',
            $p->weight_slip_no,
            $p->vehicle_no,
            $p->driver_name,
            $p->driver_mobile,
            $p->quantity_description
                ?: ($p->bag_quantity === null
                    ? ''
                    : $p->bag_quantity . ' Bag'),
            $p->empty_weight_kg,
            $p->loaded_weight_kg,
            $p->net_weight_kg,
            $p->supplier_name,
            $p->slip_receiver,
            '',
            $p->rate_per_kg,
            $p->purchase_amount,
            $paid,
            $due,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 14,
            'C' => 18,
            'D' => 18,
            'E' => 22,
            'F' => 18,
            'G' => 18,
            'H' => 20,
            'I' => 20,
            'J' => 18,
            'K' => 24,
            'L' => 22,
            'M' => 23,
            'N' => 17,
            'O' => 20,
            'P' => 18,
            'Q' => 18,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastDataRow = $sheet->getHighestRow();
                $totalRow = $lastDataRow + 2;

                // Report title.
                $sheet->mergeCells('A1:Q1');
                $sheet->setCellValue('A1', 'WHEAT PURCHASE LEDGER');

                $sheet->getStyle('A1:Q1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '17365D'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(36);

                // Subtitle.
                $sheet->mergeCells('A2:Q2');
                $sheet->setCellValue(
                    'A2',
                    'Kazi Flour Mill | Wheat Purchase Report'
                );

                $sheet->getStyle('A2:Q2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 11,
                        'color' => ['rgb' => '475569'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Header row.
                $sheet->getStyle('A4:Q4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 10,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D6E0EA'],
                        ],
                    ],
                ]);

                $sheet->getRowDimension(4)->setRowHeight(34);

                // Data formatting.
                if ($lastDataRow >= 5) {
                    $sheet->getStyle("A5:Q{$lastDataRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()
                        ->setRGB('E2E8F0');

                    // Alternating row colors.
                    for ($row = 5; $row <= $lastDataRow; $row++) {
                        if (($row - 5) % 2 === 1) {
                            $sheet->getStyle("A{$row}:Q{$row}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID);

                            $sheet->getStyle("A{$row}:Q{$row}")
                                ->getFill()
                                ->getStartColor()
                                ->setRGB('F1F5F9');
                        }
                    }

                    // Numeric formatting.
                    $sheet->getStyle("H5:J{$lastDataRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');

                    $sheet->getStyle("N5:Q{$lastDataRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');

                    $sheet->getStyle("A5:Q{$lastDataRow}")
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);

                    $sheet->getStyle("H5:J{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $sheet->getStyle("N5:Q{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                // Grand totals at the bottom.
                $sheet->setCellValue("G{$totalRow}", 'GRAND TOTAL');
                $sheet->setCellValue("O{$totalRow}", $this->totalPurchase);
                $sheet->setCellValue("P{$totalRow}", $this->totalPaid);
                $sheet->setCellValue("Q{$totalRow}", $this->totalDue);

                $sheet->getStyle("A{$totalRow}:Q{$totalRow}")
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 11,
                            'color' => ['rgb' => '274E13'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'D9EAD3'],
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => ['rgb' => '6AA84F'],
                            ],
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '6AA84F'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                $sheet->getStyle("O{$totalRow}:Q{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $sheet->getStyle("G{$totalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getStyle("O{$totalRow}:Q{$totalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getRowDimension($totalRow)->setRowHeight(26);

                // Freeze header and enable filtering.
                $sheet->freezePane('A5');
                $sheet->setAutoFilter("A4:Q{$lastDataRow}");

                // Print layout.
                $sheet->getPageSetup()
                    ->setOrientation(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                    );

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                $sheet->getPageSetup()->setFitToPage(true);
            },
        ];
    }
}
