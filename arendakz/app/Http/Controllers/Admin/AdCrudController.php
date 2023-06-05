<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Ad;
use App\Http\Controllers\Controller;
use App\Models\Category;

/**
 * Class AdCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AdCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Ad::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ad');
        CRUD::setEntityNameStrings('ad', 'ads');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('title');
        CRUD::column('category_id');
        CRUD::column('description');
        CRUD::column('photo')->type('image');
        CRUD::column('price');
        CRUD::column('contact_phone')->default('+7');
        CRUD::column('contact_email');
        CRUD::column('status');

        CRUD::addFilter([
            'name' => 'status',
            'type' => 'select2',
            'label' => 'Статус',
        ], function () {
            return [
                Ad::STATUS_PENDING => 'На проверке',
                Ad::STATUS_ACTIVE => 'Активные',
                Ad::STATUS_INACTIVE => 'Неактивные',
                Ad::STATUS_REJECTED => 'Отклоненные',
            ];

        }, function ($value) {
            $this->crud->addClause('where','status', $value);
        });

        // Фильтр по категории
        CRUD::addFilter([
            'name' => 'category',
            'type' => 'select2',
            'label' => 'Категория',
            'model' => \App\Models\Category::class,
            'attribute' => 'name',
            'pivot' => true,
        ]);

        // Фильтр по дате публикации
        CRUD::addFilter([
            'name' => 'published_date',
            'type' => 'date_range',
            'label' => 'Дата публикации',
            'table' => 'ads',
            'column' => 'created_at',
        ]);

        // Поиск по названию объявления
        $this->crud->addFilter([
            'name' => 'search',
            'type' => 'text',
            'label' => 'Поиск по названию',
        ], function ($value) {
            $this->crud->query = $this->crud->query->where('title', 'like', "%{$value}%");
        });

    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'title' => 'required',
            'category_id' => 'required',
            // 'name' => 'required|min:2',
        ]);

        CRUD::field('title');
        CRUD::field('category_id');
        CRUD::field('description');
        CRUD::field('photo')->type('image');
        CRUD::field('price');
        CRUD::field('contact_phone')->default('+7');
        CRUD::field('contact_email');

        CRUD::addField([
            'name' => 'status',
            'label' => 'Статус',
            'type' => 'select2',
            'options' => [
                Ad::STATUS_PENDING => 'На проверке',
                Ad::STATUS_ACTIVE => 'Активные',
                Ad::STATUS_INACTIVE => 'Неактивные',
                Ad::STATUS_REJECTED => 'Отклоненные',
            ],
            'default' => Ad::STATUS_PENDING,
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $category = $request->input('category');
        $search = $request->input('search');

        $query = Ad::query();

        if ($status) {
            $query->where('status', $status);
        }
        if ($category) {
            $query->where('category_id', $category);
        }
        if ($search) {
            $query->where('title', 'like', "%$search%");
        }

        $ads = $query->get();

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

    }
}
