<?php

namespace App\Notifications;

use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;

class RSSFeedReportNotification extends Notification
{
    use Queueable;

    protected $feed;
    protected $count;
    protected $feedResult;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Feed $feed, int $count, Collection $feedResult)
    {
        $this->feed = $feed;
        $this->count = $count;
        $this->feedResult = $feedResult;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('RSS Feed report')
            ->markdown('mail.rss.report', [
                'feed' => $this->feed,
                'count' => $this->count,
                'feedResult' => $this->feedResult
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
