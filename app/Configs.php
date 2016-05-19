<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Configs extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'configs';

    public function get_all_configs() {
        return DB::table($this->table)->get();
    }

}
