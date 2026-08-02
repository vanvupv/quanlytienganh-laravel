<?php

namespace App\Models\Hierarchy;

use Illuminate\Database\Eloquent\Model;

class HierarchyVersion extends Model
{
    protected $fillable = [
        'tree_id',
        'version_number',
        'note',
        'nodes_snapshot',
    ];

    protected $casts = [
        'nodes_snapshot' => 'array',
    ];

    /**
     * Get the tree that owns the version.
     */
    public function tree()
    {
        return $this->belongsTo(HierarchyTree::class, 'tree_id');
    }
}
