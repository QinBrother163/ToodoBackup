<?php

namespace App;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;

class TdoSchemaApi
{
    /**
     * @param $blueprint Blueprint
     * @param $columns Column[]
     * @param $primary string|null
     */
    public function createColumns($blueprint, $columns, $primary = null)
    {
        foreach ($columns as $column) {
            /**
             * @param $column
             *  {"name":"id","type":{},"default":null,"notnull":true,
             *   "length":null,"precision":10,"scale":0,"fixed":false,
             *   "unsigned":true,"autoincrement":true,
             *   "columnDefinition":null,"comment":null} */
            $typeName = $column->getType()->getName();
            $columnName = $column->getName();
            //echo "$typeName\t$columnName\n";
            //echo json_encode($column->toArray(),JSON_PRETTY_PRINT)."\n";

            /** @var Fluent $fluent */
            $fluent = null;
            switch ($typeName) {
                case 'char':
                    $fluent = $blueprint->char($columnName, $column->getLength());
                    break;
                case Type::STRING:
                    $fluent = $blueprint->string($columnName, $column->getLength());
                    break;
                case Type::TEXT:
                    $fluent = $blueprint->text($columnName);
                    break;
                case 'mediumtext':
                case 'medium_text':
                case 'mediumText':
                    $fluent = $blueprint->mediumText($columnName);
                    break;
                case 'longtext':
                case 'long_text':
                case 'longText':
                    $fluent = $blueprint->longText($columnName);
                    break;
                case 'tinyint':
                case 'mediumint':
                case Type::SMALLINT:
                    $fluent = $blueprint->smallInteger($columnName, $column->getAutoincrement(), $column->getUnsigned());
                    break;
                case Type::INTEGER:
                    $fluent = $blueprint->integer($columnName, $column->getAutoincrement(), $column->getUnsigned());
                    break;
                case Type::BIGINT:
                    $fluent = $blueprint->bigInteger($columnName, $column->getAutoincrement(), $column->getUnsigned());
                    break;
                case Type::FLOAT:
                    $fluent = $blueprint->float($columnName);
                    break;
                case 'double':
                    $fluent = $blueprint->double($columnName);
                    break;
                case Type::DECIMAL:
                    if ($column->getUnsigned())
                        $fluent = $blueprint->unsignedDecimal($columnName);
                    else
                        $fluent = $blueprint->decimal($columnName);
                    break;
                case Type::BOOLEAN:
                    $fluent = $blueprint->boolean($columnName);
                    break;
                case Type::DATE:
                    $fluent = $blueprint->date($columnName);
                    break;
                case Type::DATETIME:
                    $fluent = $blueprint->dateTime($columnName);
                    break;
                case Type::DATETIMETZ:
                    $fluent = $blueprint->dateTimeTz($columnName);
                    break;
                case Type::TIME:
                    $fluent = $blueprint->time($columnName);
                    break;
                case 'timestamp':
                    $fluent = $blueprint->timestamp($columnName);
                    break;
                case 'timestamptz':
                case 'timestamp_tz':
                case 'timestampTz':
                    $fluent = $blueprint->timestampTz($columnName);
                    break;
                case Type::BINARY:
                    $fluent = $blueprint->binary($columnName);
                    break;
                case Type::GUID:
                    $fluent = $blueprint->uuid($columnName);
                    break;
                /**
                 * const STRING = 'string';
                 * const TEXT = 'text';
                 * const SMALLINT = 'smallint';
                 * const INTEGER = 'integer';
                 * const BIGINT = 'bigint';
                 * const FLOAT = 'float';
                 * const DECIMAL = 'decimal';
                 * const BOOLEAN = 'boolean';
                 * const DATE = 'date';
                 * const DATETIME = 'datetime';
                 * const DATETIMETZ = 'datetimetz';
                 * const TIME = 'time';
                 * const BINARY = 'binary';
                 * const GUID = 'guid';
                 *
                 * const TARRAY = 'array';
                 * const SIMPLE_ARRAY = 'simple_array';
                 * const JSON_ARRAY = 'json_array';
                 * const OBJECT = 'object';
                 * const BLOB = 'blob';
                 */
                default:
                    $fluent = $blueprint->text($columnName);
                    break;
            }
            /** @noinspection PhpUndefinedMethodInspection */
            $fluent->comment($column->getComment());
            if (!$column->getNotnull()) {
                /** @noinspection PhpUndefinedMethodInspection */
                $fluent->nullable();
            }
            if ($primary == $columnName && !$column->getAutoincrement()) {
                /** @noinspection PhpUndefinedMethodInspection */
                $fluent->primary();
            }

        }
    }
}