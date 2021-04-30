<?php

namespace App\Http\Controllers;

use App\Http\Requests\store\StoreUpdateRequest;
use App\Model\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Store  $Store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $Store)
    {
        // return view('store/store');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Store  $Store
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $sid = $request->session()->get('sid');
        $store = Store::where('SID', '=', $sid)->get();
        $storeData = $store[0];
        return view('stores.store', compact('storeData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Store  $Store
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateRequest $request)
    {

        $input = $request->all();
        $storeid = $request->session()->get('sid');

        Store::where('SID', '=', $storeid)->update(
            [
                'vcompanycode'      =>  $input['vcompanycode'],
                'vstoreabbr'        =>  $input['vstoreabbr'],
                'vaddress1'         =>  $input['vaddress1'],
                'vstoredesc'        =>  $input['vstoredesc'],
                'vcity'             =>  $input['vcity'],
                'vstate'            =>  $input['vstate'],
                'vzip'              =>  $input['vzip'],
                'vcountry'          =>  $input['vcountry'],
                'vphone1'           =>  $input['vphone1'],
                'vphone2'           =>  $input['vphone2'],
                'vfax1'             =>  $input['vfax1'],
                'vemail'            =>  $input['vemail'],
                'vwebsite'          =>  $input['vwebsite'],
                'vcontactperson'    =>  $input['vcontactperson'],
                'isequence'         =>  $input['isequence'],
                'vmessage1'         =>  $input['vmessage1'],
                'vmessage2'         =>  $input['vmessage2']
            ]
        );

        return redirect(route('store.edit'))->with('message','Successfully Updated Store!!');
    }

}
