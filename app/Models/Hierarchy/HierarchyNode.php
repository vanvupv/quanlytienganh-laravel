<?php

namespace App\Models\Hierarchy;

use Illuminate\Database\Eloquent\Model;

class HierarchyNode extends Model
{
    protected $table = 'hierarchy_nodes';

    protected $fillable = [
        'tree_id',
        'parent_id',
        'title',
        'icon',
        'nodeable_type',
        'nodeable_id',
        'order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the tree this node belongs to.
     */
    public function tree()
    {
        return $this->belongsTo(HierarchyTree::class, 'tree_id');
    }

    /**
     * Get the parent node of this node.
     */
    public function parent()
    {
        return $this->belongsTo(HierarchyNode::class, 'parent_id');
    }

    /**
     * Get the child nodes of this node.
     */
    public function children()
    {
        return $this->hasMany(HierarchyNode::class, 'parent_id')->orderBy('order', 'asc');
    }

    /**
     * Get the target linked model (Polymorphic).
     */
    public function nodeable()
    {
        return $this->morphTo();
    }

    /**
     * Helper to check if this node is a static folder / text (not linked to any database model).
     */
    public function isStatic()
    {
        return is_null($this->nodeable_type);
    }
}
