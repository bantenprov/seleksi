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
        'kegiatan_id',
        'user_id',
        'tanggal_seleksi',
    ];

    public function kegiatan()
    {
        return $this->belongsTo('Bantenprov\Kegiatan\Models\Bantenprov\Kegiatan\Kegiatan','kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
