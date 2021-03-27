<?php

namespace App\Wizards\fields;

class FieldFactory
{
    public $type;
    public $name;
    public $module;
    public $io;

    public $listName = null;

    public function __construct($type, $name, $module, $io)
    {
        $this->type = $type;
        $this->name = $name;
        $this->module = $module;
        $this->io = $io;
    }

    public function process()
    {
        switch ($this->type) {
            case 'string':
                return $this->processString();
                break;

            case 'enum':
                return $this->processEnum();
                break;
            case 'int':
                return $this->processInt();
                break;

            case 'date':
                break;

            case 'datetime':
                break;
        }
    }

    public function processString()
    {
        return [
            'name' => $this->name,
            'label' => self::getLabelName($this->name),
            'type' => 'varchar',
            'max_size' => 255,
            'default' => null,
            'module' => $this->module,
            'reportable' => true,
        ];
    }

    public function processInt()
    {
        return [
            'name' => $this->name,
            'label' => self::getLabelName($this->name),
            'type' => 'int',
            'max_size' => 11,
            'default' => null,
            'module' => $this->module,
            'reportable' => true,
        ];
    }

    public function processEnum()
    {
        $listName = $this->io->ask('Input field name');
        $this->listName = $listName;

        return [
            'name' => $this->name,
            'label' => self::getLabelName($this->name),
            'type' => 'enum',
            'ext1' => $listName,
            'max_size' => 255,
            'default' => null,
            'module' => $this->module,
            'reportable' => true,
        ];
    }

    public static function getLabelName($fieldName)
    {
        return 'LBL_' . strtoupper($fieldName);
    }
}
