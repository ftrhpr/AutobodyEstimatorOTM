<?php
/**
 * PDF Generator Helper
 * Generates PDF reports for assessments
 * Uses HTML rendering approach (no external dependencies)
 */

class PDF
{
    private string $html = '';
    private array $report;
    private array $vehicle;
    private array $assessment;

    public function generateAssessmentReport(array $report, array $vehicle, array $assessment): void
    {
        $this->report = $report;
        $this->vehicle = $vehicle;
        $this->assessment = $assessment;

        $this->html = $this->buildHtml();
    }

    private function buildHtml(): string
    {
        $date = date('d/m/Y');
        $companyName = config('app.name', 'Auto Damage Assessment');
        $ticketNumber = htmlspecialchars($this->report['ticket_number']);

        $vehicleInfo = sprintf(
            '%d %s %s',
            $this->vehicle['year'],
            htmlspecialchars($this->vehicle['make']),
            htmlspecialchars($this->vehicle['model'])
        );

        $plateNumber = $this->vehicle['plate_number'] ? htmlspecialchars($this->vehicle['plate_number']) : 'N/A';
        $vin = $this->vehicle['vin'] ? htmlspecialchars($this->vehicle['vin']) : 'N/A';

        $damageLocation = $this->getDamageLocationLabel($this->report['damage_location']);
        $description = nl2br(htmlspecialchars($this->report['description']));

        $itemsHtml = '';
        $items = $this->assessment['items'] ?? [];
        foreach ($items as $item) {
            $itemsHtml .= sprintf(
                '<tr><td style="padding: 8px; border-bottom: 1px solid #ddd;">%s</td>
                     <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: right;">%.2f GEL</td></tr>',
                htmlspecialchars($item['description']),
                $item['cost']
            );
        }

        $totalCost = number_format($this->assessment['total_cost'], 2);
        $estimatedDays = $this->assessment['estimated_days'] ?? 'N/A';
        $comments = $this->assessment['comments']
            ? nl2br(htmlspecialchars($this->assessment['comments']))
            : 'No additional comments';

        $assessedDate = date('d/m/Y H:i', strtotime($this->assessment['created_at']));
        $assessorName = htmlspecialchars($this->assessment['admin_name'] ?? 'Admin');

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assessment Report - {$ticketNumber}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
        }
        .ticket-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background: #f5f5f5;
            padding: 8px 15px;
            font-weight: bold;
            border-left: 4px solid #667eea;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 10px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 150px;
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .items-table th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .items-table th:last-child {
            text-align: right;
        }
        .total-row {
            background: #f0f4ff;
            font-weight: bold;
            font-size: 14px;
        }
        .total-row td {
            padding: 12px 8px !important;
        }
        .comments-box {
            background: #fff9e6;
            border: 1px solid #f0d78c;
            padding: 15px;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{$companyName}</h1>
        <p>Damage Assessment Report</p>
        <div class="ticket-badge">#{$ticketNumber}</div>
    </div>

    <div class="section">
        <div class="section-title">Vehicle Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Vehicle:</td>
                <td>{$vehicleInfo}</td>
                <td class="label">Plate Number:</td>
                <td>{$plateNumber}</td>
            </tr>
            <tr>
                <td class="label">VIN:</td>
                <td colspan="3">{$vin}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Damage Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Location:</td>
                <td>{$damageLocation}</td>
                <td class="label">Report Date:</td>
                <td>{$date}</td>
            </tr>
        </table>
        <div style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
            <strong>Description:</strong><br>
            {$description}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assessment Details</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Repair Item</th>
                    <th style="text-align: right;">Cost</th>
                </tr>
            </thead>
            <tbody>
                {$itemsHtml}
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td style="padding: 12px 8px; border-top: 2px solid #667eea;">TOTAL ESTIMATED COST</td>
                    <td style="padding: 12px 8px; border-top: 2px solid #667eea; text-align: right;">{$totalCost} GEL</td>
                </tr>
            </tfoot>
        </table>

        <table class="info-table" style="margin-top: 15px;">
            <tr>
                <td class="label">Estimated Repair Time:</td>
                <td>{$estimatedDays} days</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Comments & Recommendations</div>
        <div class="comments-box">
            {$comments}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assessment Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Assessed By:</td>
                <td>{$assessorName}</td>
            </tr>
            <tr>
                <td class="label">Assessment Date:</td>
                <td>{$assessedDate}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>This is an official assessment document generated by {$companyName}</p>
        <p>Document generated on: {$date}</p>
        <p style="margin-top: 10px;">
            <strong>Note:</strong> This assessment is an estimate based on visible damage.
            Final costs may vary based on hidden damage discovered during repair.
        </p>
    </div>
</body>
</html>
HTML;
    }

    private function getDamageLocationLabel(string $location): string
    {
        $locations = [
            'front' => 'Front',
            'rear' => 'Rear',
            'left' => 'Left Side',
            'right' => 'Right Side',
            'roof' => 'Roof',
            'hood' => 'Hood',
            'trunk' => 'Trunk',
            'windshield' => 'Windshield',
            'other' => 'Other',
        ];

        return $locations[$location] ?? ucfirst($location);
    }

    public function output(string $filename): void
    {
        // Set headers for PDF download
        // Note: For true PDF generation, you would need a library like TCPDF, FPDF, or DomPDF
        // This outputs HTML that can be printed to PDF by the browser

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="' . $filename . '"');

        // Add print script for automatic print dialog
        $printScript = <<<HTML
<script>
    window.onload = function() {
        // Automatically open print dialog
        window.print();
    }
</script>
HTML;

        echo str_replace('</body>', $printScript . '</body>', $this->html);
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function saveToPdf(string $filepath): bool
    {
        // For actual PDF generation, you would integrate with:
        // - TCPDF: require_once 'tcpdf/tcpdf.php';
        // - FPDF: require_once 'fpdf/fpdf.php';
        // - DomPDF: require_once 'dompdf/autoload.inc.php';
        // - wkhtmltopdf: exec('wkhtmltopdf - output.pdf', $output, $return);

        // For now, save as HTML
        return file_put_contents($filepath . '.html', $this->html) !== false;
    }
}
