<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Genome Maps</title>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- Chrome for Android theme color -->
    <!--<meta name="theme-color" content="#303F9F">-->
    <meta name="theme-color" content="#000000">

    <!-- Web Application Manifest -->
    <link rel="manifest" href="{{ asset('genome/manifest.json') }}">

    <!-- Chrome for Android Theme color -->
    <meta name="msapplication-TileColor" content="#3372DF">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Genome Maps">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Genome Maps">

    <!-- Force Microsoft use latest web tech -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- END meta -->

    <!--Config-->
    <script src="{{ asset('genome/conf/config.js') }}"></script>

    <!--Fonts-->
    <link href='{{ asset('genome/lib/jsorolla/styles/fonts/fonts.css') }}' rel='stylesheet' type='text/css'>
    <link href='{{ asset('genome/bower_components/fontawesome/css/font-awesome.min.css') }}' rel='stylesheet' type='text/css'>

    <!--CSS-->
    <link href='{{ asset('genome/lib/jsorolla/src/lib/components/jso-global.css') }}' rel='stylesheet' type='text/css'>
    <link href='{{ asset('genome/lib/jsorolla/src/lib/components/jso-form.css') }}' rel='stylesheet' type='text/css'>


    <!--Web Components-->
    <script src="{{ asset('genome/bower_components/webcomponentsjs/webcomponents-lite.min.js') }}"></script>
    <link rel="import" href="{{ asset('genome/bower_components/iron-flex-layout/classes/iron-flex-layout.html') }}">


    <!-- External -->
    <link rel="stylesheet" href="{{ asset('genome/bower_components/qtip2/jquery.qtip.css') }}">
    <!--END of genome viewer-->
    <script src="{{ asset('genome/bower_components/underscore/underscore-min.js') }}"></script>
    <script src="{{ asset('genome/bower_components/backbone/backbone.js') }}"></script>
    <script src="{{ asset('genome/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <script src="{{ asset('genome/bower_components/qtip2/jquery.qtip.min.js') }}"></script>
    <script src="{{ asset('genome/bower_components/cookies-js/dist/cookies.min.js') }}"></script>
    <script src="{{ asset('genome/bower_components/crypto-js-evanvosberg/core.js') }}"></script>
    <script src="{{ asset('genome/bower_components/crypto-js-evanvosberg/sha1.js') }}"></script>
    <script src="{{ asset('genome/bower_components/highcharts-release/adapters/standalone-framework.js') }}"></script>
    <script src="{{ asset('genome/bower_components/highcharts-release/highcharts.js') }}"></script>
    <script src="{{ asset('genome/bower_components/purl/purl.js') }}"></script>
    <script src="{{ asset('genome/bower_components/jquery-mousewheel/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('genome/bower_components/gl-matrix/dist/gl-matrix-min.js') }}"></script>
    <script src="{{ asset('genome/bower_components/uri.js/src/URI.min.js') }}"></script>
    <script src="{{ asset('genome/lib/jsorolla/vendor/ChemDoodleWeb.js') }}"></script>


    <link rel="import" href="{{ asset('genome/src/genome-maps-element.html') }}">

    <link rel="import" href="{{ asset('genome/conf/theme.html') }}">
</head>

<body unresolved class="fullbleed">
        

<script>

    function getParams(){
        var idx = document.URL.indexOf('?');
        var params = new Array();
        if (idx != -1) {
            var pairs = document.URL.substring(idx+1, document.URL.length).split('&');
            for (var i=0; i<pairs.length; i++){
                nameVal = pairs[i].split('=');
                params[nameVal[0]] = nameVal[1];
                }
        }
        
        return params;
    }

    params = getParams();
    //region = unescape(params["lncrna"]);
    var gene = "{{ $gene }}";
    var region = "{{ $region }}";
    document.write("REGION = " + "12:4395-96150" + "<br>");


    window.addEventListener('WebComponentsReady', function(e) {
        getSpecies(function(s) {
            AVAILABLE_SPECIES = s;
            DEFAULT_SPECIES = AVAILABLE_SPECIES.vertebrates[0];
            var genomeMaps = document.createElement('genome-maps-element');
            genomeMaps.setAttribute('version', '3.2.0');
            document.body.appendChild(genomeMaps);
        });
    });

    function getSpecies(callback) {
        CellBaseManager.get({
            host: CELLBASE_HOST,
            category: "meta",
            subCategory: "species",
            success: function(r) {
                var taxonomies = r.response[0].result[0];
                for (var taxonomy in taxonomies) {
                    var newSpecies = [];
                    for (var i = 0; i < taxonomies[taxonomy].length; i++) {
                        var species = taxonomies[taxonomy][i];
                        for (var j = 0; j < species.assemblies.length; j++) {
                            var s = Utils.clone(species)
                            s.assembly = species.assemblies[j];
                            delete s.assemblies;
                            // console.log('aissa here'+ JSON.stringify(s));
                            newSpecies.push(s)
                        }
                    }
                    taxonomies[taxonomy] = newSpecies;
                }
                callback(taxonomies);
            }
        });
    }

</script>
</body>
</html>