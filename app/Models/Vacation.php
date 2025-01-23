<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class Vacation extends Model
{

    // Establecer los campos que se pueden asignar en masa
    protected $fillable = [
        'applicant_id',"approver_id", 'message_applicant', 'message_approver',"status",'start_date', 'end_date'
    ];

    // Accesor para la fecha de inicio
    public function getStartDateFormattedAttribute()
    {
        return Carbon::parse($this->start_date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
    }

    // Accesor para la fecha de fin
    public function getEndDateFormattedAttribute()
    {
        return Carbon::parse($this->end_date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
    }

    // Relación con el modelo de Usuario para el applicant
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    // Relación con el modelo de Usuario para el approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
