<?php

namespace App\Http\Livewire;

use App\Core\Popup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CrudTable extends Component
{


    use WithPagination;


    private string $modelNamespace = '\\App\\Models\\';
    private string $controllerNamespace = '\\App\\Http\\Controllers\\';

    public string $modelName = '';
    public string $modelClass = '';
    public string $controllerClass = '';
    public int $user_id = 0;
    public string $user_role = 'user';
    public string $user_email = '';


    /**
     * @var array $columns: association $columnName => $column
     * $columnName = [
     *         'label' => string,
     *         'visible' => bool,
     *         'type' => string : 'text' | 'image',
     *         'isEditable' => bool,
     *         'isSortable' => bool,
     *         'isLink' => bool,
     *         'link' => array<String> : ['methodName', 'columnToDisplay' => null|string]
     *     ];
     */
    public array $columns = [];
    public int $nbColumnsToDisplay = 0;
    public bool $canEdit = false;
    public bool $isEditRemote = false;
    public array $editForm = [];
    public bool $canDelete = false;

    public string $orderField = 'id';
    public string $orderDirection = 'DESC';

    public string $editing = '';



    public function mount($modelName)
    {
        $this->modelName = $modelName;
        $this->user_role = auth()->user()->role;
        $this->user_id = auth()->user()->id;
        $this->user_email = auth()->user()->email;


        if ($this->user_role === 'admin') {
            $this->controllerNamespace .= 'AdminControllers\\';
        } elseif ($this->user_role === 'user') {
            $this->controllerNamespace .= 'AuthControllers\\';
        } else {
            return;
        }

        $this->modelClass = $this->modelNamespace . $modelName;
        if (!class_exists($this->modelClass)) {
            throw new \Exception("Model $this->modelClass does not exist");
        }

        $this->controllerClass = $this->controllerNamespace . $modelName . 'Controller';
        if (!class_exists($this->controllerClass)) {
            throw new \Exception("Controller $this->controllerClass does not exist");
        }

        if (!method_exists($this->controllerClass, 'getCrud')) {
            throw new \Exception("Controller $this->controllerClass does not have method getCrud");
        }
        $controllerReturn = $this->controllerClass::getCrud();


        if (
            !isset($controllerReturn['columns'])
            || !isset($controllerReturn['canEdit'])
            || !isset($controllerReturn['canDelete'])
        ) {
            throw new \Exception(
                "La methode getCrud du Controller $this->controllerClass a rencontré un probleme:
                 'columns', 'canEdit', 'canDelete' sont obligatoires"
            );
        }

        $this->createTable($controllerReturn);
        // compte le nombre de colonne visible et ajoute 1 si canEdit ou canDelete vaut vrai
        $this->nbColumnsToDisplay = count(array_filter($this->columns, fn ($column) => $column['visible']))
            + ($this->canEdit || $this->canDelete ? 1 : 0);
    }


    /**
     * @param array $controllerReturn : retour de la methode getCrud du controller
     * ['columns'] => array : association $columnName => $columnLabel
     *
     * ['canEdit'] => : bool : si true, affiche un bouton d'édition
     *
     * ['isEditRemote'] => bool | null : si true : ouvre une page par $this->controllerClass::edit($id)
     * si false ou null, ouvre une modal par $this->controllerClass::editFields($id)
     *
     * ['columnsAllowedToEdit'] => array | null : correspondant aux $columnName, si isEditRemote = true,
     * le tableau n'est pas traité, sinon, si null, toutes les colonnes sont éditables, sinon, seules
     * les colonnes du tableau sont éditables
     * 
     * ['sortableColumns'] => array | null : si null, aucune colonne n'est triable
     *
     * ['canDelete'] => bool : si true, affiche un bouton de suppression
     *
     * ['modelLinks'] => array | null :
     * si une des colonne correspond à l'id d'instance d'un autre model que $this->modelClass, $modelLinks
     * sera un tableau d'associations: $columnName => ['methodName', 'columnToDisplay' => null|string]
     * si ['modelLinks][$columnName]['columnToDisplay'] est null => 'name' par defaut
     *
     * ['imagesColumns'] => array | null : si des colonnes correspondent à des images, array des
     * $columnName concernés
     */
    private function createTable($controllerReturn)
    {
        $this->canEdit = $controllerReturn['canEdit'] ?? false;
        $this->canDelete = $controllerReturn['canDelete'] ?? false;
        $this->isEditRemote = $controllerReturn['isEditRemote'] ?? false;

        $this->columns = [];
        foreach ($controllerReturn['columns'] as $columnName => $columnLabel) {
            $column = [
                'label' => $columnLabel,
                'visible' => $columnName !== 'id',
                'type' => 'text',
                'isEditable' => false,
                'isSortable' => false,
                'isLink' => false,
                'link' => '',
            ];

            /**
             * type
             */
            if (
                isset($controllerReturn['imagesColumns'])
                && in_array($columnName, $controllerReturn['imagesColumns'])
            ) {
                $column['type'] = 'image';
            }

            /**
             * isEditable
             * il faut que isEditRemote soit false ou null, que canEdit soit true, et que soit
             * columnsAllowedToEdit soit null, soit contienne $columnName
             */
            if (
                (!isset($controllerReturn['isEditRemote']) || !$controllerReturn['isEditRemote'])
                && $controllerReturn['canEdit']
                && (
                    (isset($controllerReturn['columnsAllowedToEdit'])
                        && in_array($columnName, $controllerReturn['columnsAllowedToEdit']))
                    || !isset($controllerReturn['columnsAllowedToEdit'])
                )
            ) {
                $column['isEditable'] = true;
            }

            /**
             * isSortable
             */
            if (
                isset($controllerReturn['sortableColumns'])
                && in_array($columnName, $controllerReturn['sortableColumns'])
            ) {
                $column['isSortable'] = true;
            }

            /**
             * link
             */
            if (
                isset($controllerReturn['modelLinks'])
                && isset($controllerReturn['modelLinks'][$columnName])
            ) {
                $column['isLink'] = true;
                $column['link'] = $controllerReturn['modelLinks'][$columnName];
                if (!isset($controllerReturn['modelLinks'][$columnName]['columnToDisplay'])) {
                    $column['link']['ColumnToDisplay'] = 'name';
                }
            }

            $this->columns[$columnName] = $column;
        }
    }



    public function getModelsInstances()
    {
        $select = [];
        $linkedSelect = [];
        foreach ($this->columns as $columnName => $column) {
            if ($column['isLink']) {
                $linkedSelect[$columnName] = $column['link'];
            } else {
                $select[] = $columnName;
            }
        }
        $models = $this->modelClass::select($select);
        if ($this->user_role === 'user') {
            $instance = new $this->modelClass;
            if (in_array('user_id', $instance->getFillable())) {
                $models = $this->modelClass::where('user_id', $this->user_id);
            } elseif (in_array('email', $instance->getFillable())) {
                $models = $this->modelClass::where('email', $this->user_email);
            }
        }
        $models = $models->get();
        /**
         * pour chaque model, on récupère les attributs liés, et on les concatène dans une chaine si
         * il y en a plusieurs puis on les stocke dans la colonne correspondante
         */
        foreach ($models as $model) {
            foreach ($linkedSelect as $columnName => $link) {
                $linkedAttributes = $model->{$link['methodName']}()->get();
                $model->$columnName = '';
                foreach ($linkedAttributes as $linkedAttribute) {
                    $model->$columnName .= $linkedAttribute->{$link['columnToDisplay']} . ', ';
                }
                $model->$columnName = substr($model->$columnName, 0, -2);
            }
        }
        return $models;
    }

    public function orderBy($field, $direction)
    {
        $this->orderField = $field;
        $this->orderDirection = $direction;
    }


    public function edit($id)
    {
        if ($this->isEditRemote) {
            return redirect()->action([$this->controllerClass, 'edit'], ['id' => $id]);
        } else {
            $this->editing = $id;
            $this->editForm = $this->controllerClass::editForm($id);
        }
    }



    public function update($id)
    {
        $popup =  $this->controllerClass::update($id, $this->editForm);
        return redirect()->action([$this->controllerClass, 'show'])->with('popup', $popup);
    }

    public function cancelEdit()
    {
        $this->editing = '';
    }

    public function delete($id)
    {
        $this->controllerClass::delete($id);
    }


    public function render()
    {
        if ($this->orderDirection === 'ASC') {
            $models = $this->getModelsInstances()->sortBy($this->orderField);
        } else {
            $models = $this->getModelsInstances()->sortByDesc($this->orderField);
        }
        return view('livewire.crud-table', [
            'models' => $models,
            'modelClass' => $this->modelClass,
            'modelName' => $this->modelName,
            'controllerClass' => $this->controllerClass,
            'columns' => $this->columns,
            'canEdit' => $this->canEdit,
            'canDelete' => $this->canDelete,
            'isEditRemote' => $this->isEditRemote,
            'nbColumnsToDisplay' => $this->nbColumnsToDisplay,
            'orderField' => $this->orderField,
            'orderDirection' => $this->orderDirection,
            'editing' => $this->editing,
            'editForm' => $this->editForm,
        ]);
    }
}
