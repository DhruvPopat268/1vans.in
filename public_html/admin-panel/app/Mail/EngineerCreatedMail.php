<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Project;

class EngineerCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $projectNames;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;

         // Decode project_id JSON and get names
        $projectIds = json_decode($user->project_id, true); // ["1","2"]
        if(!empty($projectIds)){
            $this->projectNames = Project::whereIn('id', $projectIds)->pluck('project_name')->toArray();
        } else {
            $this->projectNames = [];
        }
       
    }

    public function build()
    {
        return $this->subject('🔑 1 Vans Login Credentials')
                    ->markdown('email.engineer')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'projectNames' => $this->projectNames
                       
                    ]);
    }
}
