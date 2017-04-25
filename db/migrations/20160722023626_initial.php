<?php

use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
	 *
	 * The following commands can be used in this method and Phinx will
	 * automatically reverse them when rolling back:
	 *
	 *    createTable
	 *    renameTable
	 *    addColumn
	 *    renameColumn
	 *    addIndex
	 *    addForeignKey
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function up() {

		//Create Users table
		$users = $this->table('users');
		$users->addColumn('email', 'string')
			->addColumn('password', 'string')
			->addColumn('country', 'string')
			->addColumn('registered', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
			->create();

		//Create Users table
		$userCars = $this->table('user_cars');
		$userCars->addColumn('user_id', 'integer')
			->addColumn('car_year', 'string')
			->addColumn('car_make', 'string')
			->addColumn('car_model', 'string')
			->addColumn('car_engine', 'string')
			->addColumn('purchase_year', 'integer')
			->addColumn('purchase_amount', 'integer')
			->addColumn('purchase_millage', 'integer')
			->addColumn('photo', 'string')
			->addColumn('registered', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
			->create();

		//Create Master Car Table
		$cars = $this->table('cars');
		$cars->addColumn('car_year', 'string')
			->addColumn('car_make', 'string')
			->addColumn('car_model', 'string')
			->addColumn('car_engine', 'string')
			->addColumn('photo', 'string')
			->addColumn('added', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
			->create();

		//Create Car Repair Table
		$userCars = $this->table('repairs');
		$userCars->addColumn('user_id', 'integer')
			->addColumn('car_id', 'integer')
			->addColumn('item', 'string')
			->addColumn('cost', 'integer')
			->addColumn('shop_name', 'string')
			->addColumn('shop_address', 'string')
			->addColumn('mileage', 'integer')
			->addColumn('date', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
			->create();

		//Create Common Car Repairs List
		$commonRepairs = $this->table('common_repairs');
		$commonRepairs->addColumn('repair', 'string')
			->create();
	}

}