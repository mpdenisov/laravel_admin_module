<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 07.08.18
 * Time: 10:31
 */

namespace Rhinoda\Admin\Builders;


use Rhinoda\Admin\Cache\QuickCache;

class MessageBuilder
{
    private $template;
    private $columns;
    private $fields;

    public function build()
    {
        $cache = new QuickCache();
        $cached = $cache->get('fieldsinfo');
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates'  . DIRECTORY_SEPARATOR . 'view_message';
        $this->fields = $cached['fields'];
        $template = $this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    private function loadTemplate()
    {
        return $this->template != '' ? file_get_contents($this->template) : '';
    }

    private function buildParts($template)
    {

        $this->buildCreateColumns();


        // Index template
        $template = str_replace([
            '$FIELDS$',
        ], [
            $this->columns
        ], $template);


        // Create template

        return $template;
    }

    private function buildCreateColumns()
    {
        $form = '';
        foreach ($this->fields as $field) {
            $title = $field->label;
            $key = $field->title;

            $temp = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR . 'message');
            $temp = str_replace([
                '$TITLE$',
                '$LABEL$'
            ], [
                $key,
                $title,
            ], $temp);
            $form .= $temp;
        }
        $this->columns = $form;

    }

    private function publish($template)
    {


        file_put_contents(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'message' . DIRECTORY_SEPARATOR . 'message.blade.php'),
            $template);


    }
}