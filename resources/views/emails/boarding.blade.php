<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boarding Reservation - PawPet Clinic</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 20px; background-color: #f4f4f5;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Header -->
        <div style="background-color: #0ea5e9; color: #ffffff; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: bold;">🐾 PawPet Clinic</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Boarding Reservation Confirmed</p>
        </div>

        <!-- Body -->
        <div style="padding: 30px;">
            <h2 style="margin-top: 0; color: #1f2937; font-size: 18px;">Hello, {{ $boarding->hewan->owner->name ?? 'Pet Owner' }}!</h2>
            <p style="color: #4b5563; margin-bottom: 25px;">Your pet boarding reservation has been successfully registered in our system. Here are the details:</p>
            
            <div style="background-color: #f0f9ff; border: 1px solid #bae6fd; padding: 20px; border-radius: 6px; margin-bottom: 25px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #0369a1; width: 40%;"><strong>Pet Name</strong></td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: bold; text-align: right;">{{ $boarding->hewan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #0369a1;"><strong>Room</strong></td>
                        <td style="padding: 8px 0; color: #0f172a; text-align: right;">{{ $boarding->kamar->nama_kamar ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #0369a1;"><strong>Check-in Date</strong></td>
                        <td style="padding: 8px 0; color: #0f172a; text-align: right;">{{ \Carbon\Carbon::parse($boarding->tanggal_masuk)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #0369a1;"><strong>Est. Check-out</strong></td>
                        <td style="padding: 8px 0; color: #0f172a; text-align: right;">{{ \Carbon\Carbon::parse($boarding->tanggal_rencana_keluar)->format('d M Y') }}</td>
                    </tr>
                    <tr><td colspan="2"><hr style="border: 0; border-top: 1px solid #bae6fd; margin: 10px 0;"></td></tr>
                    <tr>
                        <td style="padding: 8px 0; color: #0c4a6e; font-size: 16px;"><strong>Estimated Total</strong></td>
                        <td style="padding: 8px 0; color: #0ea5e9; font-size: 16px; font-weight: bold; text-align: right;">Rp {{ number_format($boarding->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <p style="color: #4b5563;">Rest assured, we will take great care of your furry friend. Please feel free to contact us if you have any questions.</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px; text-align: center; color: #64748b; font-size: 12px;">
            <p style="margin: 0;">&copy; {{ date('Y') }} PawPet Clinic. All rights reserved.</p>
            <p style="margin: 5px 0 0 0;">This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>
