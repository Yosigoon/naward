<?php
class Award_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_award($a_id=false)
    { //어워드 정보 구하기
        if ($a_id === false) {
            //$where["p_status"] = 9;
            $query = $this->db->order_by('a_id', 'desc')->get('tb_award');
            $data = $query->result_array();
            $status = "00";
        } else {
            $where["a_id"] = $a_id;
            $query = $this->db->get_where('tb_award', $where);
            $data = $query->row_array();
            $cnt=is_null($data) ? 0 : count($data);
            $cnt_affected = $cnt;
            $status = $cnt_affected>0?"00":"29";
        }
        return class_return_refactoring($status, $data);
    }

    public function get_current_award()
    {
        $query = "select * from tb_award where a_status = 1 order by a_id desc limit 1";
        $query = $this->db->query($query);
        $row = $query->row_array();
        return $row;
    }

    public function create_award()
    { //어워드 생성
        $a_year = $this->input->post('a_year');
        $is_exist = $this->get_award_by_field('a_id', $a_year);
        $a_copy = $this->input->post('a_copy');
        $a_status = $this->input->post('a_status');
        $is_copy_exist = $a_copy>0?$this->get_award_by_field('a_id', $a_copy):null;
        $msg = "";
        if (is_null($is_exist)==false) {
            $status = "09";
            $data = [];
            $msg = "duplicate entry {$a_year}";
        } else {
            $a_desc = $this->input->post('a_desc');
            if (is_null($is_copy_exist)==false) {
                $query = "insert into tb_award (a_copy,a_id,a_year,a_desc,a_status,color_backg,color_text,color_btbackg,color_bttext,award_start,award_end,judge_start,judge_end,judge_status,reaward_start,reaward_end,win_date,win_status,work_price,trophy_price,award_descript,award_picture,descript_1,descript_2,descript_3,descript_4,descript_5,descript_6,is_del)
                select {$a_copy},{$a_year},'{$a_year}','{$a_desc}',a_status,color_backg,color_text,color_btbackg,color_bttext,award_start,award_end,judge_start,judge_end,judge_status,reaward_start,reaward_end,win_date,win_status,work_price,trophy_price,award_descript,award_picture,descript_1,descript_2,descript_3,descript_4,descript_5,descript_6,is_del from tb_award where a_id = '{$a_copy}'";
                $insert = $this->db->query($query);

                $group_query = "insert into tb_judge_group (a_id,jg_type) values ({$a_year},1),({$a_year},2),({$a_year},3)";
                $group_insert = $this->db->query($group_query);

                $judge_query = "insert into tb_judge (jg_id,a_id,j_picture,j_company,j_access,j_password) select jg_id,{$a_year},j_picture,j_company,j_access,j_password from tb_judge where a_id = {$a_copy}";
                $judge_insert = $this->db->query($judge_query);

                $judge_update = "update tb_judge as a left join tb_judge_group as b on a.jg_id = b.jg_id left join tb_judge_group as c on a.a_id = c.a_id and b.jg_type = c.jg_type set a.jg_id = c.jg_id where c.jg_id is not null and a.a_id = {$a_year}";
                $judge_update_result = $this->db->query($judge_update);
            } else {
                $a_data = [
                    'a_id'=>$a_year,
                    'a_year'=>$a_year,
                    'a_desc'=>$a_desc
                    ];
                $query = $this->db->insert('tb_award', $a_data);
                $group_query = "insert into tb_judge_group (a_id,jg_type) values ({$a_year},1),({$a_year},2),({$a_year},3)";
                $group_insert = $this->db->query($group_query);
            }
            if ($a_status == 1) {
                $query = "update tb_award set a_status = (a_id = {$a_year})";
                $res = $this->db->query($query);
            }
            $result = $this->get_award();
            $data = $result['data'];
            $cnt_affected = $this->db->affected_rows();
            $status = $cnt_affected>0?"00":"09";
            $msg = $status=="00"?"success":"failed";
            if ($status=="00") {
                $this->admin_model->logging_admin("어워드 생성 (id: {$a_year})");
            }
        }
        return class_return_refactoring($status, $data, $msg);
    }

    public function modify_award($award_picture=false)
    {
        $a_id = $this->input->post('a_id');
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        $data = ["a_id"=>$a_id];
        $status = "19";
        $msg = "no result.";
        $cnt_affected=0;
        if (is_null($is_exist)==false) {
            $color_backg = $this->input->post('color_backg');
            $color_text = $this->input->post('color_text');
            $color_btbackg = $this->input->post('color_btbackg');
            $color_btline = $this->input->post('color_btline');
            $color_bttext = $this->input->post('color_bttext');
            $award_start = $this->input->post('award_start');
            $award_end = $this->input->post('award_end');
            $judge_start = $this->input->post('judge_start');
            $judge_end = $this->input->post('judge_end');
            $judge_status = $this->input->post('judge_status');
            $reaward_start = $this->input->post('reaward_start');
            $reaward_end = $this->input->post('reaward_end');
            $win_date = $this->input->post('win_date');
            $win_status = $this->input->post('win_status');
            $work_price = $this->input->post('work_price');
            $trophy_price = $this->input->post('trophy_price');
            $award_route = $this->input->post('award_route');
            $award_descript = $this->input->post('award_descript');

            $a_data = [
                'color_backg'=>$color_backg,
                'color_text'=>$color_text,
                'color_btbackg'=>$color_btbackg,
                'color_btline'=>$color_btline,
                'color_bttext'=>$color_bttext,
                'award_start'=>$award_start,
                'award_end'=>$award_end,
                'judge_start'=>$judge_start,
                'judge_end'=>$judge_end,
                'judge_status'=>$judge_status,
                'win_date'=>$win_date,
                'win_status'=>$win_status,
                'reaward_start'=>$reaward_start,
                'reaward_end'=>$reaward_end,
                'work_price'=>$work_price,
                'trophy_price'=>$trophy_price,
                'award_route'=>$award_route,
                'award_descript'=>$award_descript
            ];
            if ($award_picture) {
                $a_data['award_picture'] = $award_picture;
            }
            $where = "a_id = '{$a_id}'";
            $query = $this->db->update('tb_award', $a_data, $where);
            $cnt_affected = $query;//$this->db->affected_rows();
            $status = $cnt_affected>0?"00":"19";
            $msg = $status=="00"?"success":"faild to update.";
            $result = $this->get_award($a_id);
            $data = $result['data'];
            if ($status=="00") {
                $this->admin_model->logging_admin("어워드 수정 (id: {$a_id})");
            }
        }
        return class_return_refactoring($status, $data, $msg);
    }

    public function onair_award()
    {
        $a_id = $this->input->post('a_id');
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        $data = ["a_id"=>$a_id];
        $status = "19";
        $msg = "no result.";
        $cnt_affected=0;
        if (is_null($is_exist)==false) {
            $a_status = $this->input->post('a_status');

            // $a_data = [
            //     'a_status'=>$a_status,
            // ];
            // $where = "a_id = '{$a_id}'";
            // $query = $this->db->update('tb_award', $a_data, $where);

            $query = "update tb_award set a_status = (a_id = {$a_id})";
            $res = $this->db->query($query);
            // $row = $res->row_array();

            $cnt_affected = $this->db->affected_rows();
            $status = $cnt_affected>0?"00":"19";
            $msg = $status=="00"?"success":"faild to update.";
            $result = $this->get_award($a_id);
            $data = $result['data'];
            if ($status=="00") {
                $this->admin_model->logging_admin("어워드 온에어 (id: {$a_id})");
            }
        }
        return class_return_refactoring($status, $data, $msg);
    }
    
    public function modify_descript_award()
    {
        $a_id = $this->input->post('a_id');
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        $status = "19";
        $data = [];
        if (is_null($is_exist)==false) {
            $descript_1 = $this->input->post('descript_1');
            $descript_2 = $this->input->post('descript_2');
            $descript_3 = $this->input->post('descript_3');
            $descript_4 = $this->input->post('descript_4');
            $descript_5 = $this->input->post('descript_5');
            $descript_6 = $this->input->post('descript_6');
            $a_data = [
                'descript_1'=>$descript_1,
                'descript_2'=>$descript_2,
                'descript_3'=>$descript_3,
                'descript_4'=>$descript_4,
                'descript_5'=>$descript_5,
                'descript_6'=>$descript_6
                ];
            $where = "a_id = '{$a_id}'";
            $query = $this->db->update('tb_award', $a_data, $where);
            $cnt_affected = $query;// $this->db->affected_rows();
            $status = $cnt_affected>0?"00":"19";
            $result = $this->get_award($a_id);
            $data = $result['data'];
            if ($status=="00") {
                $this->admin_model->logging_admin("어워드 상세내용 변경 (id: {$a_id})");
            }
        }
        return class_return_refactoring($status, $data);
    }

    public function remove_award()
    {
        /*
        1. 출품작 검사하기, 1개라도 있으면 삭제불가
        2. 출품 심사위원 삭제하기
        3. 수상클래스 삭제하기
        4. 출품카테고리 삭제하기
        5. 연감/갤러리 삭제하기
        6. 팝업 삭제하기
        7. 어워드 삭제하기
        */
        $a_id = $this->input->post("a_id");
        $workoutput_query = "select * from tb_work where a_id = {$a_id}";
        $workoutput_result = $this->db->query($workoutput_query);
        $count_workoutput = $workoutput_result->num_rows();
        $status = "39";
        $data = [];
        $msg = "연관된 출품작이 {$count_workoutput}건이 있어 삭제할 수 없습니다.";
        if ($count_workoutput==0) {
            $remove_judge_query = "delete from tb_judge where a_id = {$a_id}";
            $remove_judge_result = $this->db->query($remove_judge_query);

            $remove_judgegroup_query = "delete from tb_judge_group where a_id = {$a_id}";
            $remove_judgegroup_result = $this->db->query($remove_judgegroup_query);

            $remove_category_query = "delete from tb_category where a_id = {$a_id}";
            $remove_category_result = $this->db->query($remove_category_query);

            $remove_abord_query = "delete from tb_aboard where a_id = {$a_id}";
            $remove_abord_result = $this->db->query($remove_abord_query);

            $remove_popup_query = "delete from tb_popup where a_id = {$a_id}";
            $remove_popup_result = $this->db->query($remove_popup_query);

            $remove_prize_query = "delete from tb_prize where a_id = {$a_id}";
            $remove_prize_result = $this->db->query($remove_prize_query);

            $remove_award_query = "delete from tb_award where a_id = {$a_id}";
            $remove_award_result = $this->db->query($remove_award_query);
            $affected_cnt += $remove_award_result;//$this->db->affected_rows();

            if ($affected_cnt>0) {
                $msg = "성공적으로 삭제되었습니다.";
                $status = "00";
                $result = $this->get_award();
                $data = $result['data'];
            }
        }
        $result = class_return_refactoring($status, $data, $msg);
        return $result;
    }

    public function alter_award($a_id)
    {
        $msg = "필드를 찾을 수 없습니다.";
        $status = "19";
        $data = [];
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        if ($field==''||$value=='') {
            $result = class_return_refactoring($status, $data, $msg);
            return $result;
        }
        $query = "SHOW COLUMNS FROM tb_award LIKE '{$field}'";
        $query = $this->db->query($query);
        $result = $query->num_rows();
        if ($result>0) {
            $data = [$field=>$value];
            $where = ['a_id'=>$a_id];
            $query = $this->db->update('tb_award', $data, $where);
            $cnt_affected = $query;//$this->db->affected_rows();
            $status = $cnt_affected>0?"00":"19";
            $msg = $cnt_affected>0?"success":"해당값으로 적용할 수 없습니다.";
            $data = $cnt_affected>0?$this->get_award_by_field("a_id", $a_id):$data;
        }
        $result = class_return_refactoring($status, $data, $msg);
        return $result;
    }

    public function get_judge($a_id, $j_id=false)
    {
        $where["a_id"] = $a_id;//award설정하기
        if ($j_id === false) {
            //$where["p_status"] = 9;
            $query = $this->db->get_where('tb_judge', $where);
            $data = $query->result_array();
            $status = "00";
        } else {
            $where["j_id"] = $j_id;
            $query = $this->db->get_where('tb_judge', $where);
            $data = $query->row_array();
            $cnt_affected = is_null($data) ? 0 : count($data);
            $status = $cnt_affected>0?"00":"29";
        }
        return class_return_refactoring($status, $data);
    }

    public function get_judge_route($route)
    {
        $where["j_access"] = $route;
        $query = $this->db->get_where('tb_judge', $where);
        $data = $query->result_array();
        $status = "00";
        return class_return_refactoring($status, $data);
    }

    public function create_judge()
    {
        $a_id = $this->input->post('a_id');
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        
        $data = [];
        $msg = "can't found award";
        $status = "09";

        if (is_null($is_exist)==false) {
            $a_id = $this->input->post('a_id');
            $jg_id = $this->input->post('jg_id');
            $j_name = $this->input->post('j_name');
            $j_company = $this->input->post('j_company');
            $j_job = $this->input->post('j_job');
            $j_descript = $this->input->post('j_descript');
            $j_token = $this->input->post('j_token');
            $j_access = $this->input->post('j_access');
            $j_password = $this->input->post('j_password');
            $j_data = [
                'j_name'=>$j_name,
                'j_job'=>$j_job,
                'jg_id'=>$jg_id,
                'a_id'=>$a_id,
                'j_company'=>$j_company,
                'j_token'=>$j_token,
                'j_descript'=>$j_descript,
                'j_access'=>$j_access
                ];
            /*
            if ($j_token=="1") {
                $j_access = $this->create_access();
                $j_data['j_access'] = $j_access;
            }
            */
            $is_check_access = $this->get_judge_by_field('j_access', $j_access);
            $status = "09";
            if ($j_access != NULL && is_null($is_check_access)==false) {
                $msg = "이미 존재하는 토큰 링크입니다.";
                $data = [];
            } else {
                if ($j_password!="") {
                    $j_data['j_password'] = $j_password;
                }
        
                $query = $this->db->insert('tb_judge', $j_data);
                $j_id = $this->db->insert_id();
                $result = $this->get_judge($a_id, $j_id);
                $data = $result['data'];
                $status = $j_id>0?"00":"09";
                $msg = "";
                if ($status=="00") {
                    $this->admin_model->logging_admin("심사위원 생성 (id: {$j_id})");
                }
            }
        }
        return class_return_refactoring($status, $data, $msg);
    }
    
    public function modify_judge()
    {
        $j_id = $this->input->post('j_id');
        $is_exist = $this->get_judge_by_field('j_id', $j_id);
        $data = [];
        if (is_null($is_exist)==false) {
            $jg_id = $this->input->post('jg_id');
            $a_id = $this->input->post('a_id');
            $j_name = $this->input->post('j_name');
            $j_job = $this->input->post('j_job');
            $j_descript = $this->input->post('j_descript');
            $j_company = $this->input->post('j_company');
            $j_token = $this->input->post('j_token');
            $j_access = $this->input->post('j_access');
            $j_password = $this->input->post('j_password');
            $j_data = [
                'j_name'=>$j_name,
                'j_job'=>$j_job,
                'j_company'=>$j_company,
                'j_token'=>$j_token,
                'j_descript'=>$j_descript,
                'j_access'=>$j_access
            ];

            $access = ["j_id !="=>$j_id,"j_access"=>$j_access];
            $is_check_access = $this->get_judge_by_fields($access);
            $status = "19";
            if ($j_access != NULL && is_null($is_check_access)==false) {
                $msg = "이미 존재하는 토큰 링크입니다.";
                $data = ['token' => 'duplicate'];
            } else {
                /*
                if ($j_token=="1"&&$is_exist['j_token']=="0") {
                    $j_access = $this->create_access();
                    $j_data['j_access'] = $j_access;
                }
                */

                // if ($j_password!="") {
                    $j_data['j_password'] = $j_password;
                // }

                $msg = "cannot founded";
                $where = "j_id = '{$j_id}'";
                $query = $this->db->update('tb_judge', $j_data, $where);
                $cnt_affected = $query;//$this->db->affected_rows();
                $status = $cnt_affected>0?"00":"19";
                $status = "00";
                $result = $this->get_judge($a_id, $j_id);
                $data = $result['data'];
                if ($status=="00") {
                    $this->admin_model->logging_admin("심사위원 수정 (id: {$j_id})");
                }
            }
        }
        return class_return_refactoring($status, $data, $msg);
    }
    
    public function create_access()
    {
        $CAP_ARR = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
        $access = "";
        for ($i=0;$i<8;$i++) {
            $rand = rand(0, count($CAP_ARR)-1);
            $access .= $CAP_ARR[$rand];
        }

        $table = "tb_judge";
        $is_exist = $this->award_model->get_table_by_field($table, 'j_access', $access);
        if (is_null($is_exist)==false) {
            return $this->create_access();
        } else {
            return $access;
        }
    }

    public function update_jpicture($j_id, $img_path)
    {
        $img_data = ["j_picture"=>$img_path];
        $this->db->set($img_data);
        $this->db->where('j_id', $j_id);
        $query = $this->db->update('tb_judge');
    }

    public function remove_judge()
    {
        $table = "tb_judge";
        $j_id = $this->input->input_stream('j_id');
        $is_exist = $this->award_model->get_table_by_field($table, 'j_id', $j_id);
        $status = "39";
        $cnt_affected = 0;
        $data = [];
        if (is_null($is_exist)==false) {
            $this->db->where('j_id', $j_id);
            $this->db->delete($table);
            $cnt_affected = $this->db->affected_rows();
            $status = $cnt_affected>0?"00":"39";
            $data = ["count"=>$cnt_affected];
            if ($status=="00") {
                $this->admin_model->logging_admin("심사위원 삭제 (id: {$j_id})");
            }
        }

        return class_return_refactoring($status, $data);
    }

    public function get_category($a_id, $c_id=false)
    {
        $where["is_del"] = 'N';
        $where["a_id"] = $a_id;
        if ($c_id === false) {
            $where["cp_id"] = '0';
            $query = $this->db->get_where('tb_category', $where);
            $data = $query->result_array();
            $status = "00";
        } else {
            $where["c_id"] = $c_id;
            $query = $this->db->get_where('tb_category', $where);
            $data = $query->row_array();
            $cnt_affected = is_null($data) ? 0 : count($data);
            $status = $cnt_affected>0?"00":"29";
        }
        return class_return_refactoring($status, $data);
    }

    public function get_child_category($a_id, $cp_id=false)
    {
        $where["is_del"] = 'N';
        $where["a_id"] = $a_id;
        $where["cp_id !="] = '0';
        if ($cp_id === false) {
            $query = $this->db->order_by('c_order', 'asc')->get_where('tb_category', $where);
            $data = $query->result_array();
            $status = "00";
        } else {
            $where["cp_id"] = $cp_id;
            $query = $this->db->order_by('c_order', 'asc')->get_where('tb_category', $where);
            $data = $query->result_array();
            $cnt_affected = is_null($data) ? 0 : count($data);
            $status = $cnt_affected>0?"00":"29";
        }
        return class_return_refactoring($status, $data);
    }

    public function get_category_info($c_id)
    {
        $where["is_del"] = 'N';
        $where["c_id"] = $c_id;
        $query = $this->db->get_where('tb_category', $where);
        $data = $query->result_array();
        $status = "00";
        return class_return_refactoring($status, $data);
    }

    public function create_category($cp_id=0)
    {
        $a_id = $this->input->post('a_id');
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        $msg = "";
        $status = "09";
        $cnt_affected = 0;
        $data = [];
        if (is_null($is_exist)==false) {
            $c_start = $this->input->post('c_start');
            $c_end = $this->input->post('c_end');
            $c_title = $this->input->post('c_title');
            $c_sector = $this->input->post('c_sector');
            $c_describe = $this->input->post('c_describe');
            $c_exemple = $this->input->post('c_exemple');
            $c_data = [
                'a_id'=>$a_id,
                'c_start'=>$c_start,
                'c_end'=>$c_end,
                'c_title'=>$c_title,
                'c_sector'=>$c_sector,
                'c_describe'=>$c_describe,
                'c_exemple'=>$c_exemple,
                'cp_id'=>$cp_id
                ];
            $query = $this->db->insert('tb_category', $c_data);
            $c_id = $this->db->insert_id();
            $cnt_affected = $this->db->affected_rows();
            $status = $c_id>0?"00":"09";
            $result = $this->get_category($a_id, $c_id);
            $data = $result['data'];
            if ($status=="00") {
                $this->admin_model->logging_admin("카테고리 생성 (id: {$c_id})");
            }
        }
        return class_return_refactoring($status, $data);
    }

    public function modify_category()
    {
        $table = "tb_category";
        $c_id = $this->input->post('c_id');
        $is_exist = $this->get_table_by_field($table, "c_id", $c_id);
        $msg = "category is not exist";
        $status = "09";
        $cnt_affected = 0;
        $data = [];
        if (is_null($is_exist)==false) {
            $a_id = $is_exist['a_id'];
            $c_start = $this->input->post('c_start');
            $c_end = $this->input->post('c_end');
            $c_title = $this->input->post('c_title');
            $c_sector = $this->input->post('c_sector');
            $c_describe = $this->input->post('c_describe');
            $c_exemple = $this->input->post('c_exemple');
            $c_order = $this->input->post('c_order');
            $c_data = [
                    //'a_id'=>$a_id,
                    'c_start'=>$c_start,
                    'c_end'=>$c_end,
                    'c_title'=>$c_title,
                    'c_sector'=>$c_sector,
                    'c_describe'=>$c_describe,
                    'c_exemple'=>$c_exemple,
                    'c_order'=>$c_order
                    ];
            $where = "c_id = '{$c_id}'";
            $query = $this->db->update($table, $c_data, $where);
            $cnt_affected = $query;//$this->db->affected_rows();
            $status = $cnt_affected>0?"00":"19";
            $result = $this->get_category($a_id, $c_id);
            $data = $result['data'];
            if ($status=="00") {
                $this->admin_model->logging_admin("카테고리 수정 (id: {$c_id})");
            }
        }
        return class_return_refactoring($status, $data, $msg);
    }

    public function modify_category_order()
    {
        $table = "tb_category";
        $a_id = $this->input->post('a_id');
        $c_id = $this->input->post('c_id');
        $msg = "category is not exist";
        $status = "09";
        $cnt_affected = 0;
        $data = [];

        $c_order = $this->input->post('c_order');
        $c_data = [
            'c_order'=>$c_order
        ];
        $where = "c_id = '{$c_id}'";
        $query = $this->db->update($table, $c_data, $where);
        $cnt_affected = $this->db->affected_rows();
        $status = $cnt_affected>0?"00":"19";
        $result = $this->get_category($a_id, $c_id);
        $data = $result['data'];
        if ($status=="00") {
            $this->admin_model->logging_admin("카테고리 순서 수정 (id: {$c_id})");
        }
        return class_return_refactoring($status, $data, $msg);
    }

    public function fetch_category($field, $value)
    {
        $table = "tb_category";
        $c_id = $this->input->input_stream('c_id');
        $is_exist = $this->get_table_by_field($table, 'c_id', $c_id, true);
        $status = "19";
        $data = [];
        if (is_null($is_exist)==false) {
            $c_data = [
                $field=>$value
                ];
            $where = "c_id = '{$c_id}'";
            $query = $this->db->update($table, $c_data, $where);
            $cnt_affected = $this->db->affected_rows();
            $status = $cnt_affected>0?"00":"19";
            $result = $this->get_category($is_exist['a_id']);
            $data = $cnt_affected>0?$result['data']:[];
            if ($status=="00") {
                $this->admin_model->logging_admin("카테고리 상태 변경 ({$field}->{$value}, id: {$c_id})");
            }
        }
        return class_return_refactoring($status, $data);
    }

    public function remove_category()
    {
        $data = $this->fetch_category("is_del", "Y");
        return $data;
    }

    public function get_prize($a_id, $z_id=false)
    {
        $where["is_del"] = 'N';
        $where["a_id"] = $a_id;
        if ($z_id === false) {
            $query = $this->db->get_where('tb_prize', $where);
            $data =  $query->result_array();
            $status = "00";
        } else {
            $where["z_id"] = $z_id;
            $query = $this->db->get_where('tb_prize', $where);
            $data = $query->row_array();
            
            $cnt_affected = is_null($data) ? 0 : count($data);
            $status = $cnt_affected>0?"00":"29";
        }
        return class_return_refactoring($status, $data);
    }

    public function create_prize()
    {
        $a_id = $this->input->post('a_id');
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        $msg = "";
        $status = "09";
        $cnt_affected = 0;
        $data = [];
        if (is_null($is_exist)==false) {
            $z_title = $this->input->post('z_title');
            $z_picture = $this->input->post('z_picture');
            $z_only = $this->input->post('z_only');
            $z_data = [
                'a_id'=>$a_id,
                'z_title'=>$z_title,
                'z_picture'=>$z_picture,
                'z_only'=>$z_only
                ];
            $query = $this->db->insert('tb_prize', $z_data);
            $z_id = $this->db->insert_id();
            $status = $z_id>0?"00":"09";
            $result = $this->get_prize($a_id, $z_id);
            $data = $result['data'];
            if ($status=="00") {
                $this->admin_model->logging_admin("수상클래스 데이터 생성 (id: {$z_id})");
            }
        }
        
        return class_return_refactoring($status, $data);
    }

    public function modify_prize()
    {
        $table = "tb_prize";
        $z_id = $this->input->post('z_id');
        $is_exist = $this->get_table_by_field($table, 'z_id', $z_id, true);
        $status = "19";
        $cnt_affected = 0;
        $data = [];
        if (is_null($is_exist)==false) {
            $a_id = $is_exist['a_id'];//$this->input->post('a_id');
            $z_title = $this->input->post('z_title');
            $z_picture = $this->input->post('z_picture');
            $z_only = $this->input->post('z_only');
            $z_data = [
                //'a_id'=>$a_id,
                'z_title'=>$z_title,
                'z_picture'=>$z_picture,
                'z_only'=>$z_only
                ];
            $where = "z_id = '{$z_id}'";
            $query = $this->db->update('tb_prize', $z_data, $where);
            $cnt_affected = $query;//$this->db->affected_rows();
            $result = $this->get_prize($a_id, $z_id);
            $data = $result['data'];
            $status = $cnt_affected>0?"00":"19";
            if ($status=="00") {
                $this->admin_model->logging_admin("수상클래스 데이터 변경 (id: {$z_id})");
            }
        }
        
        return class_return_refactoring($status, $data);
    }

    public function fetch_prize($field, $value)
    {
        $table = "tb_prize";
        $z_id = $this->input->input_stream('z_id');
        $is_exist = $this->get_table_by_field($table, 'z_id', $z_id, true);
        $status = "19";
        $cnt_affected = 0;
        $data = [];

        if (is_null($is_exist)==false) {
            $z_data = [
                $field=>$value
                ];
            $where = "z_id = '{$z_id}'";
            $query = $this->db->update($table, $z_data, $where);
            $cnt_affected = $this->db->affected_rows();
            $result = $this->get_prize($is_exist['a_id']);
            $data = $result['data'];
            $status = $cnt_affected>0?"00":"19";
            if ($status=="00") {
                $this->admin_model->logging_admin("수상클래스 상태 변경 ({$field} -> {$value} , id: {$z_id})");
            }
        }

        return class_return_refactoring($status, $data);
    }

    public function update_prize($z_id, $img_path)
    {
        $img_data = ["z_picture"=>$img_path];
        $this->db->set($img_data);
        $this->db->where('z_id', $z_id);
        $query = $this->db->update('tb_prize');
    }

    public function remove_prize()
    {
        $data = $this->fetch_prize("is_del", "Y");
        return $data;
    }

    public function get_receiv_prize($a_id, $z_id=false)
    {
        // $query = "select A.*,B.zr_id,B.u_no from TB_PRIZE as A right join TB_PRIZE_RECEIV as B on A.z_id = B.z_id where A.a_id = {$a_id}";
        // if ($z_id!=false) {
        //     $query .= " and A.z_id = {$z_id}";
        // }
        // $result = $this->db->query($query);
        // return $result->result_array();
        $query = "select A.*,B.zr_id,B.u_no from TB_PRIZE as A right join TB_PRIZE_RECEIV as B on A.z_id = B.z_id where A.a_id = {$a_id}";
        if ($z_id!=false) {
            $query .= " and A.z_id = {$z_id}";
        }
        $result = $this->db->query($query);
        $status = "00";
        $data =  $result->result_array();
        return class_return_refactoring($status, $data);
    }

    public function get_receiv_prize_detail($a_id, $zr_id=false)
    {
        $query = "select A.*,B.zr_id,B.u_no from TB_PRIZE as A left join TB_PRIZE_RECEIV as B on A.z_id = B.z_id where A.a_id = {$a_id}";
        if ($zr_id!=false) {
            $query .= " and B.zr_id = {$zr_id}";
        }
        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function create_receiv_prize()
    {
        $a_id = $this->input->post('a_id');
        $z_id = $this->input->post('z_id');
        $w_id = $this->input->post('w_id');
        $u_no = $this->input->post('u_no');
        $e_id = $this->input->post('e_id');
        $zr_data = [
            'a_id'=>$a_id,
            'z_id'=>$z_id,
            'w_id'=>$w_id,
            'u_no'=>$u_no,
            'e_id'=>$e_id
            ];
        $query = $this->db->insert('TB_PRIZE_RECEIV', $zr_data);
        $zr_id = $this->db->insert_id();
        return $this->get_receiv_prize_detail($a_id, $zr_id);
    }

    public function winner_class($a_id)
    {
        $category_query = "select * from tb_category AS a left JOIN (SELECT COUNT(*) AS cnt_cp,cp_id FROM tb_category GROUP BY cp_id) AS b ON a.c_id = b.cp_id WHERE a.cp_id = 0 AND a.a_id = {$a_id} AND b.cnt_cp > 0";
        $category_result = $this->db->query($category_query);
        $data = [];
        $parent_cnt = 0;
        foreach ($category_result->result() as $row) {
            $subcategory_query = "select * from tb_category where cp_id = {$row->c_id}";
            $subcategory_result = $this->db->query($subcategory_query);
            $data[$parent_cnt] = ["c_title"=>$row->c_title,"c_id"=>$row->c_id];
            $child_cnt = 0;
            foreach ($subcategory_result->result() as $subrow) {
                $data[$parent_cnt]["child"][$child_cnt] = ["c_title"=>$subrow->c_title,"c_id"=>$subrow->c_id];
                $child_cnt++;
            }
            $parent_cnt++;
        }
        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"수상 내역이 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }

    public function winner_received($a_id)
    {
        $where_c_id = 'a.c_id';
        $where_r_status = '';
        if ((int)$a_id > 2019) {
            $where_c_id = 'c.c_id';
            $where_r_status = 'AND a.r_status IN (1, 9)';
        }
        $data = [];
        $received_query = "SELECT a.*,b.z_title,c.e_id,c.w_title,c.c_id,c.w_thumbnail,c.old_client,c.w_client,e.c_title,e.cp_id,concat(d.wo_url,d.wo_name) AS resource,f.e_company FROM tb_prize_receiv AS a
        LEFT JOIN tb_prize AS b ON a.z_id = b.z_id
        LEFT JOIN tb_work AS c ON a.w_id = c.w_id
        LEFT JOIN tb_work_output AS d ON c.w_id = d.w_id
        LEFT JOIN tb_category AS e ON {$where_c_id} = e.c_id
        LEFT JOIN tb_enterprise AS f ON c.e_id = f.e_id
        WHERE a.a_id = {$a_id} {$where_r_status}
        GROUP BY d.w_id HAVING MIN(d.wo_id) ORDER By e.c_id";
        $received_result = $this->db->query($received_query);
        $data =  $received_result->result_array();
        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"수상 내역이 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }

    public function winner_received_main($a_id, $class, $c_type)
    {
        $join_c_id = 'a.c_id';
        $where_r_status = '';
        if ((int)$a_id > 2019) {
            $join_c_id = 'd.c_id';
            $where_r_status = 'AND a.r_status IN (1, 9)';
            if ($class == 'G') {
                $class = 'Grand Prix';
            } else if ($class == 'W') {
                $class = 'Winner';
            }
        }
        $received_main_query = "SELECT a.*,c.cp_id,c.c_title,d.c_id,d.w_title,d.w_thumbnail,e.e_id,e.e_company,CONCAT(f.wo_url,f.wo_name) AS resource FROM tb_prize_receiv AS a
        LEFT JOIN tb_prize AS b ON a.z_id = b.z_id
        LEFT JOIN tb_work AS d ON a.w_id = d.w_id
        LEFT JOIN tb_work_output AS f ON d.w_id = f.w_id
        LEFT JOIN tb_category AS c ON {$join_c_id} = c.c_id
        LEFT JOIN tb_enterprise AS e ON d.e_id = e.e_id
        WHERE a.a_id = {$a_id} AND b.z_title = '{$class}' AND c.c_type = {$c_type} {$where_r_status}
        ORDER BY RAND() LIMIT 0,4";
        // print_r2($received_main_query);
        $received_main_result = $this->db->query($received_main_query);
        $data =  $received_main_result->result_array();
        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"수상 내역이 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }

    public function winner_list($a_id, $c_type)
    {
        $where_c_id = 'a.c_id';
        if ((int)$a_id > 2019) {
            $where_c_id = 'b.c_id';
        }
        $data = [];
        $win_class_query = "select c_title,c_describe,c_id,cp_id from tb_category where a_id = {$a_id} and c_type = {$c_type} and cp_id != 0 order by rand()";
        $win_class_result = $this->db->query($win_class_query);
        foreach ($win_class_result->result() as $row) {
            $win_query = "SELECT a.zr_id,z.z_title,a.z_id,b.w_id,b.w_title,b.w_thumbnail,d.e_company,CONCAT(c.wo_url,c.wo_name) AS resource from tb_prize_receiv AS a
            LEFT JOIN tb_prize AS z ON a.z_id = z.z_id
            LEFT JOIN tb_work AS b ON a.w_id = b.w_id
            LEFT JOIN tb_work_output AS c ON b.w_id = c.w_id
            LEFT JOIN tb_enterprise AS d ON b.e_id = d.e_id
            WHERE a.a_id = {$a_id} AND {$where_c_id} = {$row->c_id}
            GROUP BY c.w_id
            ORDER BY
            (case when ASCII(SUBSTRING(b.w_title, 1)) = 0 then 9
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 33 and ASCII(SUBSTRING(b.w_title, 1)) <= 47) then 1
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 58 and ASCII(SUBSTRING(b.w_title, 1)) <= 64) then 2
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 91 and ASCII(SUBSTRING(b.w_title, 1)) <= 96) then 3
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 123 and ASCII(SUBSTRING(b.w_title, 1)) <= 126) then 4
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 48 and ASCII(SUBSTRING(b.w_title, 1)) <= 57) then 5
                when ASCII(SUBSTRING(b.w_title, 1)) > 128 then 7
                when ASCII(SUBSTRING(b.w_title,1)) = 32 then 8
                else 6 end ),binary(b.w_title)";

            $win_result = $this->db->query($win_query);
            foreach ($win_result->result() as $row2) {
                $row->child[] = $row2;
            }
            $data[] = $row;
        }
        
        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"작품 정보가 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }
    /*
    public function winner_list($c_id)
    {
        $winner_query = "SELECT * FROM tb_prize_receiv AS a
        LEFT JOIN tb_prize AS b ON a.z_id = b.z_id
        LEFT JOIN tb_work AS c ON a.w_id = c.w_id
        WHERE a.c_id = {$c_id}";
        $winner_result = $this->db->query($winner_query);
        $data = [];
        foreach ($winner_result->result() as $row) {
            $data[] = $row;
        }
        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"수상 내역이 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }*/

    public function winner_detail($w_id, $a_id)
    {
        $join_c_id = 'b.c_id';
        if ($a_id != false && (int)$a_id > 2019) {
            $join_c_id = 'a.c_id';
        }
        // $winner_info = $this->award_model->get_table_by_field("tb_work", 'w_id', $w_id);
        $winner_info_query = "SELECT a.*,b.c_id,c.e_company,d.cp_id,d.c_type,d.c_title,e.z_title
            FROM tb_work AS a
            LEFT JOIN tb_prize_receiv AS b ON a.w_id = b.w_id
            LEFT JOIN tb_enterprise AS c ON a.e_id = c.e_id
            LEFT JOIN tb_category AS d ON {$join_c_id} = d.c_id
            LEFT JOIN tb_prize AS e ON b.z_id = e.z_id
            WHERE a.w_id = {$w_id}";
        $winner_info_result = $this->db->query($winner_info_query);
        $winner_info = $winner_info_result->row_array();

        $cptitle_query = "select * from tb_category where c_id = {$winner_info['cp_id']}";
        $cptitle_result = $this->db->query($cptitle_query)->result();
        $winner_info['cp_title'] = $cptitle_result[0]->c_title;

        $winner_output_query = "SELECT *,CONCAT(wo_url,wo_name) AS resource
            FROM tb_work_output
            WHERE w_id = {$w_id}";
        $winner_output_result = $this->db->query($winner_output_query);
        $data['info'] = $winner_info;
        foreach ($winner_output_result->result() as $row) {
            $data['output'][] = $row;
        }
        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"작품 정보가 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }

    public function cnt_enterprise_enroll($a_id, $e_id)
    {
        $query = "SELECT SUM(a.total_category) as total_enroll FROM tb_work as a left join tb_payment as b on a.p_no = b.p_no WHERE a.a_id = {$a_id} AND a.e_id = {$e_id} and b.p_status = 9";
        $query = $this->db->query($query);
        $row = $query->row_array();
        return $row['total_enroll'];
    }

    public function get_award_dashboard($a_id)
    {
        $is_exist = $this->get_award_by_field('a_id', $a_id);
        
        $data = [];
        $msg = "can't found award";
        $status = "09";
        $data = [];
        if (is_null($is_exist)==false) {
            $query = "SELECT 
                (select COUNT(*) from tb_category WHERE cp_id = 0 AND a_id = {$a_id}) AS cnt_category,
                (SELECT COUNT(*) FROM tb_category WHERE cp_id != 0 AND a_id = {$a_id}) AS cnt_sub_category,
                (SELECT COUNT(*) FROM tb_prize WHERE a_id = {$a_id}) AS cnt_prize,
                (SELECT COUNT(*) FROM tb_work WHERE w_status = 0 and a_id = {$a_id}) AS cnt_work_wait,
                (SELECT COUNT(*) FROM tb_work WHERE w_status = 1 and a_id = {$a_id}) AS cnt_work_process,
                (SELECT COUNT(*) FROM tb_work WHERE w_status = 2 and a_id = {$a_id}) AS cnt_work_finish,
                (SELECT COUNT(*) FROM tb_work WHERE w_status = 9 and a_id = {$a_id}) AS cnt_work_confirm";
            $query = $this->db->query($query);
            $row = $query->row_array();
            $data = $row;
            $status = "00";
            $msg = "";
        }
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }



    public function get_table_by_field($table, $field, $value, $isdel=false)
    {
        $where[$field] = $value;
        if ($isdel==true) {
            $where['is_del']='N';
        }
        $query = $this->db->get_where($table, $where);
        return $query->row_array();
    }

    public function get_table_by_fields($table, $where, $isdel=false)
    {
        if ($isdel==true) {
            $where['is_del']='N';
        }
        $query = $this->db->get_where($table, $where);
        return $query->row_array();
    }

    public function get_judge_by_field($field, $value)
    {
        return $this->get_table_by_field('tb_judge', $field, $value);
    }

    public function get_judge_by_fields($where)
    {
        $query = $this->db->get_where('tb_judge', $where);
        return $query->row_array();
    }

    public function get_award_by_field($field, $value)
    {
        return $this->get_table_by_field('tb_award', $field, $value);
    }

    /* 아카이브 최근년도 초기값 조회 */
    public function get_archive_year(){
        $query = "select * from tb_award where a_status != 1 order by a_id desc limit 1";
        $query = $this->db->query($query);
        $row = $query->row_array();
        return $row;
    }

    /* 아카이브 수상년도 리스트 조회 */
    public function get_archive_year_array(){
        $query = "select a_year from tb_award where a_status != 1 order by a_id desc";
        $result = $this->db->query($query)->result();
        return $result;
    }

    /* 아카이브 수상작 카테고리 목록 조회 */
    public function service_winners_category($year, $type){
        $query = "select c_id, c_title, cp_id from tb_category where a_id={$year} and c_type = {$type} and is_del = 'N' order by c_order asc";
        $result = $this->db->query($query)->result();
        return $result;
    }

    /* 수상작 카테고리 상세 조회 */
    public function winner_list_choice($a_id, $c_type, $c_id)
    {
        $where_c_id = 'a.c_id';
        if ((int)$a_id > 2019) {
            $where_c_id = 'b.c_id';
        }
        $data = [];
        $win_class_query = "select c_title,c_describe,c_id,cp_id from tb_category where c_id= {$c_id} and a_id = {$a_id} and c_type = {$c_type} and cp_id != 0";
        $win_class_result = $this->db->query($win_class_query);
        foreach ($win_class_result->result() as $row) {
            $win_query = "SELECT a.zr_id,z.z_title,a.z_id,b.w_id,b.w_title,b.w_thumbnail,d.e_company,CONCAT(c.wo_url,c.wo_name) AS resource from tb_prize_receiv AS a
            LEFT JOIN tb_prize AS z ON a.z_id = z.z_id
            LEFT JOIN tb_work AS b ON a.w_id = b.w_id
            LEFT JOIN tb_work_output AS c ON b.w_id = c.w_id
            LEFT JOIN tb_enterprise AS d ON b.e_id = d.e_id
            WHERE a.a_id = {$a_id} AND {$where_c_id} = {$c_id}
            GROUP BY c.w_id
            ORDER BY
            (case when ASCII(SUBSTRING(b.w_title, 1)) = 0 then 9
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 33 and ASCII(SUBSTRING(b.w_title, 1)) <= 47) then 1
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 58 and ASCII(SUBSTRING(b.w_title, 1)) <= 64) then 2
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 91 and ASCII(SUBSTRING(b.w_title, 1)) <= 96) then 3
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 123 and ASCII(SUBSTRING(b.w_title, 1)) <= 126) then 4
                when (ASCII(SUBSTRING(b.w_title, 1)) >= 48 and ASCII(SUBSTRING(b.w_title, 1)) <= 57) then 5
                when ASCII(SUBSTRING(b.w_title, 1)) > 128 then 7
                when ASCII(SUBSTRING(b.w_title,1)) = 32 then 8
                else 6 end ),binary(b.w_title)";

            $win_result = $this->db->query($win_query);
            foreach ($win_result->result() as $row2) {
                $row->child[] = $row2;
            }
            $data[] = $row;
        }

        $status = count($data)>0?"00":"29";
        $msg = count($data)>0?"":"작품 정보가 없습니다.";
        $result = ["status"=>$status,"data"=>$data,"msg"=>$msg];
        return $result;
    }
}