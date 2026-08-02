<?php

namespace App\Models\Hierarchy;

use Illuminate\Database\Eloquent\Model;

class HierarchyTree extends Model
{
    protected $table = 'hierarchy_trees';

    protected $fillable = [
        'name',
        'code',
        'module',
        'description',
    ];

    /**
     * Get all nodes in this tree.
     */
    public function nodes()
    {
        return $this->hasMany(HierarchyNode::class, 'tree_id')->orderBy('order', 'asc');
    }

    /**
     * Get only the top-level (root) nodes in this tree.
     */
    public function rootNodes()
    {
        return $this->hasMany(HierarchyNode::class, 'tree_id')
            ->whereNull('parent_id')
            ->orderBy('order', 'asc');
    }

    /**
     * Get all version snapshots of this tree.
     */
    public function versions()
    {
        return $this->hasMany(HierarchyVersion::class, 'tree_id')->orderBy('version_number', 'desc');
    }
}
