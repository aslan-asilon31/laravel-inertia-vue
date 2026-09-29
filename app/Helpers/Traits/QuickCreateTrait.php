<?php

namespace App\Helpers\Traits;

trait QuickCreateTrait
{
    public bool $showQuickCreateModal = false;
    public string $quickCreateModelProperty = ''; // Menyimpan nama properti (cth: 'modelMsWarehouse')
    public string $quickCreateColumn = '';
    public string $quickCreateProperty = '';
    public ?string $quickCreateTitle = '';
    public array $quickCreateForm = [];

    public function openQuickCreate($modelProperty = null, $column = null, $property = null, $title = 'Data Baru')
    {
        $this->quickCreateModelProperty = $modelProperty ?? '';
        $this->quickCreateColumn = $column ?? '';
        $this->quickCreateProperty = $property ?? '';
        $this->quickCreateTitle = $title;

        $this->quickCreateForm = [];
        $this->showQuickCreateModal = true;
    }

    public function saveQuickCreate()
    {
        // $this->validate([
        //     'quickCreateForm.value' => 'required|string|max:255',
        // ]);

        $inputValue = $this->quickCreateForm['value'];

        // Ambil nama kelas model secara dinamis dari properti controller (cth: $this->modelMsWarehouse)
        $modelClass = $this->{$this->quickCreateModelProperty} ?? null;

        if (!$modelClass || !class_exists($modelClass)) {
            $this->error('Model tidak ditemukan.');
            return;
        }

        // Simpan data ke database
        $newRecord = $modelClass::create([
            $this->quickCreateColumn => $inputValue
        ]);

        // Masukkan ID baru ke properti form utama secara dinamis
        data_set($this, $this->quickCreateProperty, (string) $newRecord->id);

        $this->showQuickCreateModal = false;
        $this->success('Data baru berhasil ditambahkan dan dipilih!');
    }
}
