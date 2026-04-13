<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Login Credentials</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">

  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
    <tr>
      <td style="padding:20px; text-align:center; background:#111827; color:#ffffff; border-radius:10px 10px 0 0;">
        <h2 style="margin:0;">Login Credentials</h2>
      </td>
    </tr>

    <tr>
      <td style="padding:20px; color:#333333;">
        <p>Hi <strong>{{ $user->name }}</strong>,</p>

        <p>Welcome to <strong>1 Vans</strong>.<br>
        Your account has been created with the <b>Engineer</b> role. Please find your login credentials below:</p>

        <table cellpadding="8" cellspacing="0" border="0" style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin:15px 0;">
          <tr>
            <td style="font-weight:bold; width:150px;">Email:</td>
            <td>{{ $user->email }}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">Password:</td>
            <td>{{ $password }}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">Project Access:</td>
            <td>
        @if(!empty($projectNames))
            {{ implode(', ', $projectNames) }}
        @else
             No Projects Assigned
        @endif
    </td>
          </tr>
        </table>

        <p><strong>Download Mobile App:</strong></p>
        <p>
          <a href="https://apps.apple.com/in/app/1-vans/id6748301728" 
             style="display:inline-block; margin:5px; padding:10px 20px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:6px;">
            iOS (App Store)
          </a>
          <a href="https://play.google.com/store/apps/details?id=com.om.vans" 
             style="display:inline-block; margin:5px; padding:10px 20px; background:#16a34a; color:#ffffff; text-decoration:none; border-radius:6px;">
            Android (Google Play)
          </a>
        </p>


        <p>If you face any issues while logging in, feel free to contact us at <a href="mailto:support@1vans.com">support@1vans.com</a>.</p>

        <p style="margin-top:30px;">Best regards,<br><b>1 Vans</b></p>
      </td>
    </tr>
  </table>

</body>
</html>
