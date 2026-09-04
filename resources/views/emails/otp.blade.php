<!DOCTYPE html>
<html>
<head>
    <title>Reset Password OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #1e40af; margin-top: 0;">Reset Password</h2>
        <p style="color: #374151; font-size: 15px; line-height: 1.5;">
            Kami menerima permintaan untuk mereset password akun HR Management Anda.
            Gunakan kode OTP berikut untuk melanjutkan proses reset password.
        </p>
        
        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 15px; text-align: center; border-radius: 8px; margin: 25px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1d4ed8;">{{ $otp }}</span>
        </div>

        <p style="color: #6b7280; font-size: 13px; line-height: 1.5;">
            Kode OTP ini hanya berlaku selama 15 menit. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 25px 0;">
        <p style="color: #9ca3af; font-size: 12px; text-align: center;">
            &copy; {{ date('Y') }} HR Management System. All rights reserved.
        </p>
    </div>
</body>
</html>
