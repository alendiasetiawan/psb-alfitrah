<?php

namespace App\Livewire\Components\Modals;

use App\Models\Inventory\VariantType;
use App\Models\Inventory\VariantOption;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddEditVariantTypeModal extends Component
{
    //String
    public $modalId;
    //Boolean
    public $isEditing = false;
    //Array
    public $inputs = [
        'variantTypeName' => '',
        'variantOptions' => []
    ];
    public $variantOptionLists = [];
    public $editingVariantTypeId = null;

    //ACTION - Reset property and validation when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Set edit value when modal is opened for editing
    public function setEditValue($id) {
        $this->editingVariantTypeId = $id;
        $this->isEditing = true;

        $variantType = VariantType::with('variantOptions')->find($id);

        if ($variantType) {
            $this->inputs['variantTypeName'] = $variantType->name;
            $this->inputs['variantOptions'] = $variantType->variantOptions->map(function($option) {
                return [
                    'id' => $option->id,
                    'name' => $option->name
                ];
            })->toArray();
        }
    }

    //ACTION - Add new variant option input
    public function addVariantOption() {
        $this->inputs['variantOptions'][] = [
            'id' => null,
            'name' => ''
        ];
    }

    //ACTION - Remove variant option
    public function removeVariantOption($index) {
        unset($this->inputs['variantOptions'][$index]);
        $this->inputs['variantOptions'] = array_values($this->inputs['variantOptions']); // Reindex array
    }

    //ACTION - Save variant type and options
    public function saveVariant() {
        $this->validate([
            'inputs.variantTypeName' => 'required|min:3',
            'inputs.variantOptions.*.name' => 'required|min:1'
        ], [
            'inputs.variantTypeName.required' => 'Nama varian harus diisi',
            'inputs.variantTypeName.min' => 'Nama varian minimal 3 karakter',
            'inputs.variantOptions.*.name.required' => 'Nama opsi varian harus diisi',
            'inputs.variantOptions.*.name.min' => 'Nama opsi varian minimal 1 karakter'
        ]);

        try {
            DB::transaction(function() {
                if ($this->isEditing && $this->editingVariantTypeId) {
                    // Update existing variant type
                    $variantType = VariantType::find($this->editingVariantTypeId);
                    $variantType->update([
                        'name' => $this->inputs['variantTypeName']
                    ]);

                    // Delete existing options
                    $variantType->variantOptions()->delete();

                    // Create new options
                    foreach ($this->inputs['variantOptions'] as $option) {
                        VariantOption::create([
                            'variant_type_id' => $variantType->id,
                            'name' => $option['name']
                        ]);
                    }
                } else {
                    // Create new variant type
                    $variantType = VariantType::create([
                        'name' => $this->inputs['variantTypeName']
                    ]);

                    // Create options
                    foreach ($this->inputs['variantOptions'] as $option) {
                        VariantOption::create([
                            'variant_type_id' => $variantType->id,
                            'name' => $option['name']
                        ]);
                    }
                }
            });

            session()->flash('success', 'Data varian berhasil disimpan');
            $this->resetAllProperty();
            $this->dispatch('variant-saved');

        } catch (\Exception $e) {
            session()->flash('save-failed', 'Gagal menyimpan data varian: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.components.modals.add-edit-variant-type-modal');
    }
}
