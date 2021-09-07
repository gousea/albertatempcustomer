<?php
namespace App\Http\Controllers;

use App\Model\Employee_Report;
use Illuminate\Http\Request;

use PDF;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class EmployeeReportController extends Controller {
    public function index(Request $request) {
      $input = $request->all();
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        
        
        
        if ($input) {
            $employee_report = new Employee_Report();
            $store_data=$employee_report->getStore();
            $store = $store_data[0];
            $data['p_start_date'] = $input['start_date'];
            $data['p_end_date'] = $input['end_date'];
            $results = $employee_report->getCategoriesReport($data['p_start_date'], $data['p_end_date']);
            $data['reports'] = $results;
            $request->session()->put('p_start_date', $data['p_start_date']);
            $request->session()->put('p_end_date', $data['p_end_date']);
            $request->session()->put('reports', $data['reports']);      
            $request->session()->put('storename', $store['vstorename']);
            $request->session()->put('storeaddress', $store['vaddress1']);
            $request->session()->put('storephone', $store['vphone1']);
            $data['storename'] = $request->session()->get('storename');
            $data['storeaddress'] = $request->session()->get('storeaddress');
            $data['storephone'] = $request->session()->get('storephone');
        }
        $data['byreports'] = array(1 => 'Category', 2 => 'Department');
        
        return view('employeereports.employee_report')->with($data);
    }

    public function csv_export(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $data['reports'] = $request->session()->get('reports');
        $data_row = '';
        $data_row.= "Store Name: " . $request->session()->get('storename') . PHP_EOL;
        $data_row.= "Store Address: " . $request->session()->get('storeaddress') . PHP_EOL;
        $data_row.= "Store Phone: " . $request->session()->get('storephone') . PHP_EOL;
        $data_row.= "From: " . $request->session()->get('p_start_date')." To ". $request->session()->get('p_end_date'). PHP_EOL;
        if ($data['reports'] > 0) {
            $data_row.= 'Username,User ID,Transaction Type,Transaction Id,Transaction Time,Product Name,Amount' . PHP_EOL;
            $total = 0;
            foreach ($data['reports'] as $key => $value) {
                $data_row.= str_replace(',', ' ', $value['vusername']) . ',' . str_replace(',', ' ', $value['iuserid']) . ',' . str_replace(',', ' ', $value['TrnType']) . ',' . $value['isalesid'] . ',' . str_replace(',', ' ', $value['trn_date_time']) . ',' . str_replace(',', ' ', $value['vitemname']) . ',' . number_format((float)$value['nextunitprice'], 2, '.', '') . PHP_EOL;                
            }            
        } else {
            $data_row = 'Sorry no data found!';
        }
        $file_name_csv = 'Employee-Loss-Prevention-Report.csv';

        $file_path = public_path('image/Employee-Loss-Prevention-Report.csv');

        $myfile = fopen(public_path('image/Employee-Loss-Prevention-Report.csv'), "w");

        fwrite($myfile,$data_row);
        fclose($myfile);

        $content = file_get_contents ($file_path);
        header ('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_name_csv));
        echo $content;
        exit;
    }
    public function print_page(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $data['reports'] = $request->session()->get('reports');
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');
        $data['heading_title'] = 'Employee Loss Prevention Report';
        return view('employeereports.print_emp_report')->with($data);
    }
    public function pdf_save_page(Request $request) {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "2G");
        $data['reports'] = $request->session()->get('reports');
        $data['storename'] = $request->session()->get('storename');
        $data['storeaddress'] = $request->session()->get('storeaddress');
        $data['storephone'] = $request->session()->get('storephone');
        $data['p_start_date'] = $request->session()->get('p_start_date');
        $data['p_end_date'] = $request->session()->get('p_end_date');
        $data['heading_title'] = 'Employee Loss Prevention Report';
        $pdf = PDF::loadView('employeereports.print_emp_report',$data);
        return $pdf->download('Employee-Report.pdf');
    }
    public function send_mail(Request $request) {
        //print_r($this->session->data['reports']);
        // $email_to = 'adarsh.s.chacko@gmail.com';
        $email_to = 'hemanthraj2009@gmail.com';
        $email_to = $request->session()->get('logged_email');
        // $mail = new Mail();
        // $mail->protocol = $request->config()->get('config_mail_protocol');
        // $mail->parameter = $request->config()->get('config_mail_parameter');
        // $mail->hostname = $request->config()->get('config_smtp_host');
        // $mail->username = $request->config()->get('config_smtp_username');
        // $mail->password = $request->config()->get('config_smtp_password');
        // $mail->port = $request->config()->get('config_smtp_port');
        // $mail->timeout = $request->config()->get('config_smtp_timeout');
        // $mail->setTo($email_to);
        // $mail->setReplyTo($email_to);
        // $mail->setFrom("webadmin@albertapayments.com");
        // $mail->setSender("Notification");
        // $mail->setSubject("Employee Loss Prevention Report For the Period From " . $request->session()->get('p_start_date') . " To " . $request->session()->get('p_end_date'));
        if (count($request->session()->get('reports')) > 0) {
            $message = "<p><b>Store Name: </b> " . $request->session()->get('storename') . " </p>";
            $message.= "<p><b>Store Address: </b>" . $request->session()->get('storeaddress') . "</p>";
            $message.= "<p><b>Store Phone: </b>" . $request->session()->get('storephone') . " </p><br/>";
            $message.= "<p>Employee Loss Prevention Report For the Period From " . $request->session()->get('p_start_date') . " To " . $request->session()->get('p_end_date') . " is as follows</p><br/>";
            $message.= '<table class="table table-bordered table-striped table-hover" style="border:none;width:80%;">
                    <thead>
                      <tr style="border-top: 1px solid #ddd;">
                        <th style="text-align: center; vertical-align: middle;">Username</th>
                        <th style="text-align: center; vertical-align: middle;">Transaction Type</th>
                        <th style="text-align: center; vertical-align: middle;">Transaction Id</th>
                        <th style="text-align: center; vertical-align: middle;">Transaction Time</th>
                        <th style="text-align: center; vertical-align: middle;">Product Name</th>
                        <th style="text-align: center; vertical-align: middle;">Total Amount</th>
                      </tr>
                    </thead>
                    <tbody>';
            foreach ($request->session()->get('reports') as $report) {
                $message.= '<tr>
                  <td style="text-align: center; vertical-align: middle;">' . $report['vusername'] . '</td>
                  <td style="text-align: center; vertical-align: middle;">' . $report['TrnType'] . '</td>
                  <td style="text-align: center; vertical-align: middle;">' . $report['isalesid'] . '</td>
                  <td  style="text-align: center; vertical-align: middle;">' . $report['trn_date_time'] . '</td>
                  <td style="text-align: center; vertical-align: middle;">' . $report['vitemname'] . '</td>
                  <td style="text-align: center; vertical-align: middle;">' . $report['nextunitprice'] . '</td>                        
                </tr>';
            }
            $message.= '</tbody>              
                  </table>';
        } else {
            $message = '<div class="row">
                    <div class="col-md-12"><br><br>
                      <div class="alert alert-info text-center">
                        <strong>Sorry no data found!</strong>
                      </div>
                    </div>
                  </div>';
        }
        \Mail::to("hemanthraj2009@gmail.com")->send(new \App\Mail\EmployeeReport($message));
        // dd('Mail Sent SuccessFully');
        // return view('employeereports.employee_report')->with($data);

    }
    public function reportdata(Request $request) {
        $return = array();
        $this->load->model('api/sales_item_report');
        if (!empty($this->request->get['report_by'])) {
            if ($this->request->get['report_by'] == 1) {
                $datas = $this->model_api_sales_item_report->getCategories();
            } elseif ($this->request->get['report_by'] == 2) {
                $datas = $this->model_api_sales_item_report->getDepartments();
            }
            $return['code'] = 1;
            $return['data'] = $datas;
        } else {
            $return['code'] = 0;
        }
        echo json_encode($return);
        exit;
    }
}
?>
