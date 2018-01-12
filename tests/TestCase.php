<?php


namespace Dymantic\Secretary\Tests;


use Dymantic\Secretary\SecretaryServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [SecretaryServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Secretary' => 'Dymantic\Secretary\Facades\Secretary'
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);


        $app->bind('path.public', function () {
            return __DIR__ . '/temp';
        });
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
        });
        $app['db']->connection()->getSchemaBuilder()->create('nameless_authors', function (Blueprint $table) {
            $table->increments('id');
        });

        include_once __DIR__ . '/../database/migrations/create_secretary_messages_table.php.stub';
        (new \CreateSecretaryMessagesTable())->up();
    }

}