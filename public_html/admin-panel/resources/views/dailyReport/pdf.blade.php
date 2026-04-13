<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Construction Daily Work Progress Report</title>
      <style>
         body { font-family: sans-serif; font-size: 12px; }
         table { width: 100%; border-collapse: collapse; margin-top: 10px; }
         th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #a5a6a7; color:white; }
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
         .table tbody tr:nth-child(odd) {
    background-color: #fbfafa;
}

.table tbody tr:nth-child(even) {
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
    margin-top:-20px;
}

        
         .header-info {
         text-align: center;
         margin-top: 20px;
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
           <h3>{{ $report->project->company_name ?? 'N/A' }}</h3>
            <p>{{ $report->project->project_name ?? 'N/A' }}
            </p>
            <p>{{ $report->project->project_number ?? 'N/A' }}
            </p>
           <p>
                {!! nl2br(e(wordwrap($report->project->site_address ?? 'N/A', 40, "\n", true))) !!}
            </p>

         </div>
      

      </div>
      <div class="content">
             <div class="header-info">
            <h2>
            CONSTRUCTION DAILY WORK PROGRESS REPORT
            </h2>
            
        </div>
         <div class="walkaround-info-box">
            
            <table>
            <tr>
                <th colspan="2" style="text-align:center; background:#33337f; color:white;">
                    WORK REPORT
                </th>
            </tr>
                <tr>
                  <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                  <td><strong>Work Location:</strong> {{ $report->location ?? 'N/A' }}</td>

               </tr>
                <tr>
                  <td><strong>Weather:</strong> {{ $report->weather ?? 'N/A' }}</td>
                  <td>
    <strong>Work Measurements:</strong>
    @if($report->measurements && $report->measurements->isNotEmpty())
        
        @php 
            $value = $report->measurements[0]->mesurements_value ?? 0;
            $unit  = $report->nameOfWork->mesurementsubAttribute->name ?? '-';
        @endphp

        @if($value == 0)
            <span>Working in Progress</span>
        @else
            {{ $value }} {{ $unit }}
        @endif

    @else
        <p>No Measurement Found</p>
    @endif
</td>

               </tr>
               <tr>
                   <td><strong>Types Of Work</strong> : {{ $report->mainCategory->name ?? '-' }}</td>
                  <td><strong>Comment:</strong> {{ $report->comment }}</td>
                  
               </tr>
               <tr>
                  <td><strong>Work:</strong> {{ $report->nameOfWork->name ?? '-' }}</td>
                  <!--<td><strong>At:</strong> {{ $report->at }}</td>-->
                   <td></td>
                 
               </tr>
               
               
                <tr>
                  <td><strong>Work Area:</strong> {{ $report->wing->name ?? '-' }}</td>
                  <td></td>
               </tr>
               <tr>
                  <td><strong>Work Section:</strong> {{ $report->flour->name ?? '-' }} </td>
                 <td></td>
               </tr>
               <tr>
                  <td colspan="2"><strong>Description:</strong> {{ $report->description }}</td>
                 
               </tr>
            
            </table>
         </div>
         <div class="walkaround-info-box">
            <table class="table">
               <thead>
               <tr>
                <th colspan="2" style="text-align:center; background:#33337f; color:white;">
                    MAN POWER
                </th>
            </tr>
                  <tr>
                     <th>Name</th>
                     <th>Used Person</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($report->manpowers as $stock)
                  <tr>
                     <td>{{ $stock->manPower->name ?? 'N/A' }}</td>
                     <td>{{ $stock->total_person }}</td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="3">No Man Power Found</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
         <div class="walkaround-info-box">
          
            <table class="table">
               <thead>
               <tr>
                <th colspan="3" style="text-align:center; background:#33337f; color:white;">
                    MATERIAL USED
                </th>
            </tr>
                  <tr>
                     <th>Category</th>
                     <th>Sub Category</th>
                     <th>Used Stock</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($report->materials as $material)
                  <tr>
                     <td>{{ $material->subCategory->category->name ?? 'N/A' }}</td>
                     <td>{{ $material->subCategory->name ?? 'N/A' }}</td>
                     <td>{{ $material->used_stock ?? '0' }} {{ $material->subCategory->attribute->name ?? 'N/A' }}</td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="3">No Material Found</td>
                  </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
         <!--<div class="walkaround-info-box">-->
         <!--   <h4>Measurements</h4>-->
         <!--   <table class="table">-->
         <!--      <thead>-->
         <!--         <tr>-->
         <!--            <th>Measurement Value</th>-->
         <!--            <th>Attribute</th>-->
         <!--         </tr>-->
         <!--      </thead>-->
         <!--      <tbody>-->
         <!--         @forelse($report->measurements as $measure)-->
         <!--         <tr>-->
         <!--            <td>{{ $measure->mesurements_value ?? '-' }}</td>-->
         <!--            <td>{{ $measure->attribute->name ?? '-' }}</td>-->
         <!--         </tr>-->
         <!--         @empty-->
         <!--         <tr>-->
         <!--            <td colspan="2">No Measurements Found</td>-->
         <!--         </tr>-->
         <!--         @endforelse-->
         <!--      </tbody>-->
         <!--   </table>-->
         <!--</div>-->
         <div class="walkaround-info-box">
            
            <table class="table">
               <thead>
               <tr>
                <th colspan="4" style="text-align:center; background:#33337f; color:white;">
                    EQUIPMENTS
                </th>
            </tr>
                  <tr>
                     <th>Equipment Name</th>
                     <th>Total Hours</th>
                     <th>Rate</th>
                     <th>Total Amount</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($report->equipments as $equipment)
                  <tr>
                     <td>{{ $equipment->equipment->name ?? 'N/A' }}</td>
                     <td>
                        @php
                        $hours = floor($equipment->total_hours);
                        $minutes = round(($equipment->total_hours - $hours) * 60);
                        @endphp
                        {{ $hours }} Hours {{ $minutes }} Minutes
                     </td>
                     <td>{{ $equipment->rate ?? 'N/A' }}</td>
                     <td>{{ $equipment->total_amount ?? 'N/A' }}</td>
                  </tr>
                  @empty
                  <tr>
                     <td colspan="4">No Equipment Found</td>
                  </tr>
                  @endforelse
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
         @if(!empty($Images))
         <div style="margin-top: 20px;page-break-inside: avoid;">
            <p><strong>Attachments:</strong></p>
            <table style="width: 100%; border: none;">
               <tbody>
                  @foreach(array_chunk($Images, 3) as $row)
                  <tr>
                     @foreach($row as $img)
                     <td style="text-align: center; padding: 10px; border: none;">
                        <img src="{{ $img }}" class="signature" style="width: 200px; height: auto;">
                     </td>
                     @endforeach
                     @if(count($row) < 3)
                     @endif
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
