<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jenis_barang extends RestController 
{   

    function __construct()
    {
        parent::__construct();
        $this->load->model("Jenis_barang_m", "jenis_barang");
    }

    public function index_get()
    {
        $id = $this->get('kode_barang');

        if($id !== null)
        {
            $jenis_barang = $this->jenis_barang->getJenis_barang($id);
        }
        else
        {
            $jenis_barang = $this->jenis_barang->getJenis_barang();
        }

        if($jenis_barang){
            $this->response([
                'status' => true,
                'data' => $jenis_barang
            ],self::HTTP_OK);
        }else {
            $this->response([
                'status' => false,
                'message' => 'not found'
            ],self::HTTP_NOT_FOUND);
        }
    }

        public function cek_post()
    {
       

        $data =[
            'kode_barang' => $this->post('kode_barang'),
            
        ];
        $data = $this->jenis_barang->cek($data);

        if ($data) {
            $this->response([
                'status' => true,
                'data' => $data,
                'message' => 'data found'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'data not found'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function index_post()
    {
        date_default_timezone_set('Asia/Jakarta');
        $qLastId=$this->db->query("SELECT MAX(id) id FROM `tabel_jenis_barang`");
        $str=$qLastId->row();
        $convert="KB".$str->id;
       
        $data =[
            'kode_barang' => $convert,
            'jenis_barang' => $this->post('jenis_barang'),
        ];


        if ($this->jenis_barang->addJenis_barang($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new jenis_barang has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => $kode,
            ],self::HTTP_BAD_REQUEST);
        }

       
    }


    public function edit_post()
    {
        $id = $this->post('id');
        $data =[
            'jenis_barang' => $this->post('jenis_barang'),
            
        ];


        if ($this->jenis_barang->updateJenis_barang($data, $id) > 0) {
            date_default_timezone_set('Asia/Jakarta');
            $this->response([
                'status' => true,
                'message' => 'new jenis_barang has been modify'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to modify new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function delete_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ],self::HTTP_BAD_REQUEST);
        }else {
            if ($this->jenis_barang->deleteJenis_barang($id) > 0) {
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'deleted.'
                ],self::HTTP_CREATED);
            }else {
                $this->response([
                    'status' => false,
                    'message' => 'id not found!'
                ],self::HTTP_BAD_REQUEST);
            }
        }
    }


}