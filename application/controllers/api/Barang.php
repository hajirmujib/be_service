<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Barang extends RestController 
{   

    function __construct()
    {
        parent::__construct();
        $this->load->model("Barang_m", "barang");
    }

    public function index_get()
    {
        $id = $this->get('serial_number');

        if($id !== null)
        {
            $barang = $this->barang->getBarang($id);
        }
        else
        {
            $barang = $this->barang->getBarang();
        }

        if($barang){
            $this->response([
                'status' => true,
                'data' => $barang
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
            'serial_number' => $this->post('serial_number'),
            
        ];
        $data = $this->barang->cek($data);

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

        $data =[
            'serial_number' => $this->post('serial_number'),
            'seri' => $this->post('seri'),
            'type' => $this->post('type'),
            'jenis_barang' => $this->post('jenis_barang'),
         
           
        ];


        if ($this->barang->addBarang($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new barang has been added.'
            ],self::HTTP_CREATED);
        }else {
            $this->response([
                'status' => false,
                'message' => 'failed to add new data'
            ],self::HTTP_BAD_REQUEST);
        }
    }

    public function edit_post()
    {
        $id = $this->post('id');
        $data =[
            'serial_number' => $this->post('serial_number'),
            'seri' => $this->post('seri'),
            'type' => $this->post('type'),
            'jenis_barang' => $this->post('jenis_barang'),
        ];


        if ($this->barang->updateBarang($data, $id) > 0) {
            date_default_timezone_set('Asia/Jakarta');
            $this->response([
                'status' => true,
                'message' => 'new barang has been modify'
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
            if ($this->barang->deleteBarang($id) > 0) {
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