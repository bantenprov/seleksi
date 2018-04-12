<?php

namespace Bantenprov\Seleksi\Models\Bantenprov\Seleksi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seleksi extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'seleksis';
    protected $dates = [
        'deleted_at'
    ];
    protected $fillable = [
        'pendaftaran_id',
        'user_id',
        'tanggal_seleksi',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo('Bantenprov\Pendaftaran\Models\Bantenprov\Pendaftaran\Pendaftaran','pendaftaran_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
