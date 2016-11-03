<?php

use Phinx\Migration\AbstractMigration;

class InitCommonRepairs extends AbstractMigration
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
	public function change()
	{

		$this->execute("
		INSERT INTO common_repairs (repair) VALUES
			('Oxygen Sensor'),
			('Catalytic Converter'),
			('Fuel Cap'),
			('Ignition Coils'),
			('Mass Air Flow (MAF) Sensor'),
			('Spark Plug Wires'),
			('Spark Plugs'),
			('Thermostat'),
			('Fuel Injector'),
			('Camshaft Position Sensor'),
			('Intake Manifold Gaskets'),
			('ABS Control Module'),
			('Engine Coolant Temperature Sensor'),
			('Throttle Body Assembly'),
			('Crankshaft Position Sensor'),
			('Fuel Tank Pressure (FTP) Sensor'),
			('Wiper Blades'),
			('Brakes'),
			('Tires'),
			('Battery'),
			('Radiator'),
			('Radiator Hose Upper'),
			('Radiator Hose Lower'),
			('AC Recharge'),
			('Brake Fluid'),
			('Antifreeze'),
			('Water Pump'),
			('Timing Belt'),
			('Timing Chain'),
			('Head Light'),
			('Brake Light'),
			('Interior Light'),
			('Windshield Wipers'),
			('Starter'),
			('Alternator'),
			('Oil Change'),
			('Tire Rotation'),
			('Brake Calipers'),
			('Rotors'),
			('Struts'),
			('Shocks'),
			('CV Axel')
		");
	}
}
