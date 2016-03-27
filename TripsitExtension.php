<?php

$wgExtensionCredits['parserhook'][] = array(
  'path' => _FILE_,
  'name' => 'TripSit Mediawiki Extension',
  'description' => 'Pull information from TripSit factsheets into a wiki',
  'version' => '0.0.1',
  'author' => 'reality'
);

$wgHooks['ParserFirstCallInit'][] = 'ExampleExtension::onParserSetup';

$wgExtensionMessageFiles['TripsitExtension'][] = 'TripsitExtension::onParserSetup';

class TripsitExtension {
  function onParserSetup(&$parser) {
    $parser->setFunctionHook('t_dose', 'TripsitExtension::renderDose');
  }

  function renderDose($parser, $drug='') {
    $result = file_get_contents('http://drugs.tripsit.me/raw/'+$drug);
    $drug = json_decode($result);

    $output = '';

    foreach($drug->formatted_dose as $roa => $dose_expression) {
      $output .= '{| class="wikitable"\n';
      $output .= '|+' . $roa . '\n';
      foreach($dose_expression as $level => $level_dose) {
        $output .= '|-\n';
        $output .= '| ' . $level . ' || ' + $level_dose + '\n';
      }
      $output .= '|}\n';
    }

    return $output;
  }
}
