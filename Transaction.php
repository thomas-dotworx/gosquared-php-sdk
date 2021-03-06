<?php

/**
 * Track transactions
 * https://beta.gosquared.com/docs/tracking/api/#transactions
 */

class GoSquaredTransaction{
  public $id;

  function __construct($GS, $id, $opts = array(), $person = false){
    $this->GS = $GS;
    $this->id = $id;
    $this->items = array();
    $this->opts = $opts;
    $this->person = $person;
  }

  /**
   * Add a single item to the transaction to be tracked
   * @param  string  $name      The name of the item/product
   * @param  array   $opts      Details about the item - revenue, quantity etc
   */
  function add_item($name, $opts = array()){
    if (is_array($name)) {
      $opts = $name;
    } else {
      $opts['name'] = $name;
    }

    $this->items[] = $opts;
  }

  /**
   * Add an array of items to the transaction to be tracked
   * @param  array   $items     Array of items containing details about the item - name, revenue, quantity etc
   */
  function add_items($items){
    foreach($items as $item){
      $this->add_item($item);
    }
  }

  /**
   * Send the transaction off to be tracked
   * @return mixed              Decoded JSON response object, or false on failure.
   */
  function track(){
    $body = array();
    if (is_object($this->person)) $body['person_id'] = $this->person->id;
    $transaction = array();
    $transaction['id'] = $this->id;
    $transaction['items'] = $this->items;
    $transaction['opts'] = $this->opts;
    $body['transaction'] = $transaction;

    return $this->GS->exec('/tracking/v1/transaction', array(), $body);
  }
}

?>
