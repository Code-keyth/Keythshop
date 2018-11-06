<?php
/**
 * Created by PhpStorm.
 * User: keyth
 * Date: 18-9-17
 * Time: 下午3:55
 */

namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table='keyth_member';
    protected $dateFormat='U';
}