<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class EngineerNotificationService extends Notification
{
    use Queueable;

     protected $appId;
    protected $apiKey;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // Set your API key here or retrieve it from your .env file
        $this->apiKey = 'os_v2_app_66bnm6h7frd5he6jqqgiuozgqp7eaddocn6u3tnwlbhffnw5bh7w7esber2swynql3vo665dw4v57shfmh3ox4w5ykcjmv22tyncaba';
        $this->appId = 'f782d678-ff2c-47d3-93c9-840c8a3b2683'; // Use your actual app ID
    }

    public function send(array $data)
    {
        // Include FCM tokens if provided
        $tokens = $data['tokens'] ?? [];

        \Log::info("Sending notification with tokens: " . implode(', ', $tokens));
        \Log::info("Using API Key: " . $this->apiKey); // For debugging

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => $this->appId,
            'include_player_ids' => $tokens,
            'headings' => ['en' => $data['title']],
            'contents' => ['en' => $data['message']],
        ]);

        Log::info("Response: " . $response->body());

        if ($response->successful()) {
            \Log::info("OneSignal notification sent successfully: " . $response->body());
        } else {
            \Log::error("Failed to send OneSignal notification: " . $response->body());
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
