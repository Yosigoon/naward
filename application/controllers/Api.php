<?php
class Api extends CI_Controller
{
    /* 아카이브 최근년도 초기값 조회 */
    public function get_archive_year()
    {
        $a_row = $this->award_model->get_archive_year();
        echo json_encode($a_row);
    }

    /* 아카이브 수상년도 리스트 조회 */
    public function get_archive_year_array()
    {
        $a_row = $this->award_model->get_archive_year_array();
        $yearArr = array();
        $i = 0;
        foreach ($a_row as $row) {
            $yearArr[$i] = $row->a_year;
            $i++;
        }
        echo json_encode($yearArr);
    }

    /* 아카이브 수상작 카테고리 목록 조회 */
    public function service_winners_category($year, $type)
    {
        $a_row = $this->award_model->service_winners_category($year, $type);
        $yearArr = array();
        $i = 0;
        foreach ($a_row as $row) {
            $yearArr[$i] = $row;
            $i++;
        }
        echo json_encode($yearArr);
    }

    /* 수상작 카테고리 상세 조회 */
    public function service_winners_choice($a_id, $c_type, $c_id)
    {
        $rows = $this->award_model->winner_list_choice($a_id, $c_type, $c_id);
        echo $this->result_refactoring($rows);
    }
}