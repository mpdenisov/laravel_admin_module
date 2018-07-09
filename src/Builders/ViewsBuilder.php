<?php
namespace Rhinoda\Admin\Builders;

use Illuminate\Support\Str;
use Rhinoda\Admin\Cache\QuickCache;

class ViewsBuilder
{
    // Templates
    private $template; // Array: [0]->index, [1]->edit, [2]->create
    // Variables
    private $fields;
    private $singular_name;
    private $plural_name;
    private $headings;
    private $columns;
    private $formFieldsEdit;
    private $path;
    private $formFieldsCreate;
    private $files;
    // @todo Move into FieldsDescriber for usage in fields extension
    private $starred = [
        'required',
        'required|unique'
    ];


    /**
     * Build our views files
     */
    public function build()
    {
        $cache          = new QuickCache();
        $cached         = $cache->get('fieldsinfo');
        $this->template = [
            0 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'view_index',
            1 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'view_edit',
            2 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'view_create',
        ];
        $this->singular_name = $cached['singular_name'];
        $this->plural_name   = $cached['plural_name'];
        $this->fields        = $cached['fields'];
        $this->files         = $cached['files'];
        $this->names();
        $template = (array)$this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    public function buildCustom($name)
    {
        $this->singular_name     = $name;
        $this->template = [
            0 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'customView_index',
            1 => '',
            2 => ''
        ];
        $this->names();
        $template = (array)$this->loadTemplate();
        $this->publishCustom($template);

    }

    /**
     *  Load views templates
     */
    private function loadTemplate()
    {
        return [
            0 => $this->template[0] != '' ? file_get_contents($this->template[0]) : '',
            1 => $this->template[1] != '' ? file_get_contents($this->template[1]) : '',
            2 => $this->template[2] != '' ? file_get_contents($this->template[2]) : '',
        ];
    }

    /**
     * Build views templates parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $this->buildTable();
        $this->buildCreateForm();
        $this->buildEditForm();

        // Index template
        $template[0] = str_replace([
            '$PLURAL$',
            '$HEADINGS$',
            '$FIELDS$',
        ], [
            $this->plural_name,
            $this->headings,
            $this->columns
        ], $template[0]);

        // Edit template
        $template[1] = str_replace([
            '$SINGULAR$',
            '$PLURAL$',
            '$FORMFIELDS$',
            '$FILES$'
        ], [
            $this->singular_name,
            $this->plural_name,
            $this->formFieldsEdit,
            $this->files != 0 ? "'files' => true, " : ''
        ], $template[1]);

        // Create template
        $template[2] = str_replace([
            '$SINGULAR$',
            '$PLURAL$',
            '$FORMFIELDS$',
            '$FILES$'
        ], [
            $this->singular_name,
            $this->plural_name,
            $this->formFieldsCreate,
            $this->files != 0 ? "'files' => true, " : ''
        ], $template[2]);

        return $template;
    }

    /**
     *  Build index table
     */
    private function buildTable()
    {
        $used     = [];
        $headings = '';
        $columns  = '';
        foreach ($this->fields as $field) {
            // Check if there is no duplication for radio and checkbox.
            // Password fields are excluded from the table too.
            if (! in_array($field->title, $used)
                && $field->type != 'password'
                && $field->type != 'textarea'
                && $field->show == 1
            ) {
                $headings .= "<th>$field->label</th>\r\n";
                // Format our table column by field type
                if ($field->type == 'relationship') {
                    $columns .= '<td>{{ isset($row->' . $field->relationship_name . '->' . $field->relationship_field . ') ? $row->' . $field->relationship_name . '->' . $field->relationship_field . " : '' }}</td>\r\n";
                    $used[$field->relationship_field] = $field->relationship_field;
                } elseif ($field->type == 'photo') {
                    $columns .= '<td>@if($row->' . $field->title . ' != \'\')<img src="{{ asset(\'uploads/thumb\') . \'/\'.  $row->' . $field->title . " }}\">@endif</td>\r\n";
                    $used[$field->title] = $field->title;
                } else {
                    $columns .= '<td>{{ $row->' . $field->title . " }}</td>\r\n";
                    $used[$field->title] = $field->title;
                }
            }
        }
        $this->headings = $headings;
        $this->columns  = $columns;
    }

    /**
     *  Build edit.blade.php form
     */
    private function buildEditForm()
    {
        $form = '';
        foreach ($this->fields as $field) {
            $title = addslashes($field->label);
            $label = $field->title;
            if (in_array($field->validation,
                    $this->starred) && $field->type != 'password' && $field->type != 'file' && $field->type != 'photo'
            ) {
                $title .= '*';
            }
            if ($field->type == 'relationship') {
                $label = $field->relationship_name . '_id';
            }
            if ($field->type == 'checkbox') {
                $field->default = '$' . $this->singular_name . '->' . $label . ' == 1';
            }
            $temp = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR . $field->type);
            $temp = str_replace([
                'old(\'$LABEL$\')',
                '$LABEL$',
                '$TITLE$',
                '$VALUE$',
                '$STATE$',
                '$SELECT$',
                '$TEXTEDITOR$',
                '$HELPER$',
                '$WIDTH$',
                '$HEIGHT$',
            ], [
                'old(\'$LABEL$\',$' . $this->singular_name . '->' . $label . ')',
                $label,
                $title,
                $field->type != 'radio' ?
                    $field->value != '' ? ', "' . $field->value . '"' : ''
                    : "'$field->value'",
                $field->default,
                '$' . $field->relationship_name,
                $field->texteditor == 1 ? ' ckeditor' : '',
                $this->helper($field->helper),
                $field->dimension_w,
                $field->dimension_h,
            ], $temp);
            $form .= $temp;
        }
        $this->formFieldsEdit = $form;
    }

    /**
     *  Build create.blade.php form
     */
    private function buildCreateForm()
    {
        $form = '';
        foreach ($this->fields as $field) {
            $title = addslashes($field->label);
            $key   = $field->title;
            if (in_array($field->validation, $this->starred)) {
                $title .= '*';
            }
            if ($field->type == 'relationship') {
                $key = $field->relationship_name . '_id';
            }
            $temp = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR . $field->type);
            $temp = str_replace([
                '$LABEL$',
                '$TITLE$',
                '$VALUE$',
                '$STATE$',
                '$SELECT$',
                '$TEXTEDITOR$',
                '$HELPER$',
                '$WIDTH$',
                '$HEIGHT$',
            ], [
                $key,
                $title,
                $field->type != 'radio' ?
                    $field->value != '' ? ', ' . $field->value : ''
                    : "'$field->value'",
                $field->default,
                '$' . $field->relationship_name,
                $field->texteditor == 1 ? ' ckeditor' : '',
                $this->helper($field->helper),
                $field->dimension_w,
                $field->dimension_h,
            ], $temp);
            $form .= $temp;
        }
        $this->formFieldsCreate = $form;
    }

    /**
     *  Generate names for the views
     */
    private function names()
    {
        $camelCase           = ucfirst(Str::camel($this->singular_name));
        $this->singular_name = strtolower($this->singular_name);
        $this->plural_name   = strtolower($this->plural_name);
        $this->path          = $this->singular_name;
    }

    /**
     * Create helper blocks for form fields
     *
     * @param $value
     *
     * @return string
     */
    private function helper($value)
    {
        if ($value != '') {
            return '<p class="help-block">' . $value . '</p>';
        } else {
            return '';
        }
    }

    /**
     *  Publish files into their places
     */
    private function publish($template)
    {
        $path = 'Modules' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
        if (! file_exists(app_path($path . DIRECTORY_SEPARATOR . $this->path))) {
            mkdir(app_path($path . DIRECTORY_SEPARATOR . $this->path));
            chmod(app_path($path), 0777);
        }
        file_put_contents(app_path($path . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'index.blade.php'),
            $template[0]);
        file_put_contents(app_path($path . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'edit.blade.php'),
            $template[1]);
        file_put_contents(app_path($path . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'create.blade.php'),
            $template[2]);
    }

    private function publishCustom($template)
    {
        $path = 'Modules' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
        if (! file_exists(app_path($path . DIRECTORY_SEPARATOR . $this->path))) {
            mkdir(app_path($path . DIRECTORY_SEPARATOR . $this->path));
            chmod(app_path($path), 0777);
        }
        file_put_contents(app_path($path . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'index.blade.php'),
            $template[0]);
    }

}