<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Configs extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'configs';
    public $timestamps = true;

    public function get_all_configs() {
        return DB::table($this->table)->get();
    }

    public function get_gcm_config() {
        return DB::table($this->table)->where("name", "=", GOOGLE_API_KEY)->first();
    }

    public function get_gcm_sender_id() {
        return DB::table($this->table)->where("name", "=", GOOGLE_SENDER_ID)->first();
    }

}
