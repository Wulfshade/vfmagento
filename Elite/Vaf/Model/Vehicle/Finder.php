<?php
/**
* Vehicle Fits Free Edition - Copyright (c) 2008-2010 by Ne8, LLC
* PROFESSIONAL IDENTIFICATION:
* "www.vehiclefits.com"
* PROMOTIONAL SLOGAN FOR AUTHOR'S PROFESSIONAL PRACTICE:
* "Automotive Ecommerce Provided By Ne8 llc"
*
* All Rights Reserved
* VEHICLE FITS ATTRIBUTION ASSURANCE LICENSE (adapted from the original OSI license)
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the conditions in license.txt are met
*/
class Elite_Vaf_Model_Vehicle_Finder implements Elite_Vaf_Configurable
{
    protected $schema;
    
    /** @var Zend_Config */
    protected $config;
    
    function __construct( Elite_Vaf_Model_Schema $schema )
    {
        $this->schema = $schema;
    }
    
    function findAll()
    {
        $columnsToSelect = array('id')+$this->getColumns();
        
        $select = $this->getReadAdapter()->select()
        	->from( 'elite_definition', $columnsToSelect );
        $this->addJoins($select, false);
        
        foreach($this->schema->getLevels() as $level)
        {
            $select->where('elite_definition.' . $level .'_id != 0');
        }
        
        $r = $this->query($select);
        if( !$r )
        {
            throw new Exception( mysql_error() );
        }
        $return = array();
        while( $row = $r->fetchObject() )
        {
            array_push($return, new Elite_Vaf_Model_Vehicle( $this->schema, $row->id, $row ) );
        }
        return $return;
    }
        
    function findById( $id )
    {
        $identityMap = Elite_Vaf_Model_Vehicle_Finder_IdentityMap::getInstance();
        if($identityMap->has($id))
        {
            return $identityMap->get($id);
        }
        
        $select = $this->getReadAdapter()->select()
        	->from( 'elite_definition', $this->getColumns() );
        $this->addJoins($select, false);
        $select->where('elite_definition.id = ?', $id );
            
        $r = $this->query($select);
        if( !$r )
        {
            throw new Exception( mysql_error() );
        }
        $row = $r->fetchObject();
        if( !is_object( $row ) )
        {
            throw new Exception( 'No such definition with id [' . $id . ']' );
        }
        
        $vehicle = new Elite_Vaf_Model_Vehicle( $this->schema, $id, $row );
        $identityMap->add($vehicle);
        return $vehicle;
    }
    
    function findByLevel( $level, $id )
    {
        if( !(int)$id )
        {
            throw new Exception( 'must pass an level_id, [' . $id . '] given' );
        }
        
        $identityMap = Elite_Vaf_Model_Vehicle_Finder_IdentityMapByLevel::getInstance();
        if($identityMap->has($level,$id))
        {
            return $identityMap->get($level,$id);
        }
        
        $select = $this->getReadAdapter()->select()
        	->from( 'elite_definition', $this->cols($level) )
        	->where( sprintf('%s_id = ?',$level), $id )
        	->limit(1);

        $r = $this->query(  $select );
        $row = $r->fetchObject();
        if( !is_object( $row ) )
        {
            throw new Elite_Vaf_Exception_DefinitionNotFound( 'No such definition with level [' . $level . '] and id [' . $id . ']' );
        }
        
        $vehicle = new Elite_Vaf_Model_Vehicle( $this->schema, null, $row, $level );
        $identityMap->add($vehicle);
        return $vehicle;
    }
    
    /**
    * @param array conjunction of critera Ex: ('make'=>'honda','year'=>'2000') 
    * @return array of Vehicle that meet the critera
    */
    function findByLevels( $levels, $includePartials = false )
    {
        $select = new Elite_Vaf_Select($this->getReadAdapter());
        $select
            ->from( 'elite_definition' )
            ->addLevelTitles('elite_definition');
        
        foreach($levels as $level=>$value)
        {
        	if(strpos($value,'-') || false !== strpos($value,'*'))
        	{
                $value = $this->regexifyValue($value);
                $where = $this->getReadAdapter()->quoteInto('elite_'.$level.'.title RLIKE ?', '^' . $value . '$' );
                $select->where($where);
			}
            else
			{
                $select->where('elite_'.$level.'.title = ?',$value);
			}
        }
          
        if(!$includePartials)
        {
            foreach($this->schema->getLevels() as $level)
            {
                $select->where('elite_definition.' . $level .'_id != 0');
            }
        }
        
        $result = $this->query($select)->fetchAll(Zend_Db::FETCH_OBJ);
        $return = array();
        foreach( $result as $row )
        {
            $vehicle = new Elite_Vaf_Model_Vehicle($this->schema, $row->id, $row );
            array_push($return, $vehicle);
        }
        return $return;
    }
    
    function regexifyValue($value)
    {
        $value = str_replace(array('-','*'), array('##hyphen##','##dash##'), $value);
        $value = preg_quote($value);
        $value = str_replace(array('##hyphen##','##dash##'), array('-','*'), $value);
        
        $value = preg_replace('#\*#','.*',$value);
        $value = preg_replace('#[ -]#','[ -]',$value);
        return $value;
    }
    
    /**
    * @param array conjunction of critera Ex: array('make'=>1'year'=>1) 
    * @return array of Vehicle that meet the critera
    */
    function findByLevelIds($levelIds,$includePartials=false)
    {
        $select = new Elite_Vaf_Select($this->getReadAdapter());
        $select
            ->from( 'elite_definition' )
            ->addLevelTitles('elite_definition');
        
        foreach( $this->schema->getLevels() as $level )
        {
            $value = false;
            $value = isset($levelIds[$level]) ? $levelIds[$level] : $value;
            $value = isset($levelIds[$level . '_id']) ? $levelIds[$level . '_id'] : $value;
            if( $value !== false )
            {
                $select->where('`elite_definition`.`' . $level . '_id` = ?',$value);
            }
        }
        
        if(!$includePartials)
        {
            foreach($this->schema->getLevels() as $level)
            {
                $select->where('elite_definition.' . $level .'_id != 0');
            }
        }
        
        $result = $this->query($select)->fetchAll(Zend_Db::FETCH_OBJ);
        
        $return = array();
        foreach( $result as $row )
        {
            $vehicle = new Elite_Vaf_Model_Vehicle( $this->schema, $row->id, $row );
            array_push($return, $vehicle);
        }
        return $return;
    }
    
    /**
    * @param array ('make'=>'honda','year'=>'2000') conjunction of critera
    * @return Elite_Vaf_Model_Vehicle or false
    */
    function findOneByLevels($levels)
    {
		$vehicles = $this->findByLevels($levels);
		return isset($vehicles[0]) ? $vehicles[0] : false;
    }
    
    function findByLeaf( $leaf_id )
    {
        return $this->findByLevel( $this->schema->getLeafLevel(), $leaf_id );
    }
    
    function vehicleExists(array $levelTitles)
    {
        return 0 != count($this->findByLevels($levelTitles));
    }
    
    function getConfig()
    {
        if( !$this->config instanceof Zend_Config )
        {
            $this->config = Elite_Vaf_Helper_Data::getInstance()->getConfig();
        }    
        return $this->config;
    }
    
    function setConfig( Zend_Config $config )
    {
        $this->config = $config;
    }
    
    function getColumns()
    {
        $columns = array();
        $levels = $this->schema->getLevels(); 
        
        foreach( $levels as $level )
        {
            $columns[ $level.'_id' ] = 'elite_'.$level . '.id';
            $columns[ $level ]		 = 'elite_'.$level . '.title';
        }
        return $columns;
    }
    
    function addJoins(Zend_Db_Select $select, $noRoot = false )
    {
        $joins = '';
        $levels = $this->schema->getLevels(); 
        
        foreach( $levels as $level )
        {
        	$condition = sprintf( '`elite_%1$s`.`id` = `elite_definition`.`%1$s_id`', $level );
            $select->joinLeft('elite_'.$level, $condition ); 
        }
    }
    
    /** @return Zend_Db_Statement_Interface */
    protected function query( $sql )
    {
        return $this->getReadAdapter()->query( $sql );
    }
    
    /** @return Zend_Db_Adapter_Abstract */
    protected function getReadAdapter()
    {
        return Elite_Vaf_Helper_Data::getInstance()->getReadAdapter();
    }
    
    protected function cols( $stopLevel = false )
    {
    	$cols = array();
		foreach( $this->schema->getLevels() as $level )
		{
			array_push($cols,$level.'_id');
			if( $stopLevel && $level == $stopLevel )
			{
				return $cols;
			}
		}
		return $cols;
    }
}