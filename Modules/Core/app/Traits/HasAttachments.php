<?php
namespace Modules\Core\Traits;

use App\Modules\Core\Models\Attachment;

trait HasAttachments
{
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function attachmentsByType(string $type)
    {
        return $this->attachments()->where('type', $type);
    }
}