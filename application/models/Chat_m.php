<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Chat_m extends CI_Model 
{
    public function getChat($id = null)
    {
       

        if($id === null){
            $this->db->select("c.id_chat,c.id_chat,c.id_service,c.id_user,c.text,c.tgl,u.nama,u.level,c.file,c.status");
            $this->db->join("users u", "c.id_user=u.id_user");
            $this->db->order_by("tgl", "DESC");
            $result = $this->db->get("chat c")->result();
        }else{
            $this->db->select("u.nama,c.id_user,u.foto,c.tgl,c.id_chat,c.status,c.id_service,c.text,c.type,c.file");
            $this->db->join("users u", "c.id_user=u.id_user");
            $this->db->order_by("tgl", "ASC");
            $this->db->where(["id_service"=>$id]);
            $result = $this->db->get("chat c")->result();
        }

        return $result;
    }

    //   public function getChat($id = null)
    // {
       

    //     if($id !== null){    
    //         $this->db->select("c.tgl createdAt,c.id_chat id,c.status,c.text,c.type");
    //         $this->db->order_by("tgl", "DESC");
    //         $result = $this->db->get("chat c")->result();
    //     }else{
    //         $this->db->select("c.id_chat,c.id_chat,c.id_service,c.text,c.tgl,c.id_user,u.nama,u.level,c.file,c.status");
    //         $this->db->join("users u", "c.id_user=u.id_user");
    //         $this->db->order_by("tgl", "DESC");
    //         $this->db->where(["id_service"=>$id]);
    //         $result = $this->db->get("chat c")->result();
    //     }

    //     return $result;
    // }

    //   public function getUser($id = null)
    // {
       

    //     if($id !== null){
    //         $this->db->select("nama firstName ,id_user id,foto imageUrl");
    //         $this->db->where(["id_user"=>$id]);
    //         $result = $this->db->get("users")->result();
    //     }

    //     return $result;
    // }



    public function addChat($data)
    {
        $this->db->insert("chat", $data);
        return $this->db->affected_rows();
    }

    public function deleteChat($id)
    {
        
        $this->db->delete("chat", ['id_chat' => $id]);
        return $this->db->affected_rows();
    }

    public function updateChat($data, $id)
    {
        
        if (isset($data["file"])) $this->_unlinkChat($id);
        $this->db->update("chat", $data,['id_chat' => $id]);
        return $this->db->affected_rows();
    }

     // has file
    private function _unlinkChat($id)
    {
        $file = $this->db->get_where("chat",["id_chat" => $id])->row();
        
        if ($file != null) {
            if ($file->file != "") {
                $target_file = './chatFile/' . $file->file;
                unlink($target_file);
            }
        }

    }


}