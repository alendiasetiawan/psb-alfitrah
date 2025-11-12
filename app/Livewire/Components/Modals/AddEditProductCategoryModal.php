<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\Inventory\ProductCategory;
use App\Queries\Inventory\ProductCategoryQuery;
use Illuminate\Contracts\Encryption\DecryptException;

class AddEditProductCategoryModal extends Component
{
    //String
    public $modalId;
    //Boolean
    public $isEditing = false;
    //Array
    public $inputs = [
        'categoryName' => null,
        'categoryDescription' => null,
        'selectedCategoryId' => null
    ];

    protected $rules = [
        'inputs.categoryName' => [
            'required', 'min:3'
        ]
    ];

    protected $messages = [
        'inputs.categoryName.required' => 'Nama Kategori wajib diisi!',
        'inputs.categoryName.min' => 'Nama Kategori minimal 3 karakter!',
    ];

    //ACTION - Set value for selected category
    #[On('open-add-edit-category-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);

            $categoryData = ProductCategoryQuery::fetchCategory($realId);

            $this->inputs['selectedCategoryId'] = Crypt::encrypt($categoryData->id);
            $this->inputs['categoryName'] = $categoryData->name;
            $this->inputs['categoryDescription'] = $categoryData->description;
        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //ACTION - Reset property when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Save category data
    public function saveCategory() {
        try {
            $decryptCategoryId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedCategoryId']) : null;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('save-failed', 'Terjadi kesalahan, silahkan coba lagi!');
        }

        //Run validation
        $this->validate($this->rules, $this->messages);

        try {
            ProductCategory::updateOrCreate([
                'id' => $decryptCategoryId
            ], [
                'name' => $this->inputs['categoryName'],
                'description' => $this->inputs['categoryDescription']
            ]);

            $this->resetAllProperty();
            $this->dispatch('toast', type: 'success', message: 'Kategori berhasil disimpan!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('save-failed', 'Terjadi kesalahan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.add-edit-product-category-modal');
    }
}
