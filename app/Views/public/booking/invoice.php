<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= e($booking['booking_number']) ?> - Juney Villa Limited</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; color: #333; margin: 0; padding: 40px; }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 3px solid #C8A45C; padding-bottom: 20px; }
        .brand { font-size: 1.8rem; font-weight: 700; color: #1a1a2e; }
        .brand span { color: #C8A45C; }
        .invoice-title { font-size: 2rem; color: #C8A45C; font-weight: 600; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .info-block h4 { font-size: 0.75rem; text-transform: uppercase; color: #999; margin-bottom: 5px; letter-spacing: 1px; }
        .info-block p { margin: 3px 0; font-size: 0.9rem; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #f8f9fa; padding: 12px 16px; text-align: left; font-size: 0.8rem; text-transform: uppercase; color: #666; }
        td { padding: 12px 16px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .total-section { text-align: right; margin-top: 20px; }
        .total-row { display: flex; justify-content: flex-end; gap: 40px; padding: 5px 0; }
        .total-row.grand { font-size: 1.2rem; font-weight: 700; color: #C8A45C; border-top: 2px solid #C8A45C; padding-top: 10px; margin-top: 10px; }
        .footer { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 0.8rem; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-paid { background: #d4edda; color: #155724; }
        @media print { body { padding: 20px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 20px; background: #C8A45C; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Print Invoice</button>
    </div>

    <div class="invoice-header">
        <div>
            <div class="brand">JUNEY<span>VILLA</span></div>
            <p style="color: #666; margin-top: 5px;">Luxury Villa Rental - Zanzibar, Tanzania</p>
        </div>
        <div style="text-align: right;">
            <div class="invoice-title">INVOICE</div>
            <p><strong>#<?= e($booking['booking_number']) ?></strong></p>
            <p style="color: #666; font-size: 0.85rem;">Date: <?= date('M d, Y') ?></p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-block">
            <h4>Bill To</h4>
            <p><strong><?= e($booking['guest_name']) ?></strong></p>
            <p><?= e($booking['guest_email']) ?></p>
            <p><?= e($booking['guest_phone'] ?? '') ?></p>
            <p><?= e($booking['guest_country'] ?? '') ?></p>
        </div>
        <div class="info-block" style="text-align: right;">
            <h4>Booking Information</h4>
            <p>Villa: <strong><?= e($villa['name']) ?></strong></p>
            <p>Check-in: <?= date('M d, Y', strtotime($booking['check_in'])) ?></p>
            <p>Check-out: <?= date('M d, Y', strtotime($booking['check_out'])) ?></p>
            <p>Nights: <?= $booking['nights'] ?></p>
            <p>Status: <span class="status-badge status-<?= $booking['payment_status'] ?>"><?= ucfirst($booking['payment_status']) ?></span></p>
        </div>
    </div>

    <table>
        <thead>
            <tr><th>Description</th><th>Qty</th><th>Rate</th><th style="text-align:right">Amount</th></tr>
        </thead>
        <tbody>
            <tr>
                <td><?= e($villa['name']) ?> (<?= $booking['nights'] ?> nights)</td>
                <td><?= $booking['nights'] ?></td>
                <td>$<?= number_format((float)$booking['base_price'] / max((int)$booking['nights'], 1), 2) ?></td>
                <td style="text-align:right">$<?= number_format((float)$booking['base_price'], 2) ?></td>
            </tr>
            <?php foreach ($extras as $extra): ?>
            <tr>
                <td><?= e($extra['name']) ?></td>
                <td><?= $extra['quantity'] ?></td>
                <td>$<?= number_format((float)$extra['unit_price'], 2) ?></td>
                <td style="text-align:right">$<?= number_format((float)$extra['total_price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if ((float)$booking['cleaning_fee'] > 0): ?>
            <tr><td>Cleaning Fee</td><td>1</td><td>-</td><td style="text-align:right">$<?= number_format((float)$booking['cleaning_fee'], 2) ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row"><span>Subtotal:</span><span>$<?= number_format((float)$booking['base_price'] + (float)$booking['extras_total'] + (float)$booking['cleaning_fee'], 2) ?></span></div>
        <?php if ((float)$booking['discount_amount'] > 0): ?>
        <div class="total-row"><span>Discount:</span><span style="color:#28a745">-$<?= number_format((float)$booking['discount_amount'], 2) ?></span></div>
        <?php endif; ?>
        <div class="total-row"><span>Tax (VAT 18%):</span><span>$<?= number_format((float)$booking['tax_amount'], 2) ?></span></div>
        <div class="total-row grand"><span>Total Due:</span><span>$<?= number_format((float)$booking['total_amount'], 2) ?></span></div>
    </div>

    <div class="footer">
        <p><strong>Juney Villa Limited</strong></p>
        <p>Zanzibar, Tanzania | +255 777 000 000 | info@juneyvillaszanzibar.co.tz</p>
        <p>https://juneyvillaszanzibar.co.tz</p>
        <p style="margin-top: 10px;">Thank you for choosing Juney Villa Limited. We look forward to hosting you!</p>
    </div>
</body>
</html>
