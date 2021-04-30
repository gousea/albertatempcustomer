<?php

namespace App\Http\Controllers;

use App\Model\UserDynamic;
use App\User;
use App\Model\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Model\Userpermission;
use Illuminate\Support\Facades\Auth;
use App\Model\Permissiongroup;
use App\Model\Userpermissiongroup;
use Illuminate\Auth\Access\Gate;


class AllUserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $users = UserDynamic::orderBy('iuserid', 'DESC')->paginate(20);

        return view('User.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $mstPermissiongroup = Permissiongroup::all();
        return view('User.create', compact('permissions', 'mstPermissiongroup'));
    }
   

    public function encodePassword($pass_string)
    {
        $encdata = urlencode($pass_string);
        $en_pass = base64_encode($encdata);

        return $en_pass;
    }

    //user creation function
    public function store(Request $request)
    {
        $input = $request->all();
        $sid = session()->get('sid');
        if (isset($input['vuserid'])) {
            $duplicateUserid = UserDynamic::where('vuserid', '=', $input['vuserid'])->get();
        }
        if (isset($input['vemail'])) {
            $duplicateEmail = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Active']])->get();
            $duplicateMstuser = UserDynamic::where('vemail', '=', $input['vemail'])->get();
        }

        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
            return redirect('users/create')
                ->withErrors("Pos User id is already exists.")
                ->withInput($input);
        } elseif ((isset($duplicateEmail) && count($duplicateEmail) > 0) || (isset($duplicateMstuser) && count($duplicateMstuser) > 0)) {
            // dd("hi");
            return redirect('users/create')
                ->withErrors("User Email is already exists.")
                ->withInput($input);

        } else {
            //checkwether pos user or (Mobile & Web) user
            if(isset($input['web']) == 'web'){
                $web_user = 'Y';
            }else {
                $web_user = 'N';
            }
            
            if(isset($input['lb']) == 'lb'){
                $lb_user = 'Y';
            }else {
                $lb_user = 'N';
            }
            if(isset($input['mob']) == 'mob'){
                $mob_user = 'Y';
            }else {
                $mob_user = 'N';
            }
            if(isset($input['pos']) == 'pos'){
                $pos_user = 'Y';
            }else {
                $pos_user = 'N';
            }

            if ($pos_user === 'Y' && $web_user === 'N' && $mob_user === 'N' && $lb_user ==='N') {
                $encdoe_password = $this->encodePassword($input['vpassword']);
                                
                $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                $res =DB::connection('mysql')->select($sql);
                    
                if(count($res) > 0){
                    $mst_user = UserDynamic::create([
                        'mob_user'  => $mob_user,
                        'web_user'  => $web_user,
                        'pos_user'  => $pos_user,
                        'lb_user'  => $lb_user,
                        'vfname'    => $input['vfname'],
                        'vlname'    => $input['vlname'],
                        'vaddress1' => $input['vaddress1'],
                        'vaddress2' => $input['vaddress2'],
                        'vcity'     => $input['vcity'],
                        'vstate'    => $input['vstate'],
                        'vzip'      => $input['vzip'],
                        'vcountry'  => $input['vcountry'],
                        'vphone'    => $input['vphone'],
                        'vuserid'   => $input['vuserid'],
                        'vpassword' => $encdoe_password,
                        'vusertype' => $input['vusertype'],
                        'vpasswordchange' => "No",
                        'vuserbarcode' => $input['vuserbarcode'],
                        'estatus'   =>  $input['estatus'],
                        'SID'       =>  session()->get('sid'),
                    ]);

                    User::create([
                        'mob_user'  => $mob_user,
                        'web_user'  => $web_user,
                        'lb_user'  => $lb_user,
                        'fname'     => $input['vfname'],
                        'lname'     => $input['vlname'],
                        'user_role' => $input['vusertype'],
                        'iuserid'   => $mst_user->iuserid,
                        'estatus'   => $input['estatus'],
                        'sid'       =>  session()->get('sid'),
                    ]);
                }else{
                     $mst_user = UserDynamic::create([
                        'mob_user'  => $mob_user,
                        'web_user'  => $web_user,
                        'pos_user'  => $pos_user,
                        'vfname'    => $input['vfname'],
                        'vlname'    => $input['vlname'],
                        'vaddress1' => $input['vaddress1'],
                        'vaddress2' => $input['vaddress2'],
                        'vcity'     => $input['vcity'],
                        'vstate'    => $input['vstate'],
                        'vzip'      => $input['vzip'],
                        'vcountry'  => $input['vcountry'],
                        'vphone'    => $input['vphone'],
                        'vuserid'   => $input['vuserid'],
                        'vpassword' => $encdoe_password,
                        'vusertype' => $input['vusertype'],
                        'vpasswordchange' => "No",
                        'vuserbarcode' => $input['vuserbarcode'],
                        'estatus'   =>  $input['estatus'],
                        'SID'       =>  session()->get('sid'),
                    ]);

                    User::create([
                        'mob_user'  => $mob_user,
                        'web_user'  => $web_user,
                        'fname'     => $input['vfname'],
                        'lname'     => $input['vlname'],
                        'user_role' => $input['vusertype'],
                        'iuserid'   => $mst_user->iuserid,
                        'estatus'   => $input['estatus'],
                        'sid'       =>  session()->get('sid'),
                    ]);
                }

               
            } else {
                $Email = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->get();
                if (count($Email) > 0) {
                    $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_BCRYPT);
                    $encdoe_password = $this->encodePassword($input['vpassword']);
                    
                    $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                    $res =DB::connection('mysql')->select($sql);
                    
                    if(count($res) > 0){
                        $mst_user = UserDynamic::create([
                        'mob_user'  => $mob_user,
                        'web_user'  => $web_user,
                        'pos_user'  => $pos_user,
                        'lb_user'  => $lb_user,
                        'vfname'    => $input['vfname'],
                        'vlname'    => $input['vlname'],
                        'vaddress1' => $input['vaddress1'],
                        'vaddress2' => $input['vaddress2'],
                        'vcity'     => $input['vcity'],
                        'vstate'    => $input['vstate'],
                        'vzip'      => $input['vzip'],
                        'vcountry'  => $input['vcountry'],
                        'vphone'    => $input['vphone'],
                        'vuserid'   => $input['vuserid'],
                        'vpassword' => $encdoe_password,
                        'vusertype' => $input['vusertype'],
                        'vpasswordchange' => "No",
                        'vuserbarcode' => $input['vuserbarcode'],
                        'estatus'   =>  $input['estatus'],
                        'SID'       =>  session()->get('sid'),
                        'mwpassword' => $encdoe_mwpassword,
                        'vemail' => $input['vemail'],
                        ]);
                        User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'lb_user'  => $lb_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $mst_user->iuserid,
                            'estatus'   => $input['estatus'],
                            'sid'       =>  session()->get('sid'),
                            'password' => $encdoe_mwpassword,
                            'vemail' => $input['vemail'],
                        ]);
                    }else{
                        $mst_user = UserDynamic::create([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'pos_user'  => $pos_user,
                            'vfname'    => $input['vfname'],
                            'vlname'    => $input['vlname'],
                            'vaddress1' => $input['vaddress1'],
                            'vaddress2' => $input['vaddress2'],
                            'vcity'     => $input['vcity'],
                            'vstate'    => $input['vstate'],
                            'vzip'      => $input['vzip'],
                            'vcountry'  => $input['vcountry'],
                            'vphone'    => $input['vphone'],
                            'vuserid'   => $input['vuserid'],
                            'vpassword' => $encdoe_password,
                            'vusertype' => $input['vusertype'],
                            'vpasswordchange' => "No",
                            'vuserbarcode' => $input['vuserbarcode'],
                            'estatus'   =>  $input['estatus'],
                            'SID'       =>  session()->get('sid'),
                            'mwpassword' => $encdoe_mwpassword,
                            'vemail' => $input['vemail'],
                        ]);
                        User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $mst_user->iuserid,
                            'estatus'   => $input['estatus'],
                            'sid'       =>  session()->get('sid'),
                            'password' => $encdoe_mwpassword,
                            'vemail' => $input['vemail'],
                        ]); 
                    }
                    
                    
                } else {
                    $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_BCRYPT);
                    $encdoe_password = $this->encodePassword($input['vpassword']);
                    $sid = session()->get('sid');
                    $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                    $res =DB::connection('mysql')->select($sql);
                    
                    if(count($res) > 0){
                            $mst_user = UserDynamic::create([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'pos_user'  => $pos_user,
                            'lb_user'  => $lb_user,
                            'vfname'    => $input['vfname'],
                            'vlname'    => $input['vlname'],
                            'vaddress1' => $input['vaddress1'],
                            'vaddress2' => $input['vaddress2'],
                            'vcity'     => $input['vcity'],
                            'vstate'    => $input['vstate'],
                            'vzip'      => $input['vzip'],
                            'vcountry'  => $input['vcountry'],
                            'vphone'    => $input['vphone'],
                            'vuserid'   => $input['vuserid'],
                            'vpassword' => $encdoe_password,
                            'vusertype' => $input['vusertype'],
                            'vpasswordchange' => "No",
                            'vuserbarcode' => $input['vuserbarcode'],
                            'estatus'   =>  $input['estatus'],
                            'SID'       =>  session()->get('sid'),
                            'mwpassword' => $encdoe_mwpassword,
                            'vemail' => $input['vemail'],
                        ]);
                    
    
                        $main = User::create([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'lb_user'  => $lb_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $mst_user->iuserid,
                            'estatus'   => $input['estatus'],
                            'sid'       =>  session()->get('sid'),
                            'password'  => $encdoe_mwpassword,
                            'vemail'    => $input['vemail'],
                        ]);
                    }else{
                            $mst_user = UserDynamic::create([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'pos_user'  => $pos_user,
                            'vfname'    => $input['vfname'],
                            'vlname'    => $input['vlname'],
                            'vaddress1' => $input['vaddress1'],
                            'vaddress2' => $input['vaddress2'],
                            'vcity'     => $input['vcity'],
                            'vstate'    => $input['vstate'],
                            'vzip'      => $input['vzip'],
                            'vcountry'  => $input['vcountry'],
                            'vphone'    => $input['vphone'],
                            'vuserid'   => $input['vuserid'],
                            'vpassword' => $encdoe_password,
                            'vusertype' => $input['vusertype'],
                            'vpasswordchange' => "No",
                            'vuserbarcode' => $input['vuserbarcode'],
                            'estatus'   =>  $input['estatus'],
                            'SID'       =>  session()->get('sid'),
                            'mwpassword' => $encdoe_mwpassword,
                            'vemail' => $input['vemail'],
                        ]);
                    
    
                        $main = User::create([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $mst_user->iuserid,
                            'estatus'   => $input['estatus'],
                            'sid'       =>  session()->get('sid'),
                            'password'  => $encdoe_mwpassword,
                            'vemail'    => $input['vemail'],
                        ]);
                    }
                    
                   
                }
            }
        }
        
        if(isset($input['permission'])){
            foreach ($input['permission'] as $permission) {
                Userpermission::create([
                    'status'        => 'Active',
                    'created_id'    => Auth::user()->iuserid,
                    'permission_id' => $permission,
                    'updated_id'    => Auth::user()->iuserid,
                    'userid'        => $mst_user->iuserid,
                    'SID'           => session()->get('sid')
                ]);
            }
        }

        $group = Permissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            if (count($que) > 0) {
                Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                Userpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }
        return redirect('users')->with('message', 'User Created Successfully');
    }



    public function edit(UserDynamic $userDynamic, $iuserid)
    {
        $permissions = Permission::all();
        $mstPermissiongroup = Permissiongroup::all();
        $user = UserDynamic::where('iuserid', '=', $iuserid)->get();
        $users = $user[0];
        // $checkedPermission = Userpermission::where('userid', '=', $iuserid)->get()->toArray();
        $checkedPermission = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup on mp.vpermissioncode = mup.permission_id where mup.status = 'Active' and mup.userid = ".$iuserid);
        $dataPerCheck = array();
        for ($i = 0; $i < count($checkedPermission); $i++) {
            $dataPerCheck[] = $checkedPermission[$i]->vpermissioncode;
        }
        return view('User.edit', compact('permissions', 'mstPermissiongroup', 'users', 'dataPerCheck'));
    }

    
    public function update(Request $request, UserDynamic $userDynamic, $iuserid)
    {
        $input = $request->all();
        session()->put('userInput', $input);
        $sid = session()->get('sid');
        $que = Userpermission::where('userid', '=', $iuserid)->get()->toArray();
        $list_of_permissions = array();
        for ($i = 0; $i < count($que); $i++) {
            $list_of_permissions[] = $que[$i]['permission_id'];
        }

        if(isset($input['permission'])){
            foreach ($input['permission'] as $permission) {
                if (in_array($permission, $list_of_permissions)) {
                    $get_id = Userpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
                    $mst_perm_id = $get_id[0]->Id;
                    Userpermission::where('Id', '=', $mst_perm_id)->update(['permission_id' => $permission, 'status' => 'Active']);
                } else {
                    Userpermission::create([
                        'status'        => 'Active',
                        'created_id'    => Auth::user()->iuserid,
                        'permission_id' => $permission,
                        'updated_id'    => Auth::user()->iuserid,
                        'userid'        => $iuserid,
                        'SID'           => session()->get('sid')
                    ]);
                }
                if (($k = array_search($permission, $list_of_permissions)) !== false) {
                    unset($list_of_permissions[$k]);
                }
            }
        }

        // for removing permissoion
        foreach ($list_of_permissions as $permission) {
            $get_id = Userpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
            // $mst_perm_id = $get_id[0]->Id;
            // Userpermission::where('Id', '=', $mst_perm_id)->update(['status' => 'Inactive']);
            foreach($get_id as $value){
                $mst_perm_id = $value->Id;
                Userpermission::where('Id', '=', $mst_perm_id)->update(['status' => 'Inactive']);
            }
        }

        $group = Permissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            if (count($que) > 0) {
                Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                Userpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }

        $mst_user = UserDynamic::where('iuserid', '=', $iuserid)->first();
        $store_mw_User = User::where('vemail', '=', $mst_user->vemail)->get();

        $db_mst_user = $mst_user->mwpassword;
        
        
        $dbvemail = $mst_user->vemail;
        $check_pwd_entered = 0;
        
        if (isset($input['mwpassword'])) {
            $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_DEFAULT);
            $check_pwd_entered = 1;
        } 

        // if (isset($input['vpassword'])) {
        //     $encdoe_password = $input['vpassword'];
        // } else {
        //     $encdoe_password = $db_mst_user;
        // }
        $user_id = $mst_user->iuserid;
        // check if the entered email is different from the one in db
        if ($mst_user->vemail != $input['vemail']) {
            $vemail = $input['vemail'];
            if (isset($input['vemail'])) {
                $duplicateEmail = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Active']])->get();
                $duplicateMstuser = UserDynamic::where('vemail', '=', $input['vemail'])->get();
            }
        } else {
            $vemail = $mst_user->vemail;
        }

        if (isset($input['vuserid']) && $mst_user->vuserid != $input['vuserid']) {
            $vuserid = $input['vuserid'];
            if (isset($input['vuserid'])) {
                $duplicateUserid = UserDynamic::where('vuserid', '=', $input['vuserid'])->get();
            }
        } else {
            $vuserid = $mst_user->vuserid;
        }


        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
             return redirect('users/'.$iuserid.'/edit')
                ->withErrors("Pos User id is already exists.")
                ->withInput($input);
        } elseif ((isset($duplicateEmail) && count($duplicateEmail) > 0) || (isset($duplicateMstuser) && count($duplicateMstuser) > 0)) {
            return redirect('users/'.$iuserid.'/edit')
                ->withErrors("User Email is already exists.")
                ->withInput($input);
        } else {
                if(isset($input['web']) == 'web'){
                    $web_user = 'Y';
                }else {
                    $web_user = 'N';
                }
                if(isset($input['lb']) == 'lb'){
                    $lb_user = 'Y';
                }else {
                    $lb_user = 'N';
                }
                if(isset($input['mob']) == 'mob'){
                    $mob_user = 'Y';
                }else {
                    $mob_user = 'N';
                }
                if(isset($input['pos']) == 'pos'){
                    $pos_user = 'Y';
                }else {
                    $pos_user = 'N';
                }
                $vemail = $input['vemail'];
                if($check_pwd_entered == 1){
                    
                    if (isset($input['vpassword'])) {
                        
                        $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                        $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'lb_user'  => $lb_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vpassword' => $this->encodePassword($input['vpassword']),
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'mwpassword' => $encdoe_mwpassword,
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                        }else{
                            $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'lb_user'  => $lb_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vpassword' => $this->encodePassword($input['vpassword']),
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'mwpassword' => $encdoe_mwpassword,
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                        }else{
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vpassword' => $this->encodePassword($input['vpassword']),
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'mwpassword' => $encdoe_mwpassword,
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                        }
                            
                        }
                        
                    }else {
                        
                        $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'lb_user'  => $lb_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'mwpassword' => $encdoe_mwpassword,
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                        }else{
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'mwpassword' => $encdoe_mwpassword,
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                            
                        }    
                        
                    }
                    
                }else{
                    if (isset($input['vpassword'])) {
                         $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'lb_user'  => $lb_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vpassword' => $this->encodePassword($input['vpassword']),
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');    
                        }else{
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vpassword' => $this->encodePassword($input['vpassword']),
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');    
                        }
                        
                    }else {
                         $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'lb_user'  => $lb_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                        }else{
                            UserDynamic::where('iuserid', '=', $iuserid)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'pos_user'  => $pos_user,
                                'vfname'    => $input['vfname'],
                                'vlname'    => $input['vlname'],
                                'vaddress1' => $input['vaddress1'],
                                'vaddress2' => $input['vaddress2'],
                                'vcity'     => $input['vcity'],
                                'vstate'    => $input['vstate'],
                                'vzip'      => $input['vzip'],
                                'vcountry'  => $input['vcountry'],
                                'vphone'    => $input['vphone'],
                                'vuserid'   => $vuserid,
                                'vusertype' => $input['vusertype'],
                                'vpasswordchange' => "No",
                                'vuserbarcode' => $input['vuserbarcode'],
                                'estatus'   =>  $input['estatus'],
                                'SID'       =>  session()->get('sid'),
                                'vemail' => $vemail,
                            ]);
                            session()->forget('userInput');
                        }
                        
                    }
                        
                    
                }
                
                
                if ($mst_user->vemail != $input['vemail']) {
                    $vemail = $input['vemail'];
                    
                    if($check_pwd_entered == 1){
                         $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                             $user = User::where('iuserid', '=', $user_id)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'lb_user'  => $lb_user,
                                'fname'     => $input['vfname'],
                                'lname'     => $input['vlname'],
                                'user_role' => $input['vusertype'],
                                'iuserid'   => $iuserid,
                                'estatus'   => $input['estatus'],
                                'password'  => $encdoe_mwpassword,
                                'vemail'    => $vemail,
                            ]); 
                            session()->forget('userInput');
                        }else{
                             $user = User::where('iuserid', '=', $user_id)->update([
                                'mob_user'  => $mob_user,
                                'web_user'  => $web_user,
                                'fname'     => $input['vfname'],
                                'lname'     => $input['vlname'],
                                'user_role' => $input['vusertype'],
                                'iuserid'   => $iuserid,
                                'estatus'   => $input['estatus'],
                                'password'  => $encdoe_mwpassword,
                                'vemail'    => $vemail,
                            ]); 
                            session()->forget('userInput');
                        }
                       
                    }else {
                         $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            $user = User::where('iuserid', '=', $user_id)->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'lb_user'  => $lb_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $iuserid,
                            'estatus'   => $input['estatus'],
                            'vemail'    => $vemail,
                        ]);
                        session()->forget('userInput');
                        }else{
                            $user = User::where('iuserid', '=', $user_id)->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $iuserid,
                            'estatus'   => $input['estatus'],
                            'vemail'    => $vemail,
                        ]);
                        session()->forget('userInput');
                        }
                        
                    }
                    
                    
                } else {
                    if($check_pwd_entered == 1){
                         $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            $user = User::where('vemail', '=', $vemail)->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'lb_user'  => $lb_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $iuserid,
                            'estatus'   => $input['estatus'],
                            'password' => $encdoe_mwpassword,
                        ]);
                        session()->forget('userInput');
                        }else{
                            $user = User::where('vemail', '=', $vemail)->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $iuserid,
                            'estatus'   => $input['estatus'],
                            'password' => $encdoe_mwpassword,
                        ]);
                        session()->forget('userInput');
                        }
                        
                    }else {
                        $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                             $user = User::where('vemail', '=', $vemail)->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'lb_user'  => $lb_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $iuserid,
                            'estatus'   => $input['estatus'],
                        ]);
                        session()->forget('userInput');
                        }else{
                             $user = User::where('vemail', '=', $vemail)->update([
                            'mob_user'  => $mob_user,
                            'web_user'  => $web_user,
                            'fname'     => $input['vfname'],
                            'lname'     => $input['vlname'],
                            'user_role' => $input['vusertype'],
                            'iuserid'   => $iuserid,
                            'estatus'   => $input['estatus'],
                        ]);
                        session()->forget('userInput');
                        }
                       
                    }
                    
                
                }

        }
        return redirect('users')->with('message', 'User Updated Successfully');
    }
    

    public function remove(Request $request)
    {
        // $delId = $request->all();
        // for($i = 0; $i < count($delId['selected']); $i++ ){
        //     UserDynamic::where('iuserid', '=', $delId['selected'][$i] )->delete();
        //     User::where('iuserid', '=', $delId['selected'][$i])->update(['estatus' => 'Inactive']);
        // }
        // return redirect('users')->with('message', 'User Deleted Successfully');
        
        $delId = $request->all();
        $sid = session()->get('sid');
        for($i = 0; $i < count($delId['selected']); $i++ ){
            $del_id = $delId['selected'][$i];
            // UserDynamic::where('iuserid', '=', $delId['selected'][$i] )->delete();
            $deletemstuser = UserDynamic::where('iuserid', '=', $delId['selected'][$i] )->get();
            $deletemstuser[0]->delete();
            $delete = User::where([['iuserid', '=', $del_id  ], ['sid', '=', $sid ] ])->delete();
        }
        if($delete){
            return redirect('users')->with('message', 'User Deleted Successfully');
        }else {
            return redirect('users')->with('message', 'Master Users can not be Deleted Permanently');
        }
    }
}
