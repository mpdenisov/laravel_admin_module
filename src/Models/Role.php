<?php

namespace Rhinoda\Admin\Models;

use Zizaco\Entrust\EntrustRole;
use App\Models\Menu;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Permission[] $permissions
 * @property User[] $users
 */
class Role extends EntrustRole
{

    const ADMIN = 'admin';
    const HOST = 'host';
    const CLIENT = 'client';

    /**
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public $relation_ids = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function canAccessMenu($menu)
    {
        if ($menu instanceof Menu) {
            $menu = $menu->id;
        }

        if (! isset($this->relation_ids['menus'])) {
            $this->relation_ids['menus'] = $this->menus()->pluck('id')->flip()->all();
        }

        return isset($this->relation_ids['menus'][$menu]);
    }
}
