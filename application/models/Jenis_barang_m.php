<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Jenis_barang_m extends CI_Model 
{
    public function getJenis_barang($id = null)
    {
       

        if($id === null){
           
            $this->db->order_by("kode_barang", "ASC");
            $result = $this->db->get("tabel_jenis_barang")->result();
        }else{
          
             $this->db->order_by("kode_barang", "ASC");
            $this->db->where("kode_barang",$id);
             $result = $this->db->get("tabel_jenis_barang")->result();
        }

        return $result;
    }

      public function cek($data)
    {
        $this->db->where($data);
        $result = $this->db->get("tabel_jenis_barang")->result();

        return $result;
    }

    public function addJenis_barang($data)
    {

        $this->db->insert("tabel_jenis_barang", $data);
        return $this->db->affected_rows();
    }

    public function deleteJenis_barang($id)
    {
        
        $this->db->delete("tabel_jenis_barang", ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function updatejenis_barang($data, $id)
    {
        
        $this->db->update("tabel_jenis_barang", $data,['id' => $id]);
        return $this->db->affected_rows();
    }

  


}