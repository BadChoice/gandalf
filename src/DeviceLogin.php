<?php namespace BadChoice\Gandalf;

use Illuminate\Database\Eloquent\Model;
class DeviceLogin extends Model{

    protected $table    = "device_logins";
    protected $guarded  = ['id'];
}