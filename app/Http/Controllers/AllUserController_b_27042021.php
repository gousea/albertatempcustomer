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
        $file_location = 'user_permisson_welcome.log';
        $myfile = fopen($file_location, "a") or die("Unable to open file!");
        $txt = PHP_EOL."Insertion Starting Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        fwrite($myfile, $txt);
        
        $users = UserDynamic::orderBy('iuserid', 'DESC')->paginate(20);
        
        $store_text = '========================== Userpemissionloaded '.PHP_EOL;
        fwrite($myfile, $store_text);
        
        $txt = "Insertion End Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        $txt .= PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);

        return view('User.index', compact('users'));
    }

    public function create()
    {
        // $permissions = Permission::all();
        $permissions = Permission::groupBy('vpermissioncode')->get();
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
        $file_location = 'user_permisson_store.log';
        $myfile = fopen($file_location, "a") or die("Unable to open file!");
        $txt = PHP_EOL."Insertion Starting Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        fwrite($myfile, $txt);
        
        $input = $request->all();
        $sid = session()->get('sid');
        if (isset($input['vuserid'])) {
            $some_text = '========================== Duplicate UserID '.PHP_EOL;
            fwrite($myfile, $some_text);
        
            $duplicateUserid = UserDynamic::where('vuserid', '=', $input['vuserid'])->get();
        }
        
        if (isset($input['vemail'])) {
            $some_text = '========================== checking email duplicate and status Active '.PHP_EOL;
            fwrite($myfile, $some_text);
            
            $duplicateEmail = User::where('vemail', '=', $input['vemail'])->get();
            $duplicateMstuser = UserDynamic::where('vemail', '=', $input['vemail'])->get();
        }

        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
            
            $some_text = '========================== POS user id is already exist '.PHP_EOL;
            fwrite($myfile, $some_text);
        
            return redirect('users/create')
                ->withErrors("Pos User id is already exists.")
                ->withInput($input);
        } elseif ((isset($duplicateEmail) && count($duplicateEmail) > 0) || (isset($duplicateMstuser) && count($duplicateMstuser) > 0)) {
            
            
            $some_text = '========================== User email already exists '.PHP_EOL;
            fwrite($myfile, $some_text);
            
            return redirect('users/create')
                ->withErrors("User Email is already exists.")
                ->withInput($input);

        } else {
            //checkwether pos user or (Mobile & Web) user
            
            $some_text = '========================== check whether pos user or mobile & web user '.PHP_EOL;
            fwrite($myfile, $some_text);
        
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

                    // User::create([
                    //     'mob_user'  => $mob_user,
                    //     'web_user'  => $web_user,
                    //     'lb_user'  => $lb_user,
                    //     'fname'     => $input['vfname'],
                    //     'lname'     => $input['vlname'],
                    //     'user_role' => $input['vusertype'],
                    //     'iuserid'   => $mst_user->iuserid,
                    //     'estatus'   => $input['estatus'],
                    //     'sid'       =>  session()->get('sid'),
                    // ]);
                }else{
                    $some_text = '========================== not check whether pos user or mobile & web user '.PHP_EOL;
                    fwrite($myfile, $some_text);
        
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

                    // User::create([
                    //     'mob_user'  => $mob_user,
                    //     'web_user'  => $web_user,
                    //     'fname'     => $input['vfname'],
                    //     'lname'     => $input['vlname'],
                    //     'user_role' => $input['vusertype'],
                    //     'iuserid'   => $mst_user->iuserid,
                    //     'estatus'   => $input['estatus'],
                    //     'sid'       =>  session()->get('sid'),
                    // ]);
                }

               
            } else {
                
                $some_text = '========================== check for this '.PHP_EOL;
                fwrite($myfile, $some_text);
        
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
                        $some_text = '========================== checking for this '.PHP_EOL;
                        fwrite($myfile, $some_text);
                        
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
                    
                    $some_text = '========================== passwords for this '.PHP_EOL;
                    fwrite($myfile, $some_text);
        
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
                        $some_text = '========================== else part for this '.PHP_EOL;
                        fwrite($myfile, $some_text);
        
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
        
        $txt = "User created Insertion End Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        $txt .= PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);
        
        return redirect('users')->with('message', 'User Created Successfully');
    }



    public function edit(UserDynamic $userDynamic, $iuserid)
    {
        $file_location = 'user_permisson_edit.log';
        $myfile = fopen($file_location, "a") or die("Unable to open file!");
        $txt = PHP_EOL."Insertion Starting Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        fwrite($myfile, $txt);
        
        $permissions = Permission::groupBy('vpermissioncode')->get();
        $mstPermissiongroup = Permissiongroup::all();
        $user = UserDynamic::where('iuserid', '=', $iuserid)->get();
        if(count($user) > 0){
            $users = $user[0];
        }else {
            $users = "";
        }
        // $checkedPermission = Userpermission::where('userid', '=', $iuserid)->get()->toArray();
        $checkedPermission = DB::connection('mysql_dynamic')->select("SELECT  mp.vpermissioncode FROM mst_permission mp join mst_userpermissions mup
        on mp.vpermissioncode = mup.permission_id where mup.status = 'Active' and mup.userid = ".$iuserid);
        
        $store_text = '========================== checking edit userpermission checked permission '.PHP_EOL;
        fwrite($myfile, $store_text);        
        
        $dataPerCheck = array();
        for ($i = 0; $i < count($checkedPermission); $i++) {
            $store_text = '========================== foreach user permission '.PHP_EOL;
            fwrite($myfile, $store_text);
            $dataPerCheck[] = $checkedPermission[$i]->vpermissioncode;
        }
        
        $txt = "Insertion End Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        $txt .= PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);
        
        return view('User.edit', compact('permissions', 'mstPermissiongroup', 'users', 'dataPerCheck'));
    }

    
    public function update(Request $request, UserDynamic $userDynamic, $iuserid)
    {
        
        // dd($request->all());
        $file_location = 'user_permisson_update.log';
        $myfile = fopen($file_location, "a") or die("Unable to open file!");
        $txt = PHP_EOL."Insertion Starting Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        fwrite($myfile, $txt);
        
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
                    
                    $store_text = '========================== userpermission update foreach'.PHP_EOL;
                    fwrite($myfile, $store_text);
                    
                    $get_id = Userpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
                    $mst_perm_id = $get_id[0]->Id;
                    Userpermission::where('Id', '=', $mst_perm_id)->update(['permission_id' => $permission, 'status' => 'Active']);
                } else {
                    
                    $store_text = '========================== userpermission else create'.PHP_EOL;
                    fwrite($myfile, $store_text);
        
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
            $store_text = '========================== userpermission list of permission'.PHP_EOL;
            fwrite($myfile, $store_text);
            
            $get_id = Userpermission::where([['permission_id', '=', $permission], ['userid', '=', $iuserid]])->get();
            
            foreach($get_id as $value){
                $mst_perm_id = $value->Id;
                $store_text = '========================== userpermission list of permission foreach'.PHP_EOL;
                fwrite($myfile, $store_text);
                //====commented (SUMIT) 05-04-2021====
                // Userpermission::where('Id', '=', $mst_perm_id)->update(['status' => 'Inactive']);
                Userpermission::where('Id', '=', $mst_perm_id)->delete();
            }
        }

        $group = Permissiongroup::where('vgroupname', '=', $input['vusertype'])->get();
        if (count($group) > 0) {
            $ipermissiongroupid = $group[0]->ipermissiongroupid;
            $que = Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->get();
            
            $store_text = '========================== permission group'.PHP_EOL;
            fwrite($myfile, $store_text);
            
            if (count($que) > 0) {
                Userpermissiongroup::where('iuserid', '=', Auth::user()->iuserid)->update(['ipermissiongroupid' => $ipermissiongroupid]);
            } else {
                
                $store_text = '========================== permission group else'.PHP_EOL;
                fwrite($myfile, $store_text);
            
                Userpermissiongroup::create(['iuserid' => Auth::user()->iuserid, 'ipermissiongroupid' => $ipermissiongroupid, 'SID' => session()->get('sid')]);
            }
        }


        $mst_user = UserDynamic::where('iuserid', '=', $iuserid)->first();
        $store_mw_User = User::where([['vemail', '=', $mst_user->vemail],['vemail', '!=', '']])->first();
        
        if(isset($input['mwpassword'])){
            $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_BCRYPT);
        }else {
            $encdoe_mwpassword = $mst_user->vpassword;
        }
        
        if(isset($input['vpassword'])){
            $encdoe_password = $this->encodePassword($input['vpassword']);
        }else {
            $encdoe_password = $mst_user->vpassword;
        }
        
        $db_mst_user = $mst_user->mwpassword;
        
        
        $dbvemail = $mst_user->vemail;
        $check_pwd_entered = 0;
        
        if (isset($input['mwpassword'])) {
            $encdoe_mwpassword = password_hash($input['mwpassword'], PASSWORD_DEFAULT);
            $check_pwd_entered = 1;
        } 
        
        $user_id = $mst_user->iuserid;
        
        // check if the entered email is different from the one in db
        if ($mst_user->vemail != $input['vemail']) {
            if (isset($input['vemail'])) {
                $duplicateEmail = User::where('vemail', '=', $input['vemail'])->get();
                $duplicateMstuser = UserDynamic::where('vemail', '=', $input['vemail'])->get();
            }
            $vemail = $input['vemail'];
            $store_text = '========================== check email is different one in db'.PHP_EOL;
            fwrite($myfile, $store_text);
            
        } else {
            $vemail = $mst_user->vemail;
        }

        if (isset($input['vuserid']) && $mst_user->vuserid != $input['vuserid']) {
            $vuserid = $input['vuserid'];
            if (isset($input['vuserid'])) {
                $duplicateUserid = UserDynamic::where('vuserid', '=', $input['vuserid'])->get();
            }
            $store_text = '========================== duplicates'.PHP_EOL;
            fwrite($myfile, $store_text);
        } else {
            $store_text = '========================== else duplicates'.PHP_EOL;
            fwrite($myfile, $store_text);
            
            $vuserid = $mst_user->vuserid;
        }


        if (isset($duplicateUserid) && count($duplicateUserid) > 0) {
            
            $store_text = '========================== Pos User id is already exists'.PHP_EOL;
            fwrite($myfile, $store_text);
            
             return redirect('users/'.$iuserid.'/edit')
                ->withErrors("Pos User id is already exists.")
                ->withInput($input);
        } elseif ((isset($duplicateEmail) && count($duplicateEmail) > 0) || (isset($duplicateMstuser) && count($duplicateMstuser) > 0)) {
            
            $store_text = '========================== User Email is already exists'.PHP_EOL;
            fwrite($myfile, $store_text);
            
            return redirect('users/'.$iuserid.'/edit')
                ->withErrors("User Email is already exists.")
                ->withInput($input);
        } else {
            
            
            $store_text = '========================== else part'.PHP_EOL;
            fwrite($myfile, $store_text);
            
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
                
                
                // dd($web_user."_ ".$pos_user."_ ". $mob_user. "_ ".$lb_user);
                if($check_pwd_entered == 1){
                    
                    if (isset($input['vpassword'])) {
                        
                        $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                        $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            
                            $store_text = '========================== update user'.PHP_EOL;
                            fwrite($myfile, $store_text);
                            
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
                            $store_text = '========================== update user else'.PHP_EOL;
                            fwrite($myfile, $store_text);
            
                            $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                            if(count($res) > 0){
                                $store_text = '========================== update users'.PHP_EOL;
                                fwrite($myfile, $store_text);
                                
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
                                $store_text = '========================== update users else'.PHP_EOL;
                                fwrite($myfile, $store_text);
                                
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
                        
                        $store_text = '========================== updt usr'.PHP_EOL;
                        fwrite($myfile, $store_text);
                        
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
                            
                            $store_text = '========================== updt usr else'.PHP_EOL;
                            fwrite($myfile, $store_text);
                            
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
                        $store_text = '========================== updt usr else'.PHP_EOL;
                        fwrite($myfile, $store_text);
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
                            
                            $store_text = '========================== updt usr s'.PHP_EOL;
                            fwrite($myfile, $store_text);
                            
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
                        
                        $store_text = '========================== updt '.PHP_EOL;
                        fwrite($myfile, $store_text);
                        
                         $sql = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = 'u".$sid."' AND table_name = 'mst_user' AND column_name = 'lb_user'";
                            $res =DB::connection('mysql')->select($sql);
                        
                        if(count($res) > 0){
                            
                            $store_text = '========================== updt -----'.PHP_EOL;
                            fwrite($myfile, $store_text);
                            
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
                            
                            $store_text = '========================== updt ----- else'.PHP_EOL;
                            fwrite($myfile, $store_text);
                            
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
                    $store_text = '========================== email'.PHP_EOL;
                    fwrite($myfile, $store_text);
                    
                    $vemail = $input['vemail'];
                    
                    if($check_pwd_entered == 1){
                        $store_text = '========================== if email'.PHP_EOL;
                        fwrite($myfile, $store_text);
                        
                        
                        if($pos_user === 'Y' && $web_user === 'N' && $mob_user === 'N' && $lb_user === 'N' ){
                            
                        }else{
                                //to Check wether the Store_mw_user data is empty or not
                                if(!is_null($store_mw_User)){
                                    //if not empty that we are updating the user with store_mw_user id(ie., Primary key )
                                    $user = User::where('id', '=', $store_mw_User->id)->update([
                                        'mob_user'  => $mob_user,
                                        'web_user'  => $web_user,
                                        'lb_user'  => $lb_user,
                                        'fname'     => $input['vfname'],
                                        'lname'     => $input['vlname'],
                                        'user_role' => $input['vusertype'],
                                        'iuserid'   => $iuserid,
                                        'estatus'   => $input['estatus'],
                                        'password'  => $encdoe_mwpassword ,
                                        'sid'       => session()->get('sid'),
                                        'vemail'    => $vemail,
                                    ]); 
                                }else {
                                    //if empty then we are checking the email is alreay existed in store_mw_user 
                                    $Email = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->get();
                                    if(count($Email) > 0){
                                        //if Email exists and it is in inactive means we will update that email 
                                        User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                                            'mob_user'  => $mob_user,
                                            'web_user'  => $web_user,
                                            'fname'     => $input['vfname'],
                                            'lname'     => $input['vlname'],
                                            'user_role' => $input['vusertype'],
                                            'iuserid'   => $mst_user->iuserid,
                                            'estatus'   => $input['estatus'],
                                            'sid'       => session()->get('sid'),
                                            'password'  => $encdoe_mwpassword,
                                        ]);
                                    }else {
                                        //else not email present in store_mw user we are creating that email
                                        $main = User::create([
                                            'mob_user'  => $mob_user,
                                            'web_user'  => $web_user,
                                            'lb_user'   => $lb_user,
                                            'fname'     => $input['vfname'],
                                            'lname'     => $input['vlname'],
                                            'user_role' => $input['vusertype'],
                                            'iuserid'   => $mst_user->iuserid,
                                            'estatus'   => $input['estatus'],
                                            'sid'       => session()->get('sid'),
                                            'password'  => $encdoe_mwpassword,
                                            'vemail'    => $vemail,
                                        ]);
                                    }
                                }
                            
                        }
                        session()->forget('userInput');
                        
                       
                    }else { 
                        
                        $store_text = '========================== mob web'.PHP_EOL;
                        fwrite($myfile, $store_text);
                        if($pos_user === 'Y' && $web_user === 'N' && $mob_user === 'N' && $lb_user === 'N' ){
                            
                        }else{
                            
                            //to Check wether the Store_mw_user data is empty or not
                            if(!is_null($store_mw_User)){
                                //if not empty that we are updating the user with store_mw_user id(ie., Primary key )
                                $user = User::where('id', '=', $store_mw_User->id)->update([
                                    'mob_user'  => $mob_user,
                                    'web_user'  => $web_user,
                                    'lb_user'  => $lb_user,
                                    'fname'     => $input['vfname'],
                                    'lname'     => $input['vlname'],
                                    'user_role' => $input['vusertype'],
                                    'iuserid'   => $iuserid,
                                    'estatus'   => $input['estatus'],
                                    'sid'       => session()->get('sid'),
                                    'vemail'    => $vemail,
                                ]); 
                            }else {
                                //if empty then we are checking the email is alreay existed in store_mw_user 
                                $Email = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->get();
                                if(count($Email) > 0){
                                    //if Email exists and it is in inactive means we will update that email 
                                    User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                                        'mob_user'  => $mob_user,
                                        'web_user'  => $web_user,
                                        'fname'     => $input['vfname'],
                                        'lname'     => $input['vlname'],
                                        'user_role' => $input['vusertype'],
                                        'iuserid'   => $mst_user->iuserid,
                                        'estatus'   => $input['estatus'],
                                        'sid'       => session()->get('sid'),
                                        'password'  => $encdoe_mwpassword,
                                    ]);
                                }else {
                                    //else not email present in store_mw user we are creating that email
                                    $main = User::create([
                                        'mob_user'  => $mob_user,
                                        'web_user'  => $web_user,
                                        'lb_user'   => $lb_user,
                                        'fname'     => $input['vfname'],
                                        'lname'     => $input['vlname'],
                                        'user_role' => $input['vusertype'],
                                        'iuserid'   => $mst_user->iuserid,
                                        'estatus'   => $input['estatus'],
                                        'sid'       => session()->get('sid'),
                                        'password'  => $encdoe_mwpassword,
                                        'vemail'    => $vemail,
                                    ]);
                                }
                            }
                            
                            
                            
                            
                        }
                        session()->forget('userInput');
                    }
                    
                    
                } else {
                    
                    $store_text = '========================== check pwd entered'.PHP_EOL;
                    fwrite($myfile, $store_text);
                    
                    if($check_pwd_entered == 1){
                        $store_text = '========================== if check pwd entered'.PHP_EOL;
                        fwrite($myfile, $store_text);
                        if($pos_user === 'Y' && $web_user === 'N' && $mob_user === 'N' && $lb_user === 'N' ){
                            
                        }else{
                            
                            //to Check wether the Store_mw_user data is empty or not
                            if(!is_null($store_mw_User)){
                                //if not empty that we are updating the user with store_mw_user id(ie., Primary key )
                                $user = User::where('id', '=', $store_mw_User->id)->update([
                                    'mob_user'  => $mob_user,
                                    'web_user'  => $web_user,
                                    'lb_user'   => $lb_user,
                                    'fname'     => $input['vfname'],
                                    'lname'     => $input['vlname'],
                                    'user_role' => $input['vusertype'],
                                    'iuserid'   => $iuserid,
                                    'estatus'   => $input['estatus'],
                                    'password'  => $encdoe_mwpassword,
                                    'sid'       => session()->get('sid'),
                                    'vemail'    => $vemail,
                                ]); 
                            }else {
                                //if empty then we are checking the email is alreay existed in store_mw_user 
                                $Email = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->get();
                                if(count($Email) > 0){
                                    //if Email exists and it is in inactive means we will update that email 
                                    User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                                        'mob_user'  => $mob_user,
                                        'web_user'  => $web_user,
                                        'fname'     => $input['vfname'],
                                        'lname'     => $input['vlname'],
                                        'user_role' => $input['vusertype'],
                                        'iuserid'   => $mst_user->iuserid,
                                        'estatus'   => $input['estatus'],
                                        'sid'       => session()->get('sid'),
                                        'password'  => $encdoe_mwpassword,
                                    ]);
                                }else {
                                    //else not email present in store_mw user we are creating that email
                                    $main = User::create([
                                        'mob_user'  => $mob_user,
                                        'web_user'  => $web_user,
                                        'lb_user'   => $lb_user,
                                        'fname'     => $input['vfname'],
                                        'lname'     => $input['vlname'],
                                        'user_role' => $input['vusertype'],
                                        'iuserid'   => $mst_user->iuserid,
                                        'estatus'   => $input['estatus'],
                                        'sid'       => session()->get('sid'),
                                        'password'  => $encdoe_mwpassword,
                                        'vemail'    => $vemail,
                                    ]);
                                }
                            }
                            
                        }
                        session()->forget('userInput');
                    }else { 
                        
                        if($pos_user === 'Y' && $web_user === 'N' && $mob_user === 'N' && $lb_user === 'N' ){
                            
                        }else{ 
                            
                            //to Check wether the Store_mw_user data is empty or not
                            if(!is_null($store_mw_User)){
                                //if not empty that we are updating the user with store_mw_user id(ie., Primary key )
                                $user = User::where('id', '=', $store_mw_User->id)->update([
                                        'mob_user'  => $mob_user,
                                        'web_user'  => $web_user,
                                        'lb_user'   => $lb_user,
                                        'fname'     => $input['vfname'],
                                        'lname'     => $input['vlname'],
                                        'user_role' => $input['vusertype'],
                                        'iuserid'   => $iuserid,
                                        'estatus'   => $input['estatus'],
                                        'sid'       => session()->get('sid'),
                                        'vemail'    => $vemail,
                                ]); 
                            }else {
                                //if empty then we are checking the email is alreay existed in store_mw_user 
                                $Email = User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->get();
                                if(count($Email) > 0){
                                    //if Email exists and it is in inactive means we will update that email 
                                    User::where([['vemail', '=', $input['vemail']], ['estatus', '=',  'Inactive']])->update([
                                            'mob_user'  => $mob_user,
                                            'web_user'  => $web_user,
                                            'fname'     => $input['vfname'],
                                            'lname'     => $input['vlname'],
                                            'user_role' => $input['vusertype'],
                                            'iuserid'   => $mst_user->iuserid,
                                            'estatus'   => $input['estatus'],
                                            'sid'       => session()->get('sid'),
                                            'password'  => $encdoe_mwpassword,
                                    ]);
                                }else {
                                    //else not email present in store_mw user we are creating that email
                                    $main = User::create([
                                            'mob_user'  => $mob_user,
                                            'web_user'  => $web_user,
                                            'lb_user'   => $lb_user,
                                            'fname'     => $input['vfname'],
                                            'lname'     => $input['vlname'],
                                            'user_role' => $input['vusertype'],
                                            'iuserid'   => $mst_user->iuserid,
                                            'estatus'   => $input['estatus'],
                                            'sid'       => session()->get('sid'),
                                            'password'  => $encdoe_mwpassword,
                                            'vemail'    => $vemail,
                                    ]);
                                }
                            }   
                        }
                        session()->forget('userInput');
                    }
                }
        }
        
        $txt = "Insertion End Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        $txt .= PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);
        
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
        
         $file_location = 'user_permisson_remove.log';
        $myfile = fopen($file_location, "a") or die("Unable to open file!");
        $txt = PHP_EOL."Insertion Starting Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        fwrite($myfile, $txt);
        
        $delId = $request->all();
        $sid = session()->get('sid');
        for($i = 0; $i < count($delId['selected']); $i++ ){
            $store_text = '========================== foreach remove '.PHP_EOL;
            fwrite($myfile, $store_text);
            
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
        
        $txt = "Insertion End Date And time is: ".date("Y-m-d h:i:sa").PHP_EOL;
        $txt .= PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}
