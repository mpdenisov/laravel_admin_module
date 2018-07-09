<?php
namespace App\Modules\Admin\Builders;

use Illuminate\Support\Str;
use App\Modules\Admin\Cache\QuickCache;
use App\Models\Menu;

class ControllerBuilder
{
    // Controller namespace
    private $namespace = 'App\Modules\Admin\Controllers';
    // Template
    private $template;
    // Global names
    private $singular_name;
    private $plural_name;
    private $className;
    private $modelName;
    private $createRequestName;
    private $updateRequestName;
    private $fileName;
    private $fields;
    private $relationships;
    private $files;
    private $enum;

    /**
     * Build our controller file
     */
    public function build()
    {
        $cache               = new QuickCache();
        $cached              = $cache->get('fieldsinfo');
        $this->template      = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'controller';
        $this->singular_name = $cached['singular_name'];
        $this->plural_name   = $cached['plural_name'];
        $this->fields        = $cached['fields'];
        $this->relationships = $cached['relationships'];
        $this->files         = $cached['files'];
        $this->enum          = $cached['enum'];
        $this->names();
        $template = (string)$this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    public function buildCustom($name)
    {
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'customController';
        $this->singular_name     = $name;
        $this->names();
        $template = (string)$this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    /**
     *  Load controller template
     */
    private function loadTemplate()
    {
        return file_get_contents($this->template);
    }

    /**
     * Build controller template parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $template = str_replace([
            '$NAMESPACE$',
            '$MODEL$',
            '$CREATEREQUESTNAME$',
            '$UPDATEREQUESTNAME$',
            '$CLASS$',
            '$SINGULAR$',
            '$PLURAL$',
            '$INDEXGET$',
            '$RELATIONSHIPS$',
            '$RELATIONSHIP_COMPACT$',
            '$RELATIONSHIP_COMPACT_EDIT$',
            '$RELATIONSHIP_NAMESPACES$',
            '$FILETRAIT$',
            '$FILESAVING$',
            '$ENUM$',
        ], [
            $this->namespace,
            $this->modelName,
            $this->createRequestName,
            $this->updateRequestName,
            $this->className,
            strtolower($this->singular_name),
            strtolower($this->plural_name),
            $this->indexBuilder(),
            $this->relationshipsBuilder(),
            $this->compactBuilder(),
            $this->compactEditBuilder(),
            $this->relationshipsNamespaces(),
            $this->files > 0 ? 'use App\Modules\Admin\Traits\FileUploadTrait;' : '',
            $this->files > 0 ? '$request = $this->saveFiles($request);' : '',
            $this->enum > 0 ? $this->enum() : '',
        ], $template);

        return $template;
    }

    /**
     * Build our index template
     * @return mixed|string
     */
    public function indexBuilder()
    {
        if ($this->relationships == 0) {
            return '$' . strtolower($this->plural_name) . ' = ' . $this->modelName . '::all();';
        } else {
            $relationship = '$' . strtolower($this->plural_name) . ' = ' . $this->modelName . '::$WITH$get();';
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    $relationship = str_replace([
                        '$WITH$'
                    ], [
                        'with("' . $field->relationship_name . '")->$WITH$'
                    ], $relationship);
                }
            }
            $relationship = str_replace('$WITH$', '', $relationship);

            return $relationship;
        }
    }

    public function relationshipsNamespaces()
    {
        if ($this->relationships == 0) {
            return '';
        } else {
            $menus         = Menu::all()->keyBy('id');
            $relationships = '';
            $first         = true;
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    $menu = $menus[$field->relationship_id];
                    $relationships .= 'use App\\Models\\' . ucfirst(Str::camel($menu->singular_name)) . ";\r\n";
                }
            }

            return $relationships;
        }
    }

    /**
     * Build relationships for forms
     * @return string
     */
    public function relationshipsBuilder()
    {
        if ($this->relationships == 0) {
            return '';
        } else {
            $menus         = Menu::all()->keyBy('id');
            $relationships = '';
            $first         = true;
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    // Formatting fix if the relationship is not first
                    if (! $first) {
                        $relationships .= '        ';
                    }
                    $menu = $menus[$field->relationship_id];
                    $relationships .= '$'
                                      . $field->relationship_name
                                      . ' = '
                                      . ucfirst(Str::camel($menu->singular_name))
                                      . '::pluck("'
                                      . $field->relationship_field
                                        //null list select values like array_values so, if you 
                                        //want delete one row it will not match the value with the actual id.
                                      . '", "id")->prepend(\'Please select\', 0);'
                                      . "\r\n";
                }
            }

            return $relationships;
        }
    }

    /**
     * Build compact for create form
     * @return mixed|string
     */
    public function compactBuilder()
    {
        if ($this->relationships == 0 && $this->enum == 0) {
            return '';
        } else {
            $compact = ', compact($RELATIONS$)';
            if ($this->relationships > 0) {
                $first = true;
                foreach ($this->fields as $field) {
                    if ($field->type == 'relationship') {
                        $toReplace = '';
                        if ($first != true) {
                            $toReplace .= ', ';
                        } else {
                            $first = false;
                        }
                        $toReplace .= '"' . $field->relationship_name . '"$RELATIONS$';
                        $compact = str_replace('$RELATIONS$', $toReplace, $compact);
                    }
                }
            }
            if ($this->enum > 0) {
                if (! isset($first)) {
                    $first = true;
                }
                foreach ($this->fields as $field) {
                    if ($field->type == 'enum') {
                        $toReplace = '';
                        if ($first != true) {
                            $toReplace .= ', ';
                        } else {
                            $first = false;
                        }
                        $toReplace .= '"' . $field->title . '"$RELATIONS$';
                        $compact = str_replace('$RELATIONS$', $toReplace, $compact);
                    }
                }
            }
            $compact = str_replace('$RELATIONS$', '', $compact);

            return $compact;
        }
    }

    /**
     * Build compact for edit form
     * @return string
     */
    public function compactEditBuilder()
    {
        if ($this->relationships == 0 && $this->enum == 0) {
            return '';
        } else {
            $compact = '';
            if ($this->relationships > 0) {
                foreach ($this->fields as $field) {
                    if ($field->type == 'relationship') {
                        $compact .= ', "' . $field->relationship_name . '"';
                    }
                }
            }
            if ($this->enum > 0) {
                foreach ($this->fields as $field) {
                    if ($field->type == 'enum') {
                        $compact .= ', "' . $field->title . '"';
                    }
                }
            }

            return $compact;
        }
    }

    /**
     *  Generate names
     */
    private function names()
    {
        $camelName               = ucfirst(Str::camel($this->singular_name));
        $this->className         = $camelName . 'Controller';
        $this->modelName         = $camelName;
        $this->createRequestName = 'Create' . $camelName . 'Request';
        $this->updateRequestName = 'Update' . $camelName . 'Request';

        $fileName       = $this->className . '.php';
        $this->fileName = $fileName;
    }

    /**
     *  Publish file into it's place
     */
    private function publish($template)
    {
        $path = 'Modules' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'Controllers';
        if (! file_exists(app_path($path))) {
            mkdir(app_path($path));
            chmod(app_path($path), 0777);
        }
        file_put_contents(app_path($path . DIRECTORY_SEPARATOR . $this->fileName),
            $template);
    }

    public function enum()
    {
        $return = "\r\n";
        foreach ($this->fields as $field) {
            if ($field->type == 'enum') {
                $return .= '        $' . $field->title . ' = ' . $this->modelName . '::$' . $field->title . ";\r\n";
            }
        }

        return $return;
    }

}
