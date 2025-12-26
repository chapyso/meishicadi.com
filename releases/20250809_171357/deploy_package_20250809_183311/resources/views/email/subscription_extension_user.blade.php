<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo = Utility::getValByName('company_logo');
@endphp
<head>
    <title>Subscription Extended</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        #outlook a {
            padding: 0;
        }
        .ReadMsgBody {
            width: 100%;
        }
        .ExternalClass {
            width: 100%;
        }
        .ExternalClass * {
            line-height: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
        p {
            display: block;
            margin: 13px 0;
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Open Sans" rel="stylesheet" type="text/css">
</head>
<body style="background-color:#f4f4f4;">
    <div style="background-color:#f4f4f4;">
        <div style="margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                    <tr>
                        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
                            <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                <div style="font-family:Open Sans,helvetica;font-size:20px;line-height:1;text-align:center;color:#555;">
                                                    <img src="{{ !empty($company_logo) ? asset(Storage::url('uploads/logo/'.$company_logo)) : asset(Storage::url('uploads/logo/logo.png')) }}" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;" width="200">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                <div style="font-family:Open Sans,helvetica;font-size:16px;line-height:22px;text-align:center;color:#555;">
                                                    <h2 style="color: #333; margin-bottom: 20px;">Subscription Extended Successfully!</h2>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                <div style="font-family:Open Sans,helvetica;font-size:14px;line-height:22px;text-align:left;color:#555;">
                                                    <p>Dear <strong>{{ $user->name }}</strong>,</p>
                                                    
                                                    <p>Great news! Your subscription has been successfully extended by our administrator.</p>
                                                    
                                                    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                                                        <h3 style="color: #28a745; margin-top: 0;">Extension Details:</h3>
                                                        <ul style="list-style: none; padding: 0;">
                                                            <li style="margin-bottom: 10px;"><strong>Extension Period:</strong> {{ $extensionPeriod }} {{ $extensionPeriod == 1 ? 'Month' : 'Months' }}</li>
                                                            <li style="margin-bottom: 10px;"><strong>New Expiry Date:</strong> {{ $newExpiryDate }}</li>
                                                            <li style="margin-bottom: 10px;"><strong>Current Plan:</strong> {{ !empty($user->currentPlan) ? $user->currentPlan->name : 'No Plan' }}</li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <p>Your account will continue to have access to all the features and services included in your current plan until the new expiry date.</p>
                                                    
                                                    <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
                                                    
                                                    <p>Thank you for choosing Meishicadi!</p>
                                                    
                                                    <p>Best regards,<br>
                                                    <strong>The Meishicadi Team</strong></p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                <div style="font-family:Open Sans,helvetica;font-size:12px;line-height:22px;text-align:center;color:#555;">
                                                    <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                                        This is an automated message from Meishicadi. Please do not reply to this email.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 