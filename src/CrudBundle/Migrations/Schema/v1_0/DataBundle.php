<?php

namespace CrudBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class DataBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::dataTable($schema);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema, QueryBag $queries)
    {
        $schema->dropTable('crud_data');
    }

    /**
     * Generate table crud_data
     *
     * @param Schema $schema
     */
    public static function dataTable(Schema $schema)
    {

        $table = $schema->createTable('crud_data');
        $table->addColumn('id','integer', [
            'autoincrement' => true,
        ]);
        $table->addColumn('name', 'string', [
            'length' => 255
        ]);
        $table->addColumn('visibility', 'boolean', [
            'notnull' => true
        ]);
        $table->addColumn('created_at', 'datetime', [
            'notnull' => true
        ]);
        $table->addColumn('updated_at', 'datetime', [
            'notnull' => false
        ]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['created_by'], 'IDX_7653F987DE12AB56');
        $table->addForeignKeyConstraint('oro_user', ['created_by'], ['id'], [
            'onDelete' => 'SET NULL'
        ]);
    }


}
