<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   CalendarFeature
 * @author    Sebastian Buck
 * @license   LGPL
 * @copyright Erdmann & Freunde
 */



 // Anpassung der Palette
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist'] = str_replace(
     //'perPage',
     //'perPage,events_featured,cal_featured',
     'cal_calendar',
     'cal_calendar_featured',
     $GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']
 );

$GLOBALS['TL_DCA']['tl_module']['config']['onsubmit_callback'][] = array(
	'tl_module_calendar_feature','saveCalFeatured'
	);

// Feld hinzufügen
$GLOBALS['TL_DCA']['tl_module']['fields']['events_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['events_featured'],
	'default'                 => 'all_items',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_events', 'featured_events', 'unfeatured_events'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50 long'),
	'sql'                     => "varchar(18) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_calendar_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cal_calendar'],
	'exclude'                 => true,
	'inputType'               => 'multiColumnWizard',
	'eval'                    => array(
			'columnFields' => array
				(
				'calendar' => array
					(
						'inputType' => 'select',
						'options_callback' => array('tl_module_calendar','getCalendars'),
						'eval' => array
							(
								'style' => 'width:250px'
							)
					),
				'featured' => array
					(
						'inputType' => 'select',
						'options' 	=> array('all_events', 'featured_events', 'unfeatured_events'),
						'default'	=> 'all_items',
						'reference' => &$GLOBALS['TL_LANG']['tl_module'],
						'eval' => array
							(
								'style' => 'width:250px'
							)
					)
				)
		),
	'sql'	=> "blob NULL"
);

class tl_module_calendar_feature extends Backend{

	public function saveCalFeatured(DataContainer $dc){

		// Return if there is no active record (override all)
		if (!$dc->activeRecord)
		{
			return;
		}

		$calendar_feature = deserialize($dc->activeRecord->cal_calendar_featured);

		$arrSet['cal_calendar']=array();
			if(isset($calendar_feature)){
				foreach($calendar_feature as $key=>$entry){
				if(in_array($entry['calendar'],$arrSet['cal_calendar'])){
					unset($calendar_feature[$key]);
				}
				else{
					$arrSet['cal_calendar'][] = $entry['calendar'];	
				}
			}
		}
		
		$arrSet['cal_calendar_featured'] = $calendar_feature;

		$this->Database->prepare("UPDATE tl_module %s WHERE id=?")->set($arrSet)->execute($dc->id);
	}
}