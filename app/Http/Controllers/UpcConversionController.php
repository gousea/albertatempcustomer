<?php

namespace App\Http\Controllers;

use App\Model\UpcConversion;
use Illuminate\Http\Request;

class UpcConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        return view('upcconversion.upc_conversion');
    }

    public function convert_upca_to_upce($upc) 
	{
		if(!isset($upc)||!is_numeric($upc)) { return false; }
		if(strlen($upc)==11) { $upc = $upc.$this->validate_upca($upc,true); }
		if(strlen($upc)>12||strlen($upc)<12) { return false; }
		if($this->validate_upca($upc)===false) { return false; }
		if(!preg_match("/0(\d{11})/", $upc)) { return false; }
		$upce = null;
		if(preg_match("/0(\d{2})00000(\d{3})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."0";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{2})10000(\d{3})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."1";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{2})20000(\d{3})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."2";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{3})00000(\d{2})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."3";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{4})00000(\d{1})(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1].$upc_matches[2]."4";
		$upce = $upce.$upc_matches[3]; return $upce; }
		if(preg_match("/0(\d{5})00005(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."5";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00006(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."6";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00007(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."7";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00008(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."8";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if(preg_match("/0(\d{5})00009(\d{1})/", $upc, $upc_matches)) {
		$upce = "0".$upc_matches[1]."9";
		$upce = $upce.$upc_matches[2]; return $upce; }
		if($upce==null) { return false; }
	}

    public function convert_upce_to_upca($upc)
	{
        if(!isset($upc)||!is_numeric($upc)) { return false; }
		if(strlen($upc)==7) { $upc = $upc.$this->validate_upce($upc,true); }
		if(strlen($upc)>8||strlen($upc)<8) { return false; }
		if(!preg_match("/^0/", $upc)) { return false; }
		if($this->validate_upce($upc)===false) { return false; }
		if(preg_match("/0(\d{5})([0-3])(\d{1})/", $upc, $upc_matches)) {
		$upce_test = preg_match("/0(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})/", $upc, $upc_matches);
		if($upce_test==false) { return false; }
		if($upc_matches[6]==0) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[6]."0000".$upc_matches[3].$upc_matches[4].$upc_matches[5].$upc_matches[7]; }
		if($upc_matches[6]==1) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[6]."0000".$upc_matches[3].$upc_matches[4].$upc_matches[5].$upc_matches[7]; }
		if($upc_matches[6]==2) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[6]."0000".$upc_matches[3].$upc_matches[4].$upc_matches[5].$upc_matches[7]; }
		if($upc_matches[6]==3) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3]."00000".$upc_matches[4].$upc_matches[5].$upc_matches[7]; } }
		if(preg_match("/0(\d{5})([4-9])(\d{1})/", $upc, $upc_matches)) {
		preg_match("/0(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})/", $upc, $upc_matches);
		if($upc_matches[6]==4) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3].$upc_matches[4]."00000".$upc_matches[5].$upc_matches[7]; }
		if($upc_matches[6]==5) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3].$upc_matches[4].$upc_matches[5]."0000".$upc_matches[6].$upc_matches[7]; }
		if($upc_matches[6]==6) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3].$upc_matches[4].$upc_matches[5]."0000".$upc_matches[6].$upc_matches[7]; }
		if($upc_matches[6]==7) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3].$upc_matches[4].$upc_matches[5]."0000".$upc_matches[6].$upc_matches[7]; }
		if($upc_matches[6]==8) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3].$upc_matches[4].$upc_matches[5]."0000".$upc_matches[6].$upc_matches[7]; }
		if($upc_matches[6]==9) {
		$upce = "0".$upc_matches[1].$upc_matches[2].$upc_matches[3].$upc_matches[4].$upc_matches[5]."0000".$upc_matches[6].$upc_matches[7]; } }
		return $upce; 
    }
    
    public function validate_upca($upc,$return_check=false) 
	{
		if(!isset($upc)||!is_numeric($upc)) { return false; }
		if(strlen($upc)>12) { preg_match("/^(\d{12})/", $upc, $fix_matches); $upc = $fix_matches[1]; }
		if(strlen($upc)>12||strlen($upc)<11) { return false; }
		if(strlen($upc)==11) {
		preg_match("/^(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})/", $upc, $upc_matches); }
		if(strlen($upc)==12) {
		preg_match("/^(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})(\d{1})/", $upc, $upc_matches); }
		$OddSum = ($upc_matches[1] + $upc_matches[3] + $upc_matches[5] + $upc_matches[7] + $upc_matches[9] + $upc_matches[11]) * 3;
		$EvenSum = $upc_matches[2] + $upc_matches[4] + $upc_matches[6] + $upc_matches[8] + $upc_matches[10];
		$AllSum = $OddSum + $EvenSum;
		$CheckSum = $AllSum % 10;
		if($CheckSum>0) {
		$CheckSum = 10 - $CheckSum; }
		if($return_check==false&&strlen($upc)==12) {
		if($CheckSum!=$upc_matches[12]) { return false; }
		if($CheckSum==$upc_matches[12]) { return true; } }
		if($return_check==true) { return $CheckSum; } 
		if(strlen($upc)==11) { return $CheckSum; } 
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $sid = $request->session()->get('sid');
        $data = array();
        $url = '';
        if ($input['_method'] == 'post') {
            if(isset($input['upc_e']) && $input['upc_e'] != "")
            {
                $upca = $this->convert_upce_to_upca($input['upc_e']);
                $data['upc_a'] = $upca;
                $data['upc_e'] = $input['upc_e'];
            }
            
            if(isset($input['upc_a_t']) && $input['upc_a_t'] != "")
            {
                $upce = $this->convert_upca_to_upce($input['upc_a_t']);
                $data['upc_e_t'] = $upce;
                $data['upc_a_t'] = $input['upc_a_t'];
            }
            
        }
        return view('upcconversion.upc_conversion', compact('data'));
    }
}
