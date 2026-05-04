<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt - PawPet Clinic</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 20px; background-color: #f4f4f5;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Header -->
        <div style="background-color: #f97316; color: #ffffff; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">🐾 PawPet Clinic</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Transaction Receipt</p>
        </div>

        <!-- Body -->
        <div style="padding: 30px;">
            <h2 style="margin-top: 0; color: #1f2937; font-size: 18px;">Hello, {{ $transaction->pelanggan->name ?? 'Customer' }}!</h2>
            <p style="color: #4b5563; margin-bottom: 25px;">Thank you for your visit and trusting PawPet Clinic. Here are the details of your recent transaction:</p>
            
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 6px; margin-bottom: 25px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; width: 40%;"><strong>Transaction ID</strong></td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: bold; text-align: right;">{{ $transaction->kode_transaksi }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;"><strong>Date</strong></td>
                        <td style="padding: 8px 0; color: #0f172a; text-align: right;">{{ $transaction->tanggal->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b;"><strong>Status</strong></td>
                        <td style="padding: 8px 0; text-align: right;">
                            <span style="background-color: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase;">{{ $transaction->status }}</span>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 10px 0;"></td></tr>
                    <tr>
                        <td style="padding: 8px 0; color: #1e293b; font-size: 16px;"><strong>Total Paid</strong></td>
                        <td style="padding: 8px 0; color: #f97316; font-size: 16px; font-weight: bold; text-align: right;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <p style="color: #4b5563;">We look forward to welcoming you and your furry friend again soon!</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f1f5f9; padding: 20px; text-align: center; color: #64748b; font-size: 12px;">
            <p style="margin: 0;">&copy; {{ date('Y') }} PawPet Clinic. All rights reserved.</p>
            <p style="margin: 5px 0 0 0;">This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>
