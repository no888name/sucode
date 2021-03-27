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

            case 'url':
                return $this->processLink();
                break;
            case 'relate':
                return $this->processRelate();
                break;

            case 'datetime':
                break;
        }
    }

    public function processRelate()
    {
        $relatedModuleName = $this->io->ask('Please input Related module name', 'Accounts');

        $this->name = strtolower($relatedModuleName);

        $nameField = strtolower($relatedModuleName) . '_name_c';
        $idField = strtolower($relatedModuleName) . '_id_c';

        $this->relatedModuleName = $relatedModuleName;

        return [
            [
                'name' => $nameField,
                'type' => 'relate',
                'ext2' => $relatedModuleName,
                'ext3' => $idField,
                'max_size' => 255,
                'default' => null,
                'module' => $this->module,
                'label' => self::getLabelName($nameField),
                'reportable' => true,
            ],
            [
                'name' => $idField,
                'type' => 'id',
                'len' => 36,
                'module' => $this->module,
                'label' => self::getLabelName($idField),
                'reportable' => true,
            ],
        ];
    }

    public function processLink()
    {
        $linkName = $this->io->ask('Please input target url', 'https://somesite.com');

        return [
            'name' => $this->name,
            'label' => self::getLabelName($this->name),
            'type' => 'url',
            'max_size' => 255,
            'default' => null,
            'default' => $linkName,
            'module' => $this->module,
            'reportable' => true,
            'gen' => '1',
            'link_target' => '_blank',
        ];
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
