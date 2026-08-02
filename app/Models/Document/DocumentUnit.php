<?php

namespace App\Models\Document;

use App\Models\Listening\Listening;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUnit extends Model
{
    use HasFactory;

    protected $table = 'document_units';

    protected $fillable = ['part_id', 'name', 'order', 'description'];

    protected $casts = ['order' => 'integer'];

    // =========================================================
    // Relationships
    // =========================================================

    public function part()
    {
        return $this->belongsTo(DocumentPart::class, 'part_id');
    }

    public function listenings()
    {
        return $this->hasMany(Listening::class, 'unit_id');
    }
}
