<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\User;
use App\Models\UserProfile;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('User', 'Users');
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'profile.type' => 'required',
            'profile.city' => 'required',
            'profile.phone' => 'required',
            'profile.address' => 'required',
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'name' => 'password',
            'label' => 'Password',
            'type' => 'password',
        ]);

        $this->crud->addField([
            'name' => 'profile.type',
            'label' => 'Type',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'profile.city',
            'label' => 'City',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'profile.phone',
            'label' => 'Phone',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'profile.address',
            'label' => 'Address',
            'type' => 'text',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function storeCrud()
    {
        $response = $this->crud->storeCrud();

        // Получите ID только что созданного пользователя
        $userId = $response->getData()->id;

        // Создайте профиль пользователя
        $profile = new UserProfile([
            'type' => request('profile.type'),
            'city' => request('profile.city'),
            'phone' => request('profile.phone'),
            'address' => request('profile.address'),
        ]);

        // Свяжите профиль пользователя с созданным пользователем
        User::findOrFail($userId)->profile()->save($profile);

        return $response;
    }

    public function updateCrud()
    {
        $response = $this->crud->updateCrud();

        // Получите ID обновляемого пользователя
        $userId = $this->crud->getCurrentEntryId();

        // Обновите профиль пользователя
        User::findOrFail($userId)->profile->update(request('profile'));

        return $response;
    }
}
