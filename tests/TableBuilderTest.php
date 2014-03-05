<?php

class TableBuilderTest extends \PHPUnit_Framework_TestCase {

	protected $builder;

	public function setUp()
	{
		$this->builder = new Gloudemans\TableBuilder\TableBuilder;
	}

	public function testBuilderCanBuildFromArray()
	{
		$array = array(
			'name' => 'John Doe',
			'email' => 'john@doe.com',
			'created_at' => '2014-01-01 01:01:00'
		);

		$table = $this->builder->generate(array($array));
		$controlTable = file_get_contents(__DIR__.'/stubs/table-basic.stub');

		$this->assertEquals($controlTable, $table, 'The control table doesn\'t match the generated table');
	}

	public function testBuilderCanBuildFromObject()
	{
		$obj = new stdClass;
		$obj->name = 'John Doe';
		$obj->email = 'john@doe.com';
		$obj->created_at = '2014-01-01 01:01:00';

		$table = $this->builder->generate(array($obj));
		$controlTable = file_get_contents(__DIR__.'/stubs/table-basic.stub');

		$this->assertEquals($controlTable, $table, 'The control table doesn\'t match the generated table');
	}

	public function testBuilderCanBuildFromIlluminateCollection()
	{
		$array = array(
			'name' => 'John Doe',
			'email' => 'john@doe.com',
			'created_at' => '2014-01-01 01:01:00'
		);

		$collection = new Illuminate\Support\Collection($array);

		$table = $this->builder->generate(array($collection));
		$controlTable = file_get_contents(__DIR__.'/stubs/table-basic.stub');

		$this->assertEquals($controlTable, $table, 'The control table doesn\'t match the generated table');
	}

	public function testBuilderCanBuildTableWithArguments()
	{
		$array = array(
			'name' => 'John Doe',
			'email' => 'john@doe.com',
			'created_at' => '2014-01-01 01:01:00'
		);

		$attributes = array(
			'id' => 'table-id',
			'class' => 'table-class'
		);

		$table = $this->builder->generate(array($array), $attributes);
		$controlTable = file_get_contents(__DIR__.'/stubs/table-attributes.stub');

		$crawler = new Symfony\Component\DomCrawler\Crawler($table);

		$this->assertEquals($controlTable, $table, 'The control table doesn\'t match the generated table');
		$this->assertEquals('table-id', $crawler->filter('table')->attr('id'));
		$this->assertEquals('table-class', $crawler->filter('table')->attr('class'));
	}

	public function testBuilderCanBuildTableWithHeaders()
	{
		$array = array(
			'name' => 'John Doe',
			'email' => 'john@doe.com',
			'created_at' => '2014-01-01 01:01:00'
		);

		$headers = array(
			'name' => 'Full Name',
			'email' => 'Emailaddress',
			'created_at' => 'Date Created'
		);

		$table = $this->builder->generate(array($array), null, $headers);
		$controlTable = file_get_contents(__DIR__.'/stubs/table-header.stub');

		$crawler = new Symfony\Component\DomCrawler\Crawler($table);

		$this->assertEquals($controlTable, $table, 'The control table doesn\'t match the generated table');
		$this->assertEquals('Full Name', $crawler->filter('table > thead > tr > th')->eq(0)->text());
		$this->assertEquals('Emailaddress', $crawler->filter('table > thead > tr > th')->eq(1)->text());
		$this->assertEquals('Date Created', $crawler->filter('table > thead > tr > th')->eq(2)->text());
	}

}