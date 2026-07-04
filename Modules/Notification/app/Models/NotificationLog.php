<?php
namespace Modules\Notification\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'notification_id',
        'channel',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function notification()
    {
        return $this->belongsTo(\Illuminate\Notifications\DatabaseNotification::class);
    }
}