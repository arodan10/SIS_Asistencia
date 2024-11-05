<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Member extends Model
{
    use HasFactory;
    use HasRoles;
    protected $guarded = ['id'];
    protected $guard_name = 'web';

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    //Query scopes
    public function scopeActive($query,$status){
        //dd($status);
        if($status="Seleccione estado"){
            return $query->whereYear('active',$status);
        }
    }
}
