<?php

global $adwp;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$mapID = uniqid('map_');

$aspect = '';
switch ($args['layout_settings']['aspect']) {
  case 'md:21/9':
    $classes = 'aspect-[4/3] md:aspect-[21/9]';
    break;
  case '21/9':
    $classes = 'aspect-[21/9]';
    break;
  case '16/9':
    $classes = 'aspect-video';
    break;
  case '5/4':
    $classes = 'aspect-[5/4]';
    break;
  case '4/3':
    $classes = 'aspect-[4/3]';
    break;
  case '3/2':
    $classes = 'aspect-[3/2]';
    break;
  case '2/1':
    $classes = 'aspect-[2/1]';
    break;
  case '1/1':
    $classes = 'aspect-square';
    break;
  case '4/5':
    $classes = 'aspect-[4/5]';
    break;
  case '3/4':
    $classes = 'aspect-[3/4]';
    break;
  case '2/3':
    $classes = 'aspect-[2/3]';
    break;
  case '1/2':
    $classes = 'aspect-[1/2]';
    break;
  default:
    $classes = 'aspect-video';
    break;
}

?>
<?php if (isset($args['map']['lat']) && !empty($args['map']['lat']) && isset($args['map']['lng']) && !empty($args['map']['lng'])): ?>
  <div class="google-map-wrapper <?= $isFullWidth ? '' : 'px-content' ?> aos animate-fadeinup">
    <div class="media google-map <?= $classes; ?>">
      <div id="<?= $mapID; ?>" class="map w-full h-[calc(100%+24px)]"></div>
    </div>
    <script>
      let script = document.createElement("script");
      script.src =
        "https://maps.googleapis.com/maps/api/js?key=AIzaSyCdaOxRiQZYZg_uL_8L4JP1ZEjm8BIF79A&region=BE&language=fr&callback=initMap_<?= $mapID; ?>";
      script.async = true;

      function initMap_<?= $mapID; ?>() {
        const map = new google.maps.Map(document.getElementById("<?= $mapID; ?>"), {
          center: {
            lat: <?php echo $args['map']['lat']; ?>,
            lng: <?php echo $args['map']['lng']; ?>,
          },
          zoom: <?php echo $args['map']['zoom']; ?>,
          zoomControl: false,
          zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_CENTER,
          },
          streetViewControl: false,
          mapTypeControl: false,
          fullscreenControl: false,
          styles: [],
        });

        const markerLocations = [{
          lat: <?php echo $args['map']['lat']; ?>,
          lng: <?php echo $args['map']['lng']; ?>,
        }, ];

        for (var i = 0; i < markerLocations.length; i++) {
          const marker = markerLocations[i];
          const createMarker = new google.maps.Marker({
            position: marker,
            map: map,
            title: marker.name,
            icon: "data:image/svg+xml,%3Csvg height='70' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 61 70'%3E%3Cpath fill='%23F43C00' d='M30.48 0C14.78 0 3.05 13.17 3.05 28.41 3.05 47.78 30.48 70 30.48 70s27.43-22.22 27.43-41.59C58 13.41 46.28 0 30.48 0Zm0 37.3c-6.07 0-10.97-4.68-10.97-10.47 0-5.8 4.9-10.48 10.97-10.48s10.97 4.68 10.97 10.48c0 5.71-4.9 10.47-10.97 10.47Z'/%3E%3C/svg%3E",
          });
        }
      };

      document.head.appendChild(script);
    </script>
  </div>
<?php endif; ?>