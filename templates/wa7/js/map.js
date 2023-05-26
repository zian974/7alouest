/**
 * @package     7aw
 * @subpackage  Templates.7aw
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.7alouest.re/>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */



( function() {
   

    let map,
        zoom = 17,
        activeLayer = "osm";
    
    const view = new ol.View({
        zoom: zoom,
        center: ol.proj.fromLonLat([ 55.288872, -21.165452]),
        projection: 'EPSG:3857'
    });

    view.setConstrainResolution(true);

    map = new ol.Map({
        controls: [],
        layers: [],
        target: "ol-map"
    });

    map.setView( view );
    
    
    const osm = new ol.layer.Tile({
        source: new ol.source.OSM()
    });
    map.addLayer( osm );
    
    
    let zoomIn = document.querySelector(".btn-zoom-in");   
    let zoomOut = document.querySelector(".btn-zoom-out");
    
    zoomIn.addEventListener('click', () => {
        zoom++;
        view.setZoom( zoom );
    });
    
    zoomOut.addEventListener('click', () => {
        zoom--;
        view.setZoom( zoom );
    });
//    
//    
//    
    const ignWms = new ol.layer.Tile({
        source: new ol.source.TileWMS( {
            url: 'https://wxs.ign.fr/essentiels/geoportail/r/wms',
            params: {'LAYERS': 'ORTHOIMAGERY.ORTHOPHOTOS', 'TILED': true},
            projection: 'EPSG:3857',
            attributions:
                '<a href="http://www.ign.fr" target="_blank">' +
                '<img src="https://wxs.ign.fr/static/logos/IGN/IGN.gif" title="Institut national de l\'' +
                'information géographique et forestière" alt="IGN"></a>',
            }),
        visible: false
    });

    map.addLayer( ignWms );
    
    let layerToggler = document.querySelector(".btn-layer-toggle");   
    let icon = document.querySelector(".btn-layer-toggle .material-icons");   
    
    layerToggler.addEventListener('click', () => {
        
        console.log(activeLayer)
        if ( activeLayer == 'osm' ) {
            activeLayer = "ignWms";  
            ignWms.setVisible(true);     
            osm.setVisible(false);   
            icon.innerHTML = "map";
        }
        else {
            activeLayer = "osm";     
            ignWms.setVisible(false);     
            osm.setVisible(true);     
            icon.innerHTML = "satellite_alt";  
        }
    });
    
    const features = [];
      features.push(new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([ 55.288872, -21.165452]))
      }));
    
    
    
    let vectorLayer = new ol.layer.Vector({
        source: new ol.source.Vector({features})
    });
    
    map.addLayer( vectorLayer );
    
} )();


