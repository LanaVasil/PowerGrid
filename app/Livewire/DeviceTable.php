<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Brand;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class DeviceTable extends PowerGridComponent
{
    public string $tableName = 'device-table-yaurbz-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),                

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Device::query()
          ->with(['device_type', 'brand'])
        ;

        /*
        ->join('device_types as newDevice_types', function ($device_types) 
        { 
          $device_types->on('devices.device_type_id', '=', 'newDevice_types.id');
        })
        ->select('devices.*', 'newDevice_types.name as device_type_name')
*/        

        // ->join('brands as newBrands', function ($brands) 
        // { 
        //   $brands->on('devices.brand_id', '=', 'newBrands.id');
        // })
        // ->select('devices.*', 'newBrands.name as brand_name');


               // Column::make('DeviceType', 'device_type_name', 'newDevice_types.name'),
    }

    public function relationSearch(): array
    {
        return [];
        /*return [
        'device_type' => [ 'name', ],
        'brand' => ['name',],          
        ];*/
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('id')
            ->add('device_type_name', fn ($tab) => e($tab->device_type?->name))

            // ->add('device_type_name')
            // ->add('device_type_id')            
            // ->add('brand_name')
            // ->add('brand_id')
            ->add('brand_name', fn ($tab) => e($tab->brand->name))
            ->add('name')
            ->add('status')
            // ->add('created_at')
            ->add('created_at_formatted', fn ($tab) => Carbon::parse($tab->created_at)->format('d.m.Y'));
    }

    public function columns(): array
    {
        return [
            // Column::make('Id', 'id'),

            // Column::make('Device type id', 'device_type_id'),
            Column::make('Тип', 'device_type_name', 'newDevice_types.name'),

            Column::make('Бренд', 'brand_name'),
            // Column::make('Brand id', 'brand_id')
            // Column::make('Brand', 'brand_name', 'newBrands.name')

            Column::make('Назва', 'name')
                ->bodyAttribute('!text-wrap')
                ->sortable()
                ->searchable(),

            Column::make('Статус', 'status')
                ->sortable()
                ->searchable()
                ->toggleable(),

            Column::make('Створено', 'created_at_formatted'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [

          Filter::select('device_type_name', 'device_type_id')
          ->dataSource(DeviceType::all())
          ->optionLabel('name')
          ->optionValue('id'),          

          Filter::select('brand_name', 'brand_id')
          ->dataSource(Brand::all())
          ->optionLabel('name')
          ->optionValue('id'),
         


          // Filter::inputText('brand')->operators(['contains']),

        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Device $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::checkbox()
                ->when(fn ($device) => $device->status == false)
                ->hide(),

            Rule::rows()
            ->when(fn ($dish) => $dish->status == false)
            // --bs-table-striped-color:
            // --bs-table-color: 
            // ->setAttribute('class', 'text-red-500'),

        ];
    }
    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
    
}
