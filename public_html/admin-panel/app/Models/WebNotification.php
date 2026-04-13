<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebNotification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'project_id',
        'engineer_id',
        'title',
    'message',
    'status',
    'key',
    'report_id'
    ];
    
public function formattedMessage()
{
    $formatted = preg_replace_callback('/(Task|Description)\s*-\s*/i', function ($matches) {
        return '<strong>' . $matches[1] . '</strong> - ';
    }, $this->message);

    // Remove extra line breaks and replace them with a single <br>
    $formatted = preg_replace("/\r\n|\r|\n/", '<br>', trim($formatted));

    return $formatted;
}


}
