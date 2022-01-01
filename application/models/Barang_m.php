<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Barang_m extends CI_Model 
{
    public function getBarang($id = null)
    {
       

        if($id === null){
            $this->db->select("b.*,jb.jenis_barang AS namaBarang");
            $this->db->join("tabel_jenis_barang jb", "b.jenis_barang=jb.kode_barang");
            $this->db->order_by("id", "DESC");
            $result = $this->db->get("barang b")->result();
        }else{
            $this->db->select("b.*,jb.jenis_barang AS namaBarang");
            $this->db->join("tabel_jenis_barang jb", "b.jenis_barang=jb.kode_barang");
            $this->db->where("serial_number",$id);
            $this->db->order_by("b.id", "DESC");

             $result = $this->db->get("barang b")->result();
        }

        return $result;
    }

      public function cek($data)
    {
        $this->db->where($data);
        $result = $this->db->get("barang")->result();

        return $result;
    }

    public function addBarang($data)
    {
        $this->db->insert("barang", $data);
        return $this->db->affected_rows();
    }

    public function deleteBarang($id)
    {
        
        $this->db->delete("barang", ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function updateBarang($data, $id)
    {
        
        $this->db->update("barang", $data,['id' => $id]);
        return $this->db->affected_rows();
    }

  


}