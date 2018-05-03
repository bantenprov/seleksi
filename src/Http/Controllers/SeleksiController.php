<?php

namespace Bantenprov\Seleksi\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/* Models */
use Bantenprov\Seleksi\Models\Bantenprov\Seleksi\Seleksi;
use Bantenprov\Pendaftaran\Models\Bantenprov\Pendaftaran\Pendaftaran;
use Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa;
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;
use App\User;

/* Etc */
use Validator;

/**
 * The SeleksiController class.
 *
 * @package Bantenprov\Seleksi
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class SeleksiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $pendaftaran;
    protected $seleksi;
    protected $siswa;
    protected $nilai;
    protected $user;

    public function __construct(Seleksi $seleksi, Pendaftaran $pendaftaran, User $user, Siswa $siswa, Nilai $nilai)
    {
        $this->seleksi        = $seleksi;
        $this->pendaftaran    = $pendaftaran;
        $this->user           = $user;
        $this->siswa          = $siswa;
        $this->nilai          = $nilai;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (request()->has('sort')) {
            list($sortCol, $sortDir) = explode('|', request()->sort);

            $query = $this->seleksi->with('pendaftaran')->with('user')->with('siswa')->with('nilai')->orderBy($sortCol, $sortDir);
        } else {
            $query = $this->seleksi->with('pendaftaran')->with('user')->with('siswa')->with('nilai')->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('nilai_id', 'like', $value)
                    ->orWhere('pendaftaran_id', 'like', $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $response = $query->paginate($perPage);

        return response()->json($response)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [];

        $pendaftarans = $this->pendaftaran->all();
        $siswas = $this->siswa->all();
        $nilais = $this->nilai->all();
        $users_special = $this->user->all();
        $users_standar = $this->user->find(\Auth::User()->id);
        $current_user = \Auth::User();

        $role_check = \Auth::User()->hasRole(['superadministrator','administrator']);

        if($role_check){
            $response['user_special'] = true;
            foreach($users_special as $user){
                array_set($user, 'label', $user->name);
            }
            $response['user'] = $users_special;
        }else{
            $response['user_special'] = false;
            array_set($users_standar, 'label', $users_standar->name);
            $response['user'] = $users_standar;
        }

        array_set($current_user, 'label', $current_user->name);

        foreach($pendaftarans as $pendaftaran){
            array_set($pendaftaran, 'label', $pendaftaran->kegiatan->label);
        }


        foreach($nilais as $nilai){
            array_set($nilai, 'label', $nilai->siswa->nomor_un.' - '.$nilai->siswa->nama_siswa);
        }

        $response['current_user'] = $current_user;
        $response['pendaftaran'] = $pendaftarans;
        $response['siswa'] = $siswas;
        $response['nilai'] = $nilais;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seleksi  $seleksi
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seleksi = $this->seleksi;
        $current_user_id = $request->user_id;

        $validator = Validator::make($request->all(), [
            'pendaftaran_id' => 'required',
            'user_id' => 'required',
            'nilai_id' => 'required|unique:seleksis,nilai_id',
            'nomor_un' => 'required|unique:seleksis,nomor_un'
        ]);

        if($validator->fails()){

            $check = $seleksi->Where('nilai_id', $request->nilai_id)->orWhere('nomor_un', $request->nomor_un)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed,  Nama Siswa already exists';
            } else {
                $seleksi->pendaftaran_id = $request->input('pendaftaran_id');
                $seleksi->user_id = $current_user_id;
                $seleksi->nomor_un = $request->input('nomor_un');
                $seleksi->nilai_id = $request->input('nilai_id');
                $seleksi->save();                

                $response['message'] = 'success';
            }
        } else {
            $seleksi->pendaftaran_id = $request->input('pendaftaran_id');
            $seleksi->user_id = $current_user_id;
            $seleksi->nomor_un = $request->input('nomor_un');
            $seleksi->nilai_id = $request->input('nilai_id');
            $seleksi->save();
            $response['message'] = 'success';
        }

        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seleksi = $this->seleksi->findOrFail($id);

        $response['seleksi'] = $seleksi;
        $response['pendaftaran'] = $seleksi->pendaftaran;
        $response['user'] = $seleksi->user;
        $response['siswa'] = $seleksi->siswa;
        $response['nilai'] = $seleksi->nilai;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Seleksi  $seleksi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seleksi = $this->seleksi->with(['pendaftaran', 'nilai', 'siswa', 'user'])->findOrFail($id);


        array_set($seleksi->user, 'label', $seleksi->user->name);
        array_set($seleksi->pendaftaran, 'label', $seleksi->pendaftaran->kegiatan->label);
             
        
            /*array_set($seleksi->nilai, 'label', $seleksi->nilai->nomor_un.' - '.$seleksi->nilai->siswa->nama_siswa);*/
    


        $response['seleksi'] = $seleksi;
        $response['pendaftaran'] = $seleksi->pendaftaran;
        $response['user'] = $seleksi->user;
        $response['siswa'] = $seleksi->siswa;
        $response['nilai'] = $seleksi->nilai;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seleksi  $seleksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $response = array();
        $message  = array();
        $seleksi = $this->seleksi->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'pendaftaran_id' => 'required',
                'nomor_un' => 'required|unique:seleksis,nomor_un,'.$id,
                'nilai_id' => 'required|unique:seleksis,nilai_id,'.$id,
            ]);

        if ($validator->fails()) {

            foreach($validator->messages()->getMessages() as $key => $error){
                        foreach($error AS $error_get) {
                            array_push($message, $error_get);
                        }
                    }

             $check_siswa = $this->seleksi->where('id','!=', $id)->where('nomor_un', $request->nomor_un);
             $check_nilai = $this->seleksi->where('id','!=', $id)->where('nilai_id', $request->nilai_id);

             if($check_siswa->count() > 0 || $check_nilai->count() > 0){
                  $response['message'] = implode("\n",$message);
            } else {
                $seleksi->user_id = $request->input('user_id');
                $seleksi->pendaftaran_id = $request->input('pendaftaran_id');
                $seleksi->nomor_un = $request->input('nomor_un');
                $seleksi->nilai_id = $request->input('nilai_id');
                $seleksi->save();

                $response['message'] = 'success';
            }
        } else {
            $seleksi->user_id = $request->input('user_id');
                $seleksi->pendaftaran_id = $request->input('pendaftaran_id');
                $seleksi->nomor_un = $request->input('nomor_un');
                $seleksi->nilai_id = $request->input('nilai_id');
                $seleksi->save();

            $response['message'] = 'success';
        }

        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seleksi  $seleksi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seleksi = $this->seleksi->findOrFail($id);

        if ($seleksi->delete()) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        return json_encode($response);
    }
}