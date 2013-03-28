<?php

  class ctrl_option_group {
    public $data = array();
    
    public function __construct($group_id=null) {
      global $system;
      
      $this->system = &$system;
      
      $this->reset();
      
      if ($group_id !== null) $this->load($group_id);
    }
    
    public function reset() {
      $this->data = array(
        'id' => '',
        'function' => '',
        'name' => array(),
        'description' => array(),
        'values' => array(),
        'sort' => 'alphabetical'
      );
    }
    
    public function load($group_id) {
      
      $option_group_query = $this->system->database->query(
        "select * from ". DB_TABLE_OPTION_GROUPS ."
        where id = '". (int)$group_id ."'
        limit 1;"
      );
      $this->data = $this->system->database->fetch($option_group_query);
      if (empty($this->data)) trigger_error('Could not find option_group ('. $group_id .') in database.', E_USER_ERROR);
      
      $option_groups_info_query = $this->system->database->query(
        "select * from ". DB_TABLE_OPTION_GROUPS_INFO ."
        where group_id = '". (int)$group_id ."';"
      );
      while ($option_group_info = $this->system->database->fetch($option_groups_info_query)) {
        foreach (array_keys($option_group_info) as $key) {
          if (in_array($key, array('id', 'group_id', 'language_code'))) continue;
          $this->data[$key][$option_group_info['language_code']] = $option_group_info[$key];
        }
      }
      
      $option_values_query = $this->system->database->query(
        "select * from ". DB_TABLE_OPTION_VALUES ."
        where group_id = '". (int)$group_id ."'
        order by priority;"
      );
      while ($option_value = $this->system->database->fetch($option_values_query)) {
      
        $this->data['values'][$option_value['id']] = $option_value;
        
        $option_values_info_query = $this->system->database->query(
          "select * from ". DB_TABLE_OPTION_VALUES_INFO ."
          where value_id = '". (int)$option_value['id'] ."';"
        );
        while ($option_value_info = $this->system->database->fetch($option_values_info_query)) {
          foreach (array_keys($option_value_info) as $key) {
            if (in_array($key, array('id', 'group_id', 'language_code'))) continue;
            $this->data['values'][$option_value['id']][$key][$option_value_info['language_code']] = $option_value_info[$key];
          }
        }
      }
    }
    
    public function save() {
      
    // Configuration group
      if (empty($this->data['id'])) {
        $this->system->database->query(
          "insert into ". DB_TABLE_OPTION_GROUPS ."
          (date_created)
          values ('". $this->system->database->input(date('Y-m-d H:i:s')) ."');"
        );
        $this->data['id'] = $this->system->database->insert_id();
      }
      
      $this->system->database->query(
        "update ". DB_TABLE_OPTION_GROUPS ."
        set function = '". $this->system->database->input($this->data['function']) ."',
        required = '". (int)$this->data['required'] ."',
        sort = '". $this->system->database->input($this->data['sort']) ."'
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
      );
      
    // Configuration group info
      foreach (array_keys($this->system->language->languages) as $language_code) {
        
        $option_groups_info_query = $this->system->database->query(
          "select id from ". DB_TABLE_OPTION_GROUPS_INFO ."
          where group_id = '". (int)$this->data['id'] ."'
          and language_code = '". $this->system->database->input($language_code) ."'
          limit 1;"
        );
        $option_group_info = $this->system->database->fetch($option_groups_info_query);
        
        if (empty($option_group_info)) {
          $this->system->database->query(
            "insert into ". DB_TABLE_OPTION_GROUPS_INFO ."
            (group_id, language_code)
            values ('". (int)$this->data['id'] ."', '". $this->system->database->input($language_code) ."');"
          );
          $option_group_info['id'] = $this->system->database->insert_id();
        }
        
        $this->system->database->query(
          "update ". DB_TABLE_OPTION_GROUPS_INFO ."
          set name = '". $this->system->database->input($this->data['name'][$language_code]) ."',
            description = '". @$this->system->database->input($this->data['description'][$language_code]) ."'
          where id = '". (int)$option_group_info['id'] ."'
          and group_id = '". (int)$this->data['id'] ."'
          and language_code = '". $this->system->database->input($language_code) ."'
          limit 1;"
        );
      }
      
    // Delete option values
      $option_values_query = $this->system->database->query(
        "select id from ". DB_TABLE_OPTION_VALUES ."
        where group_id = '". (int)$this->data['id'] ."'
        and id not in ('". @implode("', '", @array_keys($this->data['values'])) ."');"
      );
      
      while ($option_value = $this->system->database->fetch($option_values_query)) {
        
        $products_options_stock_query = $this->system->database->query(
          "select id from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
          where combination like '%". (int)$this->data['id'] ."-". (int)$option_value['id'] ."%';"
        );
        if ($this->system->database->num_rows($products_options_stock_query) > 0) trigger_error('Cannot delete option value linked to products.', E_USER_ERROR);
      
        $this->system->database->query(
          "delete from ". DB_TABLE_OPTION_VALUES ."
          where group_id = '". (int)$this->data['id'] ."'
          and id = '". (int)$option_value['id'] ."'
          limit 1;"
        );
        $this->system->database->query(
          "delete from ". DB_TABLE_OPTION_VALUES_INFO ."
          where value_id = '". (int)$option_value['id'] ."';"
        );
      }
      
    // Update/Insert option values
      $i=0;
      foreach ($this->data['values'] as $option_value) {
        $i++;
        
        if (empty($option_value['id'])) {
          $this->system->database->query(
            "insert into ". DB_TABLE_OPTION_VALUES ."
            (group_id)
            values ('". $this->data['id'] ."');"
          );
          $option_value['id'] = $this->system->database->insert_id();
        }
        
        $this->system->database->query(
          "update ". DB_TABLE_OPTION_VALUES ."
          set value = '". @$this->system->database->input($option_value['value']) ."',
            priority = '". (int)$i ."'
          where id = '". (int)$option_value['id'] ."'
          limit 1;"
        );
        
        foreach (array_keys($this->system->language->languages) as $language_code) {
          if (!isset($option_value['name'])) continue;
          
          $option_value_info_query = $this->system->database->query(
            "select id from ". DB_TABLE_OPTION_VALUES_INFO ."
            where value_id = '". (int)$option_value['id'] ."'
            and language_code = '". $this->system->database->input($language_code) ."'
            limit 1;"
          );
          $option_value_info = $this->system->database->fetch($option_value_info_query);
          
          if (empty($option_value_info)) {
            $this->system->database->query(
              "insert into ". DB_TABLE_OPTION_VALUES_INFO ."
              (value_id, language_code)
              values ('". $option_value['id'] ."', '". $this->system->database->input($language_code) ."');"
            );
            $option_value_info['id'] = $this->system->database->insert_id();
          }
          
          $this->system->database->query(
            "update ". DB_TABLE_OPTION_VALUES_INFO ."
            set name = '". $this->system->database->input($option_value['name'][$language_code]) ."'
            where id = '". (int)$option_value_info['id'] ."'
            and value_id = '". (int)$option_value['id'] ."'
            and language_code = '". $this->system->database->input($language_code) ."'
            limit 1;"
          );
        }
      }
      
      $this->system->cache->set_breakpoint();
    }
    
    public function delete() {
    
      if (empty($this->data['id'])) return;
    
    // Check products for option group
      $products_options_stock_query = $this->system->database->query(
        "select id from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
        where combination like '%". (int)$this->data['id'] ."-%';"
      );
      if ($this->system->database->num_rows($products_options_stock) > 0) trigger_error('Cannot delete option group linked to products.', E_USER_ERROR);
    
    // Check products for option values
      $option_values_query = $this->system->database->query(
        "select id from ". DB_TABLE_OPTION_VALUES ."
        where group_id = '". (int)$this->data['id'] ."'
        and id not in ('". @implode("', '", @array_keys($this->data['values'])) ."');"
      );
      
      while ($option_value = $this->system->database->fetch($option_values_query)) {
        
        $products_options_query = $this->system->database->query(
          "select id from ". DB_TABLE_PRODUCTS_OPTIONS ."
          where combination like '%". (int)$this->data['id'] ."-". (int)$option_value['id'] ."%';"
        );
        if ($this->system->database->num_rows($products_options_query) > 0) trigger_error('Cannot delete option value linked to products.', E_USER_ERROR);
        
      // Delete option values
        $this->system->database->query(
          "delete from ". DB_TABLE_OPTION_VALUES ."
          where group_id = '". (int)$this->data['id'] ."'
          and id = '". (int)$option_value['id'] ."'
          limit 1;"
        );
        $this->system->database->query(
          "delete from ". DB_TABLE_OPTION_VALUES_INFO ."
          where value_id = '". (int)$option_value['id'] ."';"
        );
      }
      
    // Delete option group
      $this->system->database->query(
        "delete from ". DB_TABLE_OPTION_GROUPS ."
        where id = '". (int)$this->data['id'] ."'
        limit 1;"
      );
      
      $this->system->database->query(
        "delete from ". DB_TABLE_OPTION_GROUPS_INFO ."
        where group_id = '". (int)$this->data['id'] ."';"
      );
      
      $this->data['id'] = null;
      
      $this->system->cache->set_breakpoint();
    }
  }

?>