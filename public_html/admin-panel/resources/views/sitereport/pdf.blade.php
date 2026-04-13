<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Site Report PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #0d94898e;
            color: white;
        }

        .header {
            text-align: left;
            position: fixed;
            top: 5px;
            width: 100%;
            background-color: transparent;
            border-bottom: 1px solid #ddd;
            z-index: 1000;
            margin-top: -25%;
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        tbody tr:nth-child(odd) {
            background-color: #fbfafa;
        }

        tbody tr:nth-child(even) {
            background-color: #e9e9e9a1;
        }

        .header-logo img {
            max-width: 80px;
            max-height: 60px;
            height: 70px;
            width: 60px;
            margin-top: 50px;
            object-fit: contain;
        }

        .header-info-details {
            text-align: left;
            margin-top: -80px;
            margin-left: 80px;
        }

        .header-info-details h3,
        .header-info-details p,
            {
            margin: 0;
            /* Removes default margin */
            padding: 0;
            /* Optional: also remove padding */
            line-height: 1.5;
            /* Optional: tighten line spacing */
        }

        .header-info h2,
        .header-info {
            margin: 0;
            /* Removes default margin */
            padding: 0;
            /* Optional: also remove padding */
            line-height: 1.2;
            /* Optional: tighten line spacing */
        }


        .header-info {
            text-align: center;
            margin-top: 0px;
            margin-bottom: 10px;
        }

        .content {
            margin-top: -40px;
            padding: 20px;
        }

        .walkaround-info-box {
            border: 1.5px solid #cacaca;
            background-color: transparent;
            padding: 15px;
            width: 100%;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signature {
            width: 100px;
            /* Adjust the size as needed */
            height: auto;
            margin-top: 20px;
            /* Add some space above the signature */
        }

        @page {
            margin-top: 15%;
        }

    </style>
</head>
<body>

    <div class="header">
        <div class="header-logo">
            @if (!empty($profileImg))
            <img src="{{ $profileImg }}" alt="Logo" />
            @else
            <p>No Logo available</p>
            @endif
        </div>
        <div class="header-info-details">
            <h3>{{ $sitereport->project->company_name ?? 'N/A' }}</h3>
            <p>{{ $sitereport->project->project_name ?? 'N/A' }}
            </p>
            <p>{{ $sitereport->project->project_number ?? 'N/A' }}
            </p>
            <p>
                {!! nl2br(e(wordwrap($sitereport->project->site_address ?? 'N/A', 40, "\n", true))) !!}
            </p>

        </div>

    </div>

    <div class="content">
        <div class="header-info">
            <h2>SITE REPORT</h2>

        </div>
        <div class="walkaround-info-box">

            <table>
                <tr>
                    <th colspan="2" style="text-align:center; background:#000; color:white;">
                        SITE REPORT DETAILS
                    </th>
                </tr>

                <tr>
                    <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($sitereport->date)->format('d-m-Y') }}</td>
                    <td><strong>Name Of Work:</strong> {{ $sitereport->name_of_work ?? '-' }}</td>


                </tr>
                <tr>
                    <td colspan="2"><strong>Work Description:</strong> {{ $sitereport->work_description ?? '-' }}</td>

                </tr>
                <tr>
                    <td colspan="2"><strong>Work Address:</strong> {{ $sitereport->work_address ?? '-' }}</td>

                </tr>
                        <tr>
                    <td colspan="2"><strong>Created By:</strong> {{ $sitereport->user->name ?? '-' }}</td>

                </tr>
            </table>




        </div>

@if(!empty($sitereportImages) && count($sitereportImages) > 0)

    <div style="margin-top:20px;">
        <p><strong>Images:</strong></p>

        <table width="100%" style="border-collapse: collapse; border:none;">
            <tbody>
            @foreach(array_chunk($sitereportImages, 2) as $row)
                <tr>
                    @foreach($row as $img)
                        <td style="
                            width:50%;
                            text-align:center;
                            padding:15px;
                            border:none;
                            background:transparent;
                        ">
                            <img src="{{ $img }}"
                                 style="
                                    max-width:220px;
                                    height:150px;
                                    object-fit:contain;
                                    border:1px solid #ccc;
                                    background:transparent;
                                 ">
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@else
    <div style="margin-top:20px; text-align:left;">
        <p><strong>Images:</strong> Not Available</p>
    </div>
@endif



    </div>
</body>
</html>
