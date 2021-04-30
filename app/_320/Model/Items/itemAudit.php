<?php
namespace App\Model\Items;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class itemAudit extends Model
{
    public function getTotalItems($data = array())
    {
        $sql = "SELECT a.iitemid as iitemid FROM mst_item as a WHERE a.estatus='Active'";
        if (!empty($data['search_radio'])) {
            if (count($data['search_find_dates']) > 0 && $data['search_radio'] == 'by_dates') {
                $sdate = str_replace('/', '-', $data['search_find_dates']['seach_start_date']);
                $start_date = \DateTime::createFromFormat('m-d-Y', $sdate);
                $data['start_date'] = $start_date->format('Y-m-d');
                $edate = str_replace('/', '-', $data['search_find_dates']['seach_end_date']);
                $end_date = \DateTime::createFromFormat('m-d-Y', $edate);
                $data['end_date'] = $end_date->format('Y-m-d');
                $sql .= " AND date_format(a.LastUpdate,'%Y-%m-%d') >= '" . $data['start_date'] . "' AND date_format(a.LastUpdate,'%Y-%m-%d') <= '" . $data['end_date'] . "'";
            } else if (!empty($data['search_find']) && $data['search_radio'] == 'search') {
                $sql .= " AND a.vitemname LIKE  '%" . ($data['search_find']) . "%' ";
            }
        }
        $sql .= " ORDER BY LastUpdate DESC";
        $query = DB::connection('mysql_dynamic')->select($sql);
        $query = json_decode(json_encode($query), true);
        $return_arr = array();
        if (count($query) > 0) {
            foreach ($query as $key => $value) {
                $return_arr['iitemid'][$key] = $value['iitemid'];
            }
        } else {
            $return_arr['iitemid'] = array();
        }
        $return_arr['total'] = count($query);
        return $return_arr;
    }
    
    public function getItems($itemdata = array())
    {
        $datas = array();
        $sql_string = '';
        if (isset($itemdata['search_radio']) && !empty($itemdata['search_radio'])) {
            if (count($itemdata['search_find_dates']) > 0 && $itemdata['search_radio'] == 'by_dates') {
                $sdate = str_replace('/', '-', $itemdata['search_find_dates']['seach_start_date']);
                $start_date = \DateTime::createFromFormat('m-d-Y', $sdate);
                $data['start_date'] = $start_date->format('Y-m-d');
                $edate = str_replace('/', '-', $itemdata['search_find_dates']['seach_end_date']);
                $end_date = \DateTime::createFromFormat('m-d-Y', $edate);
                $data['end_date'] = $end_date->format('Y-m-d');
                $sql_string .= " WHERE a.estatus='Active' AND date_format(a.LastUpdate,'%Y-%m-%d') >= '" . $data['start_date'] . "' AND date_format(a.LastUpdate,'%Y-%m-%d') <= '" . $data['end_date'] . "'";
            } else if (!empty($itemdata['search_find']) && $itemdata['search_radio'] == 'search') {
                $sql_string .= " WHERE a.estatus='Active' AND a.vitemname LIKE  '%" . ($itemdata['search_find']) . "%' ";
            }
            $sql_string .= ' ORDER BY a.LastUpdate DESC';
            if (isset($itemdata['start']) || isset($itemdata['limit'])) {
                if ($itemdata['start'] < 0) {
                    $itemdata['start'] = 0;
                }
                if ($itemdata['limit'] < 1) {
                    $itemdata['limit'] = 20;
                }
                $sql_string .= " LIMIT " . (int)$itemdata['start'] . "," . (int)$itemdata['limit'];
            }
        } else {
            $sql_string .= ' WHERE a.estatus="Active" ORDER BY a.LastUpdate DESC';
            if (isset($itemdata['start']) || isset($itemdata['limit'])) {
                if ($itemdata['start'] < 0) {
                    $itemdata['start'] = 0;
                }
                if ($itemdata['limit'] < 1) {
                    $itemdata['limit'] = 20;
                }
                $sql_string .= " LIMIT " . (int)$itemdata['start'] . "," . (int)$itemdata['limit'];
            }
        }
        $query = DB::connection('mysql_dynamic')->select("SELECT a.iitemid, a.vitemtype, a.vitemname, a.vbarcode, a.vcategorycode, a.vdepcode, a.vsuppliercode, a.iqtyonhand, a.vtax1, a.vtax2, a.dcostprice, a.dunitprice, a.visinventory, a.isparentchild, a.LastUpdate, mc.vcategoryname, md.vdepartmentname, ms.vcompanyname , CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND, case isparentchild when 0 then a.VITEMNAME  when 1 then Concat(a.VITEMNAME,' [Child]') when 2 then  Concat(a.VITEMNAME,' [Parent]') end   as VITEMNAME FROM mst_item as a LEFT JOIN mst_category mc ON(mc.vcategorycode=a.vcategorycode) LEFT JOIN mst_department md ON(md.vdepcode=a.vdepcode) LEFT JOIN mst_supplier ms ON(ms.vsuppliercode=a.vsuppliercode) $sql_string");
        return $query;
    }
}
