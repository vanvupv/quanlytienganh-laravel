<?php

namespace App\Models\Document;

use App\Models\Listening\Listening;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentPart extends Model
{
    use HasFactory;

    protected $table = 'document_parts';

    protected $fillable = ['document_id', 'name', 'order', 'description'];

    protected $casts = ['order' => 'integer'];

    // =========================================================
    // Relationships
    // =========================================================

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function units()
    {
        return $this->hasMany(DocumentUnit::class, 'part_id')->orderBy('order');
    }

    /** File nghe trực tiếp thuộc chương này */
    public function listenings()
    {
        return $this->hasMany(Listening::class, 'chapter_id');
    }

    // =========================================================
    // Accessors
    // =========================================================

    /** Tổng số file nghe trong chương (qua units) */
    public function getListeningCountAttribute(): int
    {
        return $this->listenings()->count()
            + $this->units->sum(fn($u) => $u->listenings()->count());
    }

    /** Tổng thời gian nghe của chương (giây) */
    public function getTotalDurationAttribute(): int
    {
        $direct = $this->listenings()->sum('duration') ?? 0;
        $viaUnits = $this->units->sum(fn($u) => $u->listenings()->sum('duration') ?? 0);
        return $direct + $viaUnits;
    }
}
