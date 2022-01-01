<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Auth_m extends CI_Model 
{
    public function login($data)
    {
        $this->db->where($data);
        $result = $this->db->get("users")->result();

        return $result;
    }


    public function register($data)
    {
        $this->db->insert('users',$data);
        return $this->db->affected_rows();
    }

}