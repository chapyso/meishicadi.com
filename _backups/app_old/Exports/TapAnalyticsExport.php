<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TapAnalyticsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'Business Name',
            'Card ID',
            'Tap Source',
            'IP Address',
            'Country',
            'City',
            'Region',
            'Device Type',
            'Device OS',
            'Browser',
            'Browser Version',
            'Referrer',
            'UTM Source',
            'UTM Medium',
            'UTM Campaign',
            'Is Suspicious',
            'Suspicious Reason',
            'Created At'
        ];
    }

    public function map($tap): array
    {
        return [
            $tap->id,
            $tap->user ? $tap->user->name : 'N/A',
            $tap->business ? $tap->business->title : 'N/A',
            $tap->card_id ?? 'N/A',
            $tap->tap_source,
            $tap->ip_address ?? 'N/A',
            $tap->country ?? 'N/A',
            $tap->city ?? 'N/A',
            $tap->region ?? 'N/A',
            $tap->device_type,
            $tap->device_os ?? 'N/A',
            $tap->browser ?? 'N/A',
            $tap->browser_version ?? 'N/A',
            $tap->referrer ?? 'N/A',
            $tap->utm_source ?? 'N/A',
            $tap->utm_medium ?? 'N/A',
            $tap->utm_campaign ?? 'N/A',
            $tap->is_suspicious ? 'Yes' : 'No',
            $tap->suspicious_reason ?? 'N/A',
            $tap->created_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ]
        ];
    }
} 