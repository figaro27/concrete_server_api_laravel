<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Address extends Model
{
    //
  protected $fillable = [
    'id', 'person_id', 'address1', 'address2', 'city', 'state', 'type', 'zip', 'primary'
  ];
  public function person()
  {
    return $this->belongsTo(Person::class);
  }
}
