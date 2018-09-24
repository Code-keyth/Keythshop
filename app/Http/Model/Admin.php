<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:57
 */

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table='keyth_admin_user';
    protected $dateFormat = 'U';
}