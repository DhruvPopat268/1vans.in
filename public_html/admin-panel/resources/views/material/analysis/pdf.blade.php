<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Material Incoming Report PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
         th { background-color: #0d94898e; color:white; }
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
.header-info-details p, {
    margin: 0;           /* Removes default margin */
    padding: 0;          /* Optional: also remove padding */
    line-height: 1.5;    /* Optional: tighten line spacing */
}
.header-info h2,
.header-info {
    margin: 0;           /* Removes default margin */
    padding: 0;          /* Optional: also remove padding */
    line-height: 1.2;    /* Optional: tighten line spacing */
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
            width: 100px; /* Adjust the size as needed */
            height: auto;
            margin-top: 20px; /* Add some space above the signature */
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
                <img src="{{ $profileImg }}" alt="Logo"/>
            @else
                <p>No Logo available</p>
            @endif
        </div>
         <div class="header-info-details">
           <h3>{{ $materialincoming->project->company_name ?? 'N/A' }}</h3>
            <p>{{ $materialincoming->project->project_name ?? 'N/A' }}
            </p>
            <p>{{ $materialincoming->project->project_number ?? 'N/A' }}
            </p>
           <p>
                {!! nl2br(e(wordwrap($materialincoming->project->site_address ?? 'N/A', 40, "\n", true))) !!}
            </p>

         </div>
        
    </div>

    <div class="content">
         <div class="header-info">
            <h2>MATERIAL INCOMING REPORT</h2>
            
        </div>
        <div class="walkaround-info-box">
            
            <table>
                <tr>
                <th colspan="2" style="text-align:center; background:#000; color:white;">
                    MATERIAL INCOMING
                </th>
            </tr>

                <tr>
                    <td><strong>Challan Number:</strong> {{ $materialincoming->challan_number }}</td>
                    <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($materialincoming->date)->format('d-m-Y') }}</td>

                </tr>
                <tr>
                    <td><strong>Bill Number:</strong> {{ $materialincoming->bill_number }}</td>
                    <td><strong>Location:</strong> {{ $materialincoming->location ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>GST Number:</strong> {{ $materialincoming->gst_number }}</td>
                    <td><strong>Vehicle Number:</strong> {{ $materialincoming->vehicle_number }}</td>
                </tr>
                <tr>
                    <td><strong>Batch Number:</strong> {{ $materialincoming->batch_number }}</td>
                    <td><strong>Uploaded By:</strong> {{ $materialincoming->user->name ?? 'N/A' }}</td>
                </tr>
                                                <tr>
                    <td colspan="2"><strong>Vendor Name:</strong> {{ $materialincoming->vendor_name }}</td>

                </tr>
                <tr>
                    <td colspan="2"><strong>E-Way Bill Number:</strong> {{ $materialincoming->eway_bill_no }}</td>

                </tr>

                 <tr>
                    <td colspan="2"><strong>Royalty Slip Number:</strong> {{ $materialincoming->royalty_slip_no }}</td>
                </tr>

                <tr>
                    <td colspan="2"><strong>Description:</strong> {{ $materialincoming->description }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Remark:</strong> {{ $materialincoming->remark }}</td>
                </tr>
            </table>




        </div>
    <div class="walkaround-info-box">
       
        <table>
            <thead>
                <tr>
                <th colspan="2" style="text-align:center; background:#000; color:white;">
                    MATERIAL NAME - {{ strtoupper($materialincoming->category->name ?? 'N/A') }}
                </th>
            </tr>
                <tr>

                    <th>Material Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materialincoming->stocks as $stock)
                    <tr>
                        <td>{{ optional($stock->subCategory)->name ?? 'N/A' }}</td>
                        <td>{{ $stock->stock }} {{ $stock->subCategory->attribute->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <!-- Add signature image after the table -->
    <div style="text-align: right; margin-top: 20px;">
        @if(!empty($signatureImg))
        <p><strong>Signature:</strong></p>
        <img src="{{ $signatureImg }}" class="signature">
    @else
        <p><strong>Signature:</strong> Not available</p>
    @endif
    </div>

@if(!empty($royaltySlipImg))
    <div style="margin-top: 20px;">
        <p><strong>Royalty Slip Image:</strong></p>
        <img src="{{ $royaltySlipImg }}" class="signature" style="width: 300px; height: auto;">
    </div>
@endif

@if(!empty($ewayBillImg))
    <div style="margin-top: 20px;">
        <p><strong>E-Way Bill Image:</strong></p>
        <img src="{{ $ewayBillImg }}" class="signature" style="width: 300px; height: auto;">
    </div>
@endif



       @if(!empty($incomingImages))
    <div style="margin-top: 20px; page-break-inside: avoid;">
        <p><strong>Attachments:</strong></p>
        <table style="width: 100%; border: none;">
            <tbody>
                @foreach(array_chunk($incomingImages, 2) as $row)
                    <tr>
                        @foreach($row as $img)
                            <td style="text-align: center; padding: 10px; border: none;">
                                <img src="{{ $img }}" class="signature" style="width: 200px; height: auto;">
                            </td>
                        @endforeach
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div style="text-align: left; margin-top: 20px;">
        <p><strong>Attachment:</strong> Not available</p>
    </div>
@endif

    </div>
</body>
</html>
