<?php

class Model_Category
{

	public $id;
	public $label;
}

class Aurora_Category implements Interface_Aurora_Database, Interface_Aurora_JSON_Serialize, Interface_Aurora_JSON_Deserialize
{
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

	}
	public function json_serialize($object) {

	}
}

class Collection_Category extends Collection
{

}