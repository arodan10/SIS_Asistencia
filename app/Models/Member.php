<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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
