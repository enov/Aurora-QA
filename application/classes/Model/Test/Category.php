<?php

class Model_Test_Category
{

	public $id;
	public $label;
}

class Aurora_Test_Category implements Interface_Aurora_Database,
  Interface_Aurora_JSON_Serialize,
  Interface_Aurora_JSON_Deserialize
{

	public $table = 'categories';

	public function db_persist($model) {
		return array(
			'id' => $model->$id,
			'label' => $model->$label,
		);
	}

	public function db_retrieve($model, array $row) {
		$tbl = Au::db()->table($this);
		$model->id = $row[$tbl . '.id'];
		$model->label = $row[$tbl . '.label'];
	}

	public function json_deserialize($json) {
		$model = new Model_Test_Category;
		$model->id = $json->id;
		$model->label = $json->label;
	}

	public function json_serialize($model) {
		return $model;
	}
}

class Collection_Test_Category extends Collection
{

}