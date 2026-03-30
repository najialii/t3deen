<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            color: #1a202c;
            margin: 0;
            padding: 40px;
            line-height: 1.5;
        }
        .brand-section { width: 100%; margin-bottom: 30px; }
        .brand-title { font-size: 26px; font-weight: bold; color: #2d3748; margin: 0; }
        .brand-subtitle { font-size: 13px; color: #718096; }

        /* Modern Details Grid */
        .details-grid {
            width: 100%;
            margin-bottom: 30px;
            border-top: 1px solid #edf2f7;
            border-bottom: 1px solid #edf2f7;
            padding: 20px 0;
        }
        .detail-item {
            display: inline-block;
            width: 32%; /* Three columns */
            vertical-align: top;
            margin-bottom: 15px;
        }
        .label { font-size: 11px; color: #a0aec0; text-transform: uppercase; display: block; margin-bottom: 4px; }
        .value { font-size: 14px; font-weight: bold; color: #2d3748; }

        /* Amount Card */
        .amount-card {
            background-color: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        .amount-value { font-size: 22px; font-weight: bold; text-align: left; }
        .currency { font-size: 13px; color: #718096; font-weight: normal; }

        .notes-section { margin-top: 30px; padding-right: 15px; border-right: 3px solid #e2e8f0; }
        .footer { position: fixed; bottom: 30px; width: 100%; text-align: center; font-size: 10px; color: #cbd5e0; }
    </style>
</head>
<body>

    <div class="brand-section">
        <h1 class="brand-title">{{ $title }}</h1>
        <p class="brand-subtitle">{{ $project_name }}</p>
    </div>

    <div class="details-grid">
        <div class="detail-item">
            <span class="label">{{ $id_label }}</span>
            <span class="value">#{{ $record->id }}</span>
        </div>
        <div class="detail-item">
            <span class="label">{{ $date_label }}</span>
            <span class="value">{{ $record->created_at->format('Y-m-d') }}</span>
        </div>
        <div class="detail-item">
            <span class="label">{{ $customer_label }}</span>
            <span class="value">{{ $customer_name }}</span>
        </div>

        <div class="detail-item">
            <span class="label">{{ $machine_label }}</span>
            <span class="value">{{ $machine_name }}</span>
        </div>
        <div class="detail-item">
            <span class="label">{{ $quantity_label }}</span>
            <span class="value">{{ $quantity_val }} {{ $unit_name }}</span>
        </div>
    </div>

    <div class="amount-card">
        <table style="width: 100%">
            <tr>
                <td style="font-size: 14px; color: #4a5568;">{{ $total_label }}</td>
                <td class="amount-value">
                    {{ number_format($record->total_amount, 2) }}
                    <span class="currency">SDG</span>
                </td>
            </tr>
        </table>
    </div>

    @if($record->notes)
    <div class="notes-section">
        <span class="label">{{ $notes_label }}</span>
        <p style="font-size: 13px; color: #4a5568;">{{ $notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>{{ $footer_text }} • {{ $extract_date }} {{ now()->format('Y-m-d H:i') }}</p>
    </div>

</body>
</html>