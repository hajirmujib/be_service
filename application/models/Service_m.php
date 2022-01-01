<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Service_m extends CI_Model 
{
    public function getService($id = null)
    {
        if ($id === null) {
            $this->db->select("s.*,us.nama,l.seri,l.type");
            $this->db->join("users us", "s.id_user=us.id_user","left");
            $this->db->join("barang l","s.serial_number=l.serial_number","left");
            $this->db->order_by("s.tgl_masuk", "DESC");
            $result = $this->db->get("service s")->result();
        }else{
           $this->db->select("s.*,us.nama,l.seri,l.type");
            $this->db->join("users us", "s.id_user=us.id_user","left");
            $this->db->join("barang l","s.serial_number=l.serial_number","left");
            $this->db->where("id_service",$id);
            $result = $this->db->get("service s")->result();
        }

        return $result;
    }


    public function getServiceUser($id=null, $status=null)
    {
        $this->db->select("s.*,us.nama,l.seri,l.type");
        $this->db->join("users us", "s.id_user=us.id_user","left");
        $this->db->join("barang l","s.serial_number=l.serial_number","left");  
        $this->db->where(["us.id_user" => $id, "s.status" => $status]);
        $this->db->order_by("s.tgl_masuk", "DESC");
        $result = $this->db->get("service s")->result();

        return $result;
    }

     public function getServiceStatus($status=null)
    {
       $this->db->select("s.*,us.nama,l.seri,l.type");
            $this->db->join("users us", "s.id_user=us.id_user","left");
            $this->db->join("barang l","s.serial_number=l.serial_number","left");
            
        $this->db->where(["s.status" => $status]);
        $this->db->order_by("s.tgl_masuk", "DESC");
        $result = $this->db->get("service s")->result();

        return $result;
    }


    public function addService($data)
    {
        $this->db->insert('service',$data);
        return $this->db->affected_rows();
    }

    public function deleteService($id)
    {
        $this->db->delete('service', ['id_service' => $id]);
        return $this->db->affected_rows();
    }

    public function updateService($data, $id)
    {
        $this->db->update('service',$data,['id_service' => $id]);
        return $this->db->affected_rows();
    }

}