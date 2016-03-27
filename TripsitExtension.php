<?php

$wgExtensionCredits['tripsitextension'][] = array(
  'path' => __FILE__,
  'name' => 'TripSit Mediawiki Extension',
  'description' => 'Pull information from TripSit factsheets into a wiki page',
  'version' => '0.0.1',
  'author' => 'reality',
  'url' => 'https://github.com/TripSit/tripsit_mediawiki'
);

$wgExtensionMessagesFiles['TripsitExtension'] = __DIR__ . '/TripsitExtension.i18n.php';
$wgHooks['ParserFirstCallInit'][] = 'TripsitExtension::onParserSetup';

class TripsitExtension {
  function onParserSetup(&$parser) {
    $parser->setFunctionHook('tdose', 'TripsitExtension::renderDose');
  }

  function getDrug($name) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, 'http://drugs.tripsit.me/raw/'.$name);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, false);
	$result = curl_exec($ch);
	curl_close($ch);

    return json_decode($result);
  }

  function renderDose($parser, $name='') {
    $drug = self::getDrug($name);

    $output = '';

    foreach($drug->formatted_dose as $roa => $dose_expression) {
      $output .= "{| class=\"wikitable\"\n";
      $output .= "|+" . $roa . "\n";
      foreach($dose_expression as $level => $level_dose) {
        $output .= "|-\n";
        $output .= "| " . $level . " || " . $level_dose . "\n";
      }
      $output .= "|}\n";
    }

	$output .= 'Dosages from [http://drugs.tripsit.me/'.$name.' TripSit Factsheets]';

    return $output;
  }
}
