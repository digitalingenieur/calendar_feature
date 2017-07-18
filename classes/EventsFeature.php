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


 class EventsFeature extends \Controller
 {

   public function getFeaturedEvents($arrEvents, $arrCalendars, $intStart, $intEnd, $objModule) {

    //Erstelle ein Array, bei dem die Calender Id (event-pid) als key gespeichert wird
    $objModuleCalendarFeatured = deserialize($objModule->cal_calendar_featured);
    if(!empty($objModuleCalendarFeatured)){
      foreach ($objModuleCalendarFeatured as $val){
        $arrCalendarFeatured[$val['calendar']] = $val;
      }  
    }

    // Only show events accordingly to module featured settings
 		foreach ($arrEvents as $key=>$days)
 		{
 			foreach ($days as $day=>$events)
 			{
 				foreach ($events as $arrCol=>$event)
 				{
          switch ($arrCalendarFeatured[$event['pid']]['featured']) {
            case 'featured_events':
              if ($event[featured] != 1) {
                // nicht gefeatured Events löschen
                unset($arrEvents[$key][$day][$arrCol]);
              }
              break;

            case 'unfeatured_events':
              if ($event[featured] == 1) {
                // gefeatured Events löschen
                unset($arrEvents[$key][$day][$arrCol]);
              }
              break;

            case 'all_events':
              // do nothing
              break;

            default:
              // do nothing
              break;
          }
 				}
 			}
 		}

    return $arrEvents;
   }
 }
