<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AssetModel;

class Assets extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new AssetModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $model = new AssetModel();
        $data = $model->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No asset found with id ' . $id);
        }
    }

    public function create()
    {
        $model = new AssetModel();

        // Handle Fields
        $data = [
            'asset_id' => $this->request->getPost('asset_id'), // Vue uses camelCase
            'name' => $this->request->getPost('name'),
            'model' => $this->request->getPost('model'),
            'cost' => $this->request->getPost('cost'),
            'quantity' => $this->request->getPost('quantity'),
            'invoice_number' => $this->request->getPost('invoice_number'),
            'inventory_date' => $this->request->getPost('inventory_date'),
            'inventory_taker' => $this->request->getPost('inventory_taker'),
            'type' => $this->request->getPost('type') ?? 1,
        ];

        // Handle File Uploads
        $image1 = $this->request->getFile('image1_file');
        if ($image1 && $image1->isValid() && !$image1->hasMoved()) {
            $newName1 = $image1->getRandomName();
            $image1->move(FCPATH . 'uploads/assets', $newName1);
            $data['image1'] = base_url('uploads/assets/' . $newName1);
        }

        $image2 = $this->request->getFile('image2_file');
        if ($image2 && $image2->isValid() && !$image2->hasMoved()) {
            $newName2 = $image2->getRandomName();
            $image2->move(FCPATH . 'uploads/assets', $newName2);
            $data['image2'] = base_url('uploads/assets/' . $newName2);
        }

        // Insert
        if ($model->insert($data)) {
            $data['id'] = $model->getInsertID();
            return $this->respondCreated($data);
        } else {
            return $this->fail($model->errors());
        }
    }

    public function update($id = null)
    {
        $model = new AssetModel();

        // CI4 puts request params in RAW input stream for PUT, but files are tricky.
        // Usually better to use POST with _method=PUT or just standard POST for multipart updates.
        // For simplicity with multipart/form-data, we interpret POST update if ID is provided.

        // Check if exists
        if (!$model->find($id)) {
            return $this->failNotFound('No asset found with id ' . $id);
        }

        $data = [
            'asset_id' => $this->request->getPost('asset_id'),
            'name' => $this->request->getPost('name'),
            'model' => $this->request->getPost('model'),
            'cost' => $this->request->getPost('cost'),
            'quantity' => $this->request->getPost('quantity'),
            'invoice_number' => $this->request->getPost('invoice_number'),
            'inventory_date' => $this->request->getPost('inventory_date'),
            'inventory_taker' => $this->request->getPost('inventory_taker'),
            'type' => $this->request->getPost('type'),
        ];

        // Handle File Uploads (Only update if new file uploaded)
        $image1 = $this->request->getFile('image1_file');
        if ($image1 && $image1->isValid() && !$image1->hasMoved()) {
            $newName1 = $image1->getRandomName();
            $image1->move(FCPATH . 'uploads/assets', $newName1);
            $data['image1'] = base_url('uploads/assets/' . $newName1);
        }

        $image2 = $this->request->getFile('image2_file');
        if ($image2 && $image2->isValid() && !$image2->hasMoved()) {
            $newName2 = $image2->getRandomName();
            $image2->move(FCPATH . 'uploads/assets', $newName2);
            $data['image2'] = base_url('uploads/assets/' . $newName2);
        }

        // Remove nulls if you don't want to overwrite with empty
        $data = array_filter($data, function ($v) {
            return !is_null($v);
        });

        if ($model->update($id, $data)) {
            return $this->respond($data);
        } else {
            return $this->fail($model->errors());
        }
    }

    public function delete($id = null)
    {
        $model = new AssetModel();
        $data = $model->find($id);

        if ($data) {
            $model->delete($id);
            return $this->respondDeleted($data);
        } else {
            return $this->failNotFound('No asset found with id ' . $id);
        }
    }
}
