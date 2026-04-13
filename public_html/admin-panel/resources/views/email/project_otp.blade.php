<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Action Required – Confirm Deletion of {{ $project->project_name }}</title>
</head>
<body style="font-family:Arial, sans-serif; line-height:1.5; color:#111;">
    <p>Hello {{ $client->name }},</p>

    <p>We want to inform you that a request to permanently delete your project has been initiated by the <strong>Super Admin</strong> based on a request from the <strong>Sub Admin</strong>.</p>

    <p><strong>Project Details:</strong></p>
    <ul>
        <li>Project Name: {{ $project->project_name }}</li>
        <li>Requested By: Sub Admin – {{ $client->name }}</li>
        <li>Action: Permanent Project Deletion</li>
    </ul>

    <p>Since deleting a project is a critical and irreversible action, we require an additional layer of verification to ensure that this action is authorized.</p>

    <p>To proceed with deletion, please enter the One-Time Password (OTP) provided below:</p>

    <p style="font-size:18px; font-weight:bold;">🔑 Your OTP Code: {{ $otp }}</p>

    <p>This OTP is:</p>
    <ul>
        <li>Required to confirm project deletion.</li>
    </ul>

    <p>⚠️ <strong>Important Notes:</strong></p>
    <ul>
        <li>Once deleted, all project data, files, tasks, reports, and history will be permanently removed from our system.</li>
        <li>Deleted projects cannot be restored under any circumstances.</li>
        <li>If you did not request this deletion, please do not share the OTP and contact our support team immediately.</li>
    </ul>

    <p>By confirming this OTP, you acknowledge and approve the permanent deletion of <strong>{{ $project->project_name }}</strong>.</p>

    <p>Thank you for helping us keep your account secure.</p>

    <p>Best regards,<br>
    1 Vans</p>
</body>
</html>
