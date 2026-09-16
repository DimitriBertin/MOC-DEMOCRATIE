<section class="contact-section theme-white bg-layout-main">
    <div class="flex flex-col md:flex-row md:items-end @sm:gap-[70px] @md/lg:gap-[106px]">
      <!-- Map Section -->
        <?php if (!empty($args['map'])): ?>
          <div class="map-container relative w-full max-sm:h-[339px] md:h-[450px] lg:h-[550px] @sm:rounded-lg @md/lg:rounded-lg overflow-hidden">
            <div class="acf-map w-full h-full" data-zoom="<?php echo esc_attr($args['map']['zoom'] ?? 12); ?>">
              <div class="marker" data-lat="<?php echo esc_attr($args['map']['lat']); ?>" data-lng="<?php echo esc_attr($args['map']['lng']); ?>">
                <h4><?php echo esc_html($args['map']['address']); ?></h4>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="bg-dark-green aspect-[629/552] md:basis-3/5 max-h-[80dvh] mm-sm:min-h-[340px]">
          </div>
        <?php endif; ?>

      <!-- Contact Information Section -->
      <div class="flex md:basis-2/5 flex-col justify-center @sm:px-4 @sm:pb-7 @md/lg:py-12 @md/lg:pr-6 @md/lg:pl-0">
        <?php if (!empty($args['title'])): ?>
          <h2 class="heading-xl heading-primary autoscale @sm:mb-8 @md/lg:mb-12">
            <?php echo esc_html($args['title']); ?>
          </h2>
        <?php endif; ?>

        <?php if (!empty($args['information'])): ?>
          <div class="contact-info-list autoscale flex flex-col @sm:gap-5 @md/lg:gap-3">
            <?php foreach ($args['information'] as $info): ?>
              <div class="contact-info-item flex mm-sm:flex-col @sm:gap-1.5 md:flex-row @md/lg:gap-6">
                <div class="paragraph-md paragraph-primary font-semibold mm-sm:w-max md:basis-2/5">
                  <?php echo esc_html($info['title']); ?>
                </div>
                <div class="paragraph-md paragraph-primary font-light mm-sm:w-max md:basis-3/5">
                  <?php echo wp_kses_post($info['value']); ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
</section>

<style>
.acf-map {
  width: 100%;
  height: 100%;
  position: relative;
  background: #96FAC2;
}

.acf-map img {
  max-width: inherit !important;
}

.acf-map .marker {
  position: absolute;
  left: 50%;
  top: 50%;
}

@media (max-width: 640px) {
  .acf-map .marker {
    width: 25px;
    height: 34px;
    margin-left: -12.5px;
    margin-top: -34px;
  }
}

@media (min-width: 641px) {
  .acf-map .marker {
    width: 41px;
    height: 56px;
    margin-left: -20.5px;
    margin-top: -56px;
  }
}
</style>

<script>
(function($) {
  function initMap( $el ) {
    var $markers = $el.find('.marker');
    var mapArgs = {
      zoom: $el.data('zoom') || 16,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      styles: [
        {
          "featureType": "all",
          "elementType": "all",
          "stylers": [
            { "saturation": -100 }
          ]
        }
      ]
    };
    var map = new google.maps.Map( $el[0], mapArgs );

    map.markers = [];

    $markers.each(function(){
      initMarker( $(this), map );
    });

    centerMap( map );
  }

  function initMarker( $marker, map ) {
    var lat = $marker.data('lat');
    var lng = $marker.data('lng');
    var latLng = {
      lat: parseFloat( lat ),
      lng: parseFloat( lng )
    };

    var marker = new google.maps.Marker({
      position : latLng,
      map: map,
      icon: {
        path: 'M20.5047 0.201172C8.75211 0.201172 0 10.6718 0 22.7789C0 38.1393 20.5047 55.7983 20.5047 55.7983C20.5047 55.7983 41 38.1393 41 22.7789C41 10.8374 32.2094 0.201172 20.5047 0.201172ZM20.5047 31.4672C18.6444 31.4692 16.8253 30.9104 15.2776 29.8616C13.7298 28.8129 12.523 27.3212 11.8097 25.5754C11.0965 23.8296 10.9089 21.908 11.2707 20.0539C11.6325 18.1997 12.5273 16.4963 13.8421 15.159C15.1569 13.8218 16.8326 12.9107 18.657 12.5412C20.4815 12.1718 22.3728 12.3604 24.0917 13.0833C25.8106 13.8062 27.2799 15.0309 28.3137 16.6025C29.3474 18.174 29.8992 20.0218 29.8992 21.912C29.8992 24.4445 28.9098 26.8735 27.1482 28.6651C25.3867 30.4568 22.9972 31.4647 20.5047 31.4672Z',
        fillColor: '#012E31',
        fillOpacity: 1,
        strokeWeight: 0,
        scale: 1,
        anchor: new google.maps.Point(20.5, 56)
      }
    });

    map.markers.push( marker );

    if( $marker.html() ) {
      var infowindow = new google.maps.InfoWindow({
        content: $marker.html()
      });

      google.maps.event.addListener(marker, 'click', function() {
        infowindow.open( map, marker );
      });
    }
  }

  function centerMap( map ) {
    var bounds = new google.maps.LatLngBounds();

    map.markers.forEach(function( marker ){
      bounds.extend({
        lat: marker.position.lat(),
        lng: marker.position.lng()
      });
    });

    if( map.markers.length == 1 ){
      map.setCenter( bounds.getCenter() );
    } else {
      map.fitBounds( bounds );
    }
  }

  $(document).ready(function(){
    $('.acf-map').each(function(){
      initMap( $(this) );
    });
  });

})(jQuery);
</script>
