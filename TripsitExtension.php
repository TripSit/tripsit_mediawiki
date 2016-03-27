<?php

$wgExtensionCredits['tripsitextension'][] = array(
  'path' => __FILE__,
  'name' => 'TripSit Mediawiki Extension',
  'description' => 'Pull information from TripSit factsheets into a wiki',
  'version' => '0.0.1',
  'author' => 'reality',
  'url' => 'test'
);

$wgExtensionMessagesFiles['TripsitExtension'] = __DIR__ . '/TripsitExtension.i18n.php';
$wgHooks['ParserFirstCallInit'][] = 'TripsitExtension::onParserSetup';

class TripsitExtension {
  function onParserSetup(&$parser) {
    $parser->setFunctionHook('tdose', 'TripsitExtension::renderDose');
  }

  function renderDose($parser, $param1='') {
    $parser->disableCache();

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, 'http://drugs.tripsit.me/raw/'.$param1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POST, false);
	$result = curl_exec($ch);
	curl_close($ch);

    $drug = json_decode($result);

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

    return $output;
  }
}
