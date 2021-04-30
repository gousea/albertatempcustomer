<?php

namespace App\Http\Controllers;

use App\Model\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //set’s application’s locale
    //   app()->setLocale($locale);
      
      //Gets the translated message and displays it
    //   echo trans('template.title');
        // return view('templates.template', compact('storeData'));
        $data['templates'] = array();
        
        $data['heading_title'] = __('administration/template.heading_title');
        $data['text_list'] = __('administration/template.text_list');
        $data['text_no_results'] = __('administration/template.text_no_results');
        $data['text_confirm'] = __('administration/template.text_confirm');
        $data['text_template_type'] = __('administration/template.text_template_type');
        $data['text_inventory_type'] = __('administration/template.text_inventory_type');
        $data['text_template_name'] = __('administration/template.text_template_name');
        $data['text_template_sequence'] = __('administration/template.text_template_sequence');
        $data['text_template_status'] = __('administration/template.text_template_status');
        $data['button_remove'] = __('administration/template.button_remove');
        $data['button_save'] = __('administration/template.button_save');
        $data['button_view'] = __('administration/template.button_view');
        $data['button_add'] = __('administration/template.button_add');
        $data['button_edit'] = __('administration/template.button_edit');
        $data['button_delete'] = __('administration/template.button_delete');
        $data['button_rebuild'] = __('administration/template.button_rebuild');
        

        return view('templates.template',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        //
    }
}
