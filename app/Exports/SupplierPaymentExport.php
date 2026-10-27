<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Supplier-wise payment summary.
 * Rows need: supplier_name, total_purchases, total_payments, outstanding.
 * Grand totals are calculated here from the rows.
 */
class SupplierPaymentExport implements
    FromCollection,
    WithMapping,
    WithHeadings,
    WithCustomStartCell,
    WithEvents,
    WithColumnWidths,
    WithTitle
{
    protected Collection $rows;
    protected int $rowNumber = 0;

    public function __construct($rows)
    {
        $this->rows = collect($rows);
    }

    public function title(): string
    {
        return 'Supplier Payments';
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'SI NO',
            'Supplier',
            'Total Purchases',
            'Total Payments',
            'Outstanding',
        ];
    }

    public function map($r): array
    {
        return [
            ++$this->rowNumber,
            $r->supplier_name,
            (float) $r->total_purchases,
            (float) $r->total_payments,
            round((float) $r->outstanding, 2),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 36,
            'C' => 22,
            'D' => 22,
            'E' => 22,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastDataRow = $sheet->getHighestRow();
                $totalRow    = $lastDataRow + 2;

                $grandPurchases = (float) $this->rows->sum('total_purchases');
                $grandPayments  = (float) $this->rows->sum('total_payments');
                $grandDue       = round($grandPurchases - $grandPayments, 2);

                // Title.
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'SUPPLIER-WISE PAYMENT SUMMARY');

                $sheet->getStyle('A1:E1')->applyFromArray([
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
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue(
                    'A2',
                    'Kazi Flour Mill | Supplier Payment Report | Generated ' . now()->format('d M Y, h:i A')
                );
                $sheet->getStyle('A2:E2')->applyFromArray([
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
                $sheet->getStyle('A4:E4')->applyFromArray([
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
                $sheet->getRowDimension(4)->setRowHeight(30);

                // Data rows.
                if ($lastDataRow >= 5) {
                    $sheet->getStyle("A5:E{$lastDataRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()
                        ->setRGB('E2E8F0');

                    for ($row = 5; $row <= $lastDataRow; $row++) {
                        if (($row - 5) % 2 === 1) {
                            $sheet->getStyle("A{$row}:E{$row}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID);

                            $sheet->getStyle("A{$row}:E{$row}")
                                ->getFill()
                                ->getStartColor()
                                ->setRGB('F1F5F9');
                        }
                    }

                    $sheet->getStyle("C5:E{$lastDataRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');

                    $sheet->getStyle("C5:E{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $sheet->getStyle("A5:E{$lastDataRow}")
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);

                    // Outstanding in red when something is owed.
                    $sheet->getStyle("E5:E{$lastDataRow}")
                        ->getFont()
                        ->setBold(true);
                }

                // Grand total.
                $sheet->setCellValue("B{$totalRow}", 'GRAND TOTAL');
                $sheet->setCellValue("C{$totalRow}", $grandPurchases);
                $sheet->setCellValue("D{$totalRow}", $grandPayments);
                $sheet->setCellValue("E{$totalRow}", $grandDue);

                $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray([
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

                $sheet->getStyle("C{$totalRow}:E{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $sheet->getStyle("B{$totalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getStyle("C{$totalRow}:E{$totalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getRowDimension($totalRow)->setRowHeight(26);

                // Freeze header, filter, print layout.
                $sheet->freezePane('A5');
                if ($lastDataRow >= 5) {
                    $sheet->setAutoFilter("A4:E{$lastDataRow}");
                }

                $sheet->getPageSetup()
                    ->setOrientation(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT
                    );
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getPageSetup()->setFitToPage(true);
            },
        ];
    }
}