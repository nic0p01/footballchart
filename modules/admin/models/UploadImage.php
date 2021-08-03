<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadImage extends Model{

    public $image;

    public function rules(){
        return[
        [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload(){
        if($this->validate()){
            $this->image->saveAs("upload/{$this->image->baseName}.{$this->image->extension}");
            return $this->image->baseName.'.'.$this->image->extension;
        }else{
            return false;
        }
    }

}
