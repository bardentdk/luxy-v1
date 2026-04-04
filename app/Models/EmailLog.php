<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'crm_contact_id', 'deal_id', 'quote_id', 'sequence_step_id',
        'to_email', 'to_name', 'subject', 'body_html',
        'status', 'brevo_message_id', 'sent_at', 'opened_at',
    ];

    protected $casts = [
        'sent_at'   => 'datetime',
        'opened_at' => 'datetime',
    ];

    public function contact(): BelongsTo     { return $this->belongsTo(CrmContact::class, 'crm_contact_id'); }
    public function deal(): BelongsTo        { return $this->belongsTo(Deal::class); }
    public function quote(): BelongsTo       { return $this->belongsTo(Quote::class); }
    public function sequenceStep(): BelongsTo{ return $this->belongsTo(EmailSequenceStep::class, 'sequence_step_id'); }
}