function startTracking() {

    Tracker.initalize(map);


    google.maps.event.addListener(map, 'zoom_changed', function () {
        Tracker.trackEvent('zoom_changed');
    });

    google.maps.event.addListener(map, 'dragend', function () {
        Tracker.trackEvent('move');
    });

    google.maps.event.addListener(map, 'maptypeid_changed', function () {
        Tracker.trackEvent('maptypeid_changed');
    });
    google.maps.event.addListener(map, 'click', function (event) {
        Tracker.trackEvent('click', event);
    });

   // Define yout own custom events
};

function stopTracking(){
     google.maps.event.clearListeners(map);
};



function logStart(client) {


    // ID = timestamp - pak se z něj udělá i datetime záznam...
    // zápis do konzole
    // -------------------


    $('#log').html('<strong>ID:</strong> ' + client.id + ' | <strong>Div size:</strong> ' + client.divSize + ' | <strong>Screen size:</strong> ' + client.screenSize + ' | <strong>Client:</strong> ' + client.browser + '('+client.OS+')' + ' <strong>Var: </strong>' + client.variable );



    // XML soubory
    // -------------------
    $.ajax({
        type: "POST",
        data: "id=" + client.id + "&div=" + client.divSize + "&screen=" + client.screenSize + "&browser=" + client.browser + "&os=" + client.OS + "&var=" + client.variable,
        url: 'ajax/startHandler.php',
        success: function (data) {

        }
    });
};

function logEnd(client, value) {


    // ID = timestamp - pak se z něj udělá i datetime záznam...
    // zápis do konzole
    // -------------------

    $('#log').html('<strong>Tracking finished. Answer value: </strong> ' + value);

    // XML soubory
    // -------------------
    $.ajax({
        type: "POST",
        data: "id=" + client.id + "&answer=" + value,
        url: 'ajax/endHandler.php',
        success: function (data) {

        }
    });
};

function log(time, name, center, zoom) {

    // zápis do konzole
    // -------------------
     $('#log').html('<strong>Čas:</strong> ' + time + ' | <strong>Akce:</strong> ' + name + ' | <strong>Střed mapy:</strong> ' + center + ' | <strong>Zoom:</strong> ' + zoom);


    // XML soubory
    // -------------------
    $.ajax({
        type: "POST",
        data: "id=" + Tracker.client.id + "&time=" + time + "&name=" + name + "&center=" + center + "&zoom=" + zoom,
        url: 'ajax/trackHandler.php',
        success: function (data) {

        }
    });


};



function timeStamp() {
    var d = new Date();
    return d.getTime();
};



var Tracker = Tracker || {};



Tracker.initalize = function (map) {
    this.iniZoom = map.getZoom();
    this.prevZoom = map.getZoom();
    this.iniCenter = map.getCenter();
    this.map = map;
    this.startTracking = timeStamp();

    this.client = new Object();
    this.client.id = this.startTracking;
    this.client.divSize = this.map.getDiv().offsetWidth + 'x' + this.map.getDiv().offsetHeight;
    this.client.screenSize = screen.width + 'x' + screen.height;
    this.client.OS = BrowserDetect.OS;
    this.client.browser = BrowserDetect.browser + ' ' + BrowserDetect.version;
    this.client.variable = variable.join('|');

    logStart(this.client);

    Tracker.trackEvent('init', );


};

Tracker.trackEvent = function (event, value) {
    switch (event) {
    case 'zoom_changed':
        if (this.prevZoom > this.map.getZoom()) event_log = 'zoom_out';
        else event_log = 'zoom_in';
        this.prevZoom = this.map.getZoom();
        log((timeStamp() - this.startTracking) / 1000, event_log, this.map.getCenter(), this.map.getZoom());
        break;

    case 'move':
        log((timeStamp() - this.startTracking) / 1000, event, this.map.getCenter(), this.map.getZoom());
        break;

    case 'maptypeid_changed':
        log((timeStamp() - this.startTracking) / 1000, event, this.map.getCenter(), this.map.getZoom());
        break;

    case 'click':

        log((timeStamp() - this.startTracking) / 1000, event, value.latLng, this.map.getZoom());
        break;

    case 'init':
        log(0, event, this.map.getCenter(), this.map.getZoom());
        break;

    default:
        log((timeStamp() - this.startTracking) / 1000, event, this.map.getCenter(), this.map.getZoom());
    }


};


// Brovser detection z http://www.quirksmode.org/js/detect.html
var BrowserDetect = {
    init: function () {
        this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
        this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "an unknown version";
        this.OS = this.searchString(this.dataOS) || "an unknown OS";
    },
    searchString: function (data) {
        for (var i = 0; i < data.length; i++) {
            var dataString = data[i].string;
            var dataProp = data[i].prop;
            this.versionSearchString = data[i].versionSearch || data[i].identity;
            if (dataString) {
                if (dataString.indexOf(data[i].subString) != -1) return data[i].identity;
            } else if (dataProp) return data[i].identity;
        }
    },
    searchVersion: function (dataString) {
        var index = dataString.indexOf(this.versionSearchString);
        if (index == -1) return;
        return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
    },
    dataBrowser: [{
        string: navigator.userAgent,
        subString: "Chrome",
        identity: "Chrome"
    }, {
        string: navigator.userAgent,
        subString: "OmniWeb",
        versionSearch: "OmniWeb/",
        identity: "OmniWeb"
    }, {
        string: navigator.vendor,
        subString: "Apple",
        identity: "Safari",
        versionSearch: "Version"
    }, {
        prop: window.opera,
        identity: "Opera",
        versionSearch: "Version"
    }, {
        string: navigator.vendor,
        subString: "iCab",
        identity: "iCab"
    }, {
        string: navigator.vendor,
        subString: "KDE",
        identity: "Konqueror"
    }, {
        string: navigator.userAgent,
        subString: "Firefox",
        identity: "Firefox"
    }, {
        string: navigator.vendor,
        subString: "Camino",
        identity: "Camino"
    }, { // for newer Netscapes (6+)
        string: navigator.userAgent,
        subString: "Netscape",
        identity: "Netscape"
    }, {
        string: navigator.userAgent,
        subString: "MSIE",
        identity: "Explorer",
        versionSearch: "MSIE"
    }, {
        string: navigator.userAgent,
        subString: "Gecko",
        identity: "Mozilla",
        versionSearch: "rv"
    }, { // for older Netscapes (4-)
        string: navigator.userAgent,
        subString: "Mozilla",
        identity: "Netscape",
        versionSearch: "Mozilla"
    }],
    dataOS: [{
        string: navigator.platform,
        subString: "Win",
        identity: "Windows"
    }, {
        string: navigator.platform,
        subString: "Mac",
        identity: "Mac"
    }, {
        string: navigator.userAgent,
        subString: "iPhone",
        identity: "iPhone/iPod"
    }, {
        string: navigator.platform,
        subString: "Linux",
        identity: "Linux"
    }]

};
BrowserDetect.init();