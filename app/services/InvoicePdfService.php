<?php
// ./app/service/InvoicePdfService.php

namespace App\Services;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/bootstrap.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicePdfService
{
    public static function generate($student, $classes, $payment)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        ob_start();
?>

        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">

            <style>
                * {
                    font-family: 'Battambang', sans-serif;
                }

                body {
                    font-family: DejaVu Sans, sans-serif;
                    font-size: 12px;
                    color: #333;
                    padding: 20px;
                }

                .header {
                    width: 100%;
                    border-bottom: 2px solid #4F46E5;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }

                .logo {
                    width: 70px;
                    height: 70px;
                }

                .title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #4F46E5;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }

                table th {
                    background: #4F46E5;
                    color: #fff;
                    padding: 8px;
                    text-align: left;
                }

                table td {
                    border-bottom: 1px solid #ddd;
                    padding: 8px;
                }

                .row {
                    margin-bottom: 5px;
                }

                .total {
                    text-align: right;
                    margin-top: 10px;
                    font-size: 14px;
                    font-weight: bold;
                }
            </style>
        </head>

        <body>

            <!-- HEADER -->
            <div class="header">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <img class="logo" src="http://localhost/system-management/src/assets/logo.jpg">
                        </td>

                        <td width="50%">
                            <div class="title">INVOICE</div>
                            <div>Education System</div>
                            <div>Date: <?= date("Y-m-d") ?></div>
                        </td>

                        <td width="30%">
                            <strong>Student:</strong><br>
                            <?= htmlspecialchars(
                                trim(($student['first_name_kh'] ?? '') . ' ' . ($student['last_name_kh'] ?? '')),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- TABLE -->
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th>Price</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($classes as $cls): ?>
                        <tr>
                            <td><?= htmlspecialchars($cls["name"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($cls["teacher"] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>$<?= number_format((float)($cls["price"] ?? 0), 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            $total = (float)($payment["total"] ?? 0);
            ?>

            <div class="total">Total: $<?= number_format($total, 2) ?></div>
            <div class="total">Paid: $<?= number_format($total, 2) ?></div>
            <div class="total">Amount: $<?= number_format($total, 2) ?></div>
            <div class="total">Balance: $0.00</div>

        </body>

        </html>

<?php
        $html = ob_get_clean();

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();

        $dompdf->stream("invoice.pdf", ["Attachment" => false]);
    }
}
