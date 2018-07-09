<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Role[] $roles
 */
class Permission extends EntrustPermission
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
}
