<?php

namespace Bantenprov\Seleksi\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/* Models */
use Bantenprov\Seleksi\Models\Bantenprov\Seleksi\Seleksi;
use Bantenprov\Kegiatan\Models\Bantenprov\Kegiatan\Kegiatan;
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
    protected $kegiatanModel;
    protected $seleksi;
    protected $user;

    public function __construct(Seleksi $seleksi, Kegiatan $kegiatan, User $user)
    {
        $this->seleksi      = $seleksi;
        $this->kegiatanModel    = $kegiatan;
        $this->user             = $user;
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

            $query = $this->seleksi->with('kegiatan')->with('user')->orderBy($sortCol, $sortDir);
        } else {
            $query = $this->seleksi->with('kegiatan')->with('user')->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('tanggal_seleksi', 'like', $value)
                    ->orWhere('tanggal_seleksi', 'like', $value);
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

        $kegiatan = $this->kegiatanModel->all();
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

        $response['current_user'] = $current_user;
        $response['kegiatan'] = $kegiatan;
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
        $current_user_id = \Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'kegiatan_id' => 'required',
            'user_id' => 'required|max:16|unique:seleksis,user_id',
            'tanggal_seleksi' => 'required',
        ]);

        if($validator->fails()){

            $check = $seleksi->where('user_id', $current_user_id)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed, User already exists';
            } else {
                $seleksi->kegiatan_id = $request->input('kegiatan_id');
                $seleksi->user_id = $current_user_id;
                $seleksi->tanggal_seleksi = $request->input('tanggal_seleksi');
                $seleksi->save();

                $response['message'] = 'success';
            }
        } else {
            $seleksi->kegiatan_id = $request->input('kegiatan_id');
            $seleksi->user_id = $current_user_id;
            $seleksi->tanggal_seleksi = $request->input('tanggal_seleksi');
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
        $response['kegiatan'] = $seleksi->kegiatan;
        $response['user'] = $seleksi->user;
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
        $seleksi = $this->seleksi->findOrFail($id);

        array_set($seleksi->user, 'label', $seleksi->user->name);

        $response['seleksi'] = $seleksi;
        $response['kegiatan'] = $seleksi->kegiatan;
        $response['user'] = $seleksi->user;
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
        $seleksi = $this->seleksi->findOrFail($id);

        if ($request->input('old_user_id') == $request->input('user_id'))
        {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'kegiatan_id' => 'required',
                'tanggal_seleksi' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|unique:seleksis,user_id',
                'kegiatan_id' => 'required',
                'tanggal_seleksi' => 'required',
            ]);
        }

        if ($validator->fails()) {
            $check = $seleksi->where('user_id',$request->user_id)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed, username ' . $request->user_id . ' already exists';
            } else {
                $seleksi->user_id = $request->input('user_id');
                $seleksi->kegiatan_id = $request->input('kegiatan_id');
                $seleksi->tanggal_seleksi = $request->input('tanggal_seleksi');
                $seleksi->save();

                $response['message'] = 'success';
            }
        } else {
            $seleksi->user_id = $request->input('user_id');
                $seleksi->kegiatan_id = $request->input('kegiatan_id');
                $seleksi->tanggal_seleksi = $request->input('tanggal_seleksi');
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
